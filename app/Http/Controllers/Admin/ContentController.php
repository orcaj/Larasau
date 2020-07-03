<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\ContentCategoryFilter;
use App\Models\ContentCategoryFilterTag;
use App\Models\ContentCategoryFilterTagRelation;
use App\Models\ContentComment;
use App\Models\ContentMeta;
use App\Models\ContentPart;
use App\Models\ContentSupport;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\ContentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{

    public function lists()
    {
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;

        $users = User::all();
        $category = ContentCategory::get();
        $lists = Content::with(['category','user','metas'=>function($qm){
            $qm->get()->pluck('option','value');
        },'transactions'=>function($q){
            $q->where('mode','deliver');
        }])->withCount('sells','partsactive')->where(function($w){
            $w->where('mode','publish');
        });


        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('cat')!==null)
            $lists->where('category_id',Input::get('cat'));
        if(Input::get('id')!==null)
            $lists->where('id',Input::get('id'));
        if(Input::get('title')!==null)
            $lists->where('title', 'like', '%'.Input::get('title').'%');


        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count','DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view','DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        }
        else
            $lists->orderBy('id','DESC');


        if(Input::get('order')!=null) {
            switch (Input::get('order')) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                    return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
        }
        }

        $lists = $lists->paginate(10);

        return view('admin.content.list',['lists'=>$lists,'users'=>$users,'category'=>$category]);
    }
    public function waitingList()
    {
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category','user','metas'=>function($qm){
            $qm->get()->pluck('option','value');
        },'transactions'=>function($q){
            $q->where('mode','deliver');
        }])->withCount('sells','partsactive','partsRequest')->where(function ($w){
            $w->where('mode','delete')->orWhere('mode','request');
        });

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('cat')!==null)
            $lists->whereHas('categories',function ($qu){
                $qu->where('category_id',Input::get('cat'));
            });
        if(Input::get('id')!==null)
            $lists->where('id',Input::get('id'));
        if(Input::get('title')!==null)
            $lists->where('title', 'like', '%'.Input::get('title').'%');


        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count','DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view','DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        }
        else
            $lists->orderBy('id','DESC');

        $lists = $lists->paginate(15);

        if(Input::get('order')!=null) {
            switch (Input::get('order')) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                    return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
        }
        }

        return view('admin.content.waiting',['lists'=>$lists,'users'=>$users,'category'=>$category]);
    }
    public function draftList()
    {
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category','user','metas'=>function($qm){
            $qm->get()->pluck('option','value');
        },'transactions'=>function($q){
            $q->where('mode','deliver');
        }])->withCount('sells','partsactive')->where('mode','draft');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('cat')!==null)
            $lists->whereHas('categories',function ($qu){
                $qu->where('category_id',Input::get('cat'));
            });
        if(Input::get('id')!==null)
            $lists->where('id',Input::get('id'));
        if(Input::get('title')!==null)
            $lists->where('title', 'like', '%'.Input::get('title').'%');


        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count','DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view','DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        }
        else
            $lists->orderBy('id','DESC');

        $lists = $lists->paginate(15);

        if(Input::get('order')!=null) {
            switch (Input::get('order')) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                    return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
        }
        }

        return view('admin.content.list',['lists'=>$lists,'users'=>$users,'category'=>$category]);
    }
    public function userContent($id)
    {
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;

        $users = User::all();
        $category = ContentCategory::all();
        $lists = Content::with(['category','user','metas'=>function($qm){
            $qm->get()->pluck('option','value');
        },'transactions'=>function($q){
            $q->where('mode','deliver');
        }])->withCount('sells','partsactive')->where('user_id',$id);

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('cat')!==null)
            $lists->whereHas('categories',function ($qu){
                $qu->where('category_id',Input::get('cat'));
            });
        if(Input::get('id')!==null)
            $lists->where('id',Input::get('id'));
        if(Input::get('title')!==null)
            $lists->where('title', 'like', '%'.Input::get('title').'%');


        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count','DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view','DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        }
        else
            $lists->orderBy('id','DESC');

        $lists = $lists->get();

        if(Input::get('order')!=null) {
            switch (Input::get('order')) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                    return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
        }
        }

        return view('admin.content.list',['lists'=>$lists,'users'=>$users,'category'=>$category]);
    }
    public function edit($id){
        $item = Content::with(['parts','metas','tags'=>function($tq){
            $tq->pluck('tag_id');
        },'category'=>function($q){
            $q->with(['filters'=>function($qs){
                $qs->with('tags');
            }]);
        }])->find($id);
        $meta = arrayToList($item->metas,'option','value');
        $contentMenu = contentMenu();
        $filters = ContentCategoryFilter::where('category_id',$item->category_id)->with('tags')->get();
        $products = Content::where('mode','publish')->get();
        return view('admin.content.edit',['item'=>$item,'meta'=>$meta,'menus'=>$contentMenu,'filters'=>$filters,'products'=>$products]);
    }
    public function store(Request $request,$id,$mode)
    {
        if($mode == 'subscribe'){
            Content::find($id)->update($request->all());
            return Redirect::to(URL::previous().'#subscribe');
        }
        if($mode == 'main'){
            global $admin;
            $request->request->add(['update_at'=>time()]);
            $content = Content::with('user')->find($id);

            ## Notification Center
            if($request->mode == 'publish')
                sendNotification(0,['[u.name]'=>$content->user->name,'[c.title]'=>$content->title],get_option('notification_template_content_publish'),'user',$content->user->id);
            if($request->mode == 'waiting')
                sendNotification(0,['[u.name]'=>$content->user->name,'[c.title]'=>$content->title],get_option('notification_template_content_change'),'user',$content->user->id);


            $content->update($request->all());
            return Redirect::to(URL::previous().'#main');
        }
        if($mode == 'meta'){
            $request = $request->all();

            if(isset($request['precourse']))
                $request['precourse'] = implode(',',$request['precourse']).',';

            foreach ($request as $key => $val){
                $res = ContentMeta::updateOrCreate(
                    ['content_id'=>$id,'option'=>$key],
                    ['value'=>$val]
                );
            }
            return Redirect::to(URL::previous().'#meta');
        }
        if($mode == 'tags'){
            ContentCategoryFilterTagRelation::where('content_id',$id)->delete();
            if($request->tags!=null){
                foreach ($request->tags as $tag){
                    ContentCategoryFilterTagRelation::create(['content_id'=>$id,'tag_id'=>$tag]);
                }
            }
            return Redirect::to(URL::previous().'#filter');
        }
    }
    public function excel(){
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;
        $lists = Content::with(['category','user','metas'=>function($qm){
            $qm->get()->pluck('option','value');
        },'transactions'=>function($q){
            $q->where('mode','deliver');
        }])->withCount('sells','partsactive');
        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('cat')!==null)
            $lists->where('category_id',Input::get('cat'));
        if(Input::get('id')!==null)
            $lists->where('id',Input::get('id'));
        if(Input::get('title')!==null)
            $lists->where('title', 'like', '%'.Input::get('title').'%');
        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $lists->orderBy('sells_count');
                    break;
                case 'selld':
                    $lists->orderBy('sells_count','DESC');
                    break;
                case 'viewa':
                    $lists->orderBy('view');
                    break;
                case 'viewd':
                    $lists->orderBy('view','DESC');
                    break;
                case 'datea':
                    $lists->orderBy('id');
                    break;

            }
        }
        else
            $lists->orderBy('id','DESC');
        $lists = $lists->get();
        if(Input::get('order')!=null) {
            switch (Input::get('order')) {
                case 'priced':
                    $lists = $lists->sortByDesc(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'pricea':
                    $lists = $lists->sortBy(function ($item) {
                        return $item->metas->where('option', 'price')->pluck('value');
                    });
                    break;
                case 'suma':
                    $lists = $lists->sortBy(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
                case 'sumd':
                    $lists = $lists->sortByDesc(function($item){
                        return $item->transactions->sum('price');
                    });
                    break;
            }
        }

        Excel::create(trans('admin.courses'), function($excel) use($lists){
            $excel->sheet('Sheetname', function($sheet) use($lists){
                $sheet->freezeFirstRow();
                $sheet->setAutoSize(true);
                $sheet->cell('A1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('B1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('C1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('D1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('E1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('F1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('G1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('H1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('I1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('J1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('K1', function($cell) {$cell->setBackground('#FFAB25');});

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Tahoma',
                        'size'      =>  12,
                        'text-align'  => 'center'
                    )));
                $sheet->appendRow(array(
                    trans('admin.course_title'),
                    trans('admin.th_date'),
                    trans('admin.th_vendor'),
                    trans('admin.sales'),
                    trans('admin.parts'),
                    trans('admin.income'),
                    trans('admin.views'),
                    trans('admin.price'),
                    trans('admin.category'),
                    trans('admin.publish_type'),
                    trans('admin.th_status')
                ));
                foreach ($lists as $item) {
                    $meta = arrayToList($item->metas,'option','value');

                    if($item->private==1)
                        $privete = trans('admin.exclusive');
                    else
                        $privete = trans('admin.open');

                    if($item->mode == 'publish')
                        $mode = trans('admin.published');
                    elseif($item->mode == 'waiting')
                        $mode = trans('admin.waiting');
                    else
                        $mode = trans('admin.unpublished');

                    if(isset($item->category->title))
                        $category = $item->category->title;
                    else
                        $category = trans('admin.not_defined');


                    $sheet->appendRow(array(
                        $item->title,
                        date('d F Y | H:i', $item->create_at),
                        $item->user->username,
                        $item->sells_count,
                        $item->partsactive_count,
                        $item->transactions->sum('price'),
                        $item->view,
                        isset($meta['price'])?$meta['price']:'',
                        $category,
                        $privete,
                        $mode
                    ));
                }
            });
        })->download('xls');
        return back();
    }
    public function delete($id){
        Content::find($id)->delete();
        return back();
    }

    ## Comment Section
    public function comments(){
        $comments = ContentComment::with('content')->orderBy('id','DESC')->get();
        return view('admin.content.comments',['comments'=>$comments]);
    }
    public function commentDelete($id)
    {
        ContentComment::find($id)->delete();
        return back();
    }
    public function commentEdit($id)
    {
        $item = ContentComment::find($id);
        return view('admin.content.commentedit',['item'=>$item]);
    }
    public function commentStore(Request $request)
    {
        $comment = ContentComment::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }
    public function commentView($action,$id)
    {
        $comment = ContentComment::with('content.user')->find($id);
        $comment->mode = $action;
        $comment->save();
        ContentComment::where('parent',$id)->update(['mode'=>$action]);

        ## Notification Center
        if($action == 'publish')
        sendNotification(0,['[c.title]'=>$comment->content->title],get_option('notification_template_content_comment_new'),'user',$comment->content->user->id);

        return back();
    }

    ## Support Section
    public function supports(){
        $comments = ContentSupport::with('content')->orderBy('id','DESC')->get();
        return view('admin.content.supports',['comments'=>$comments]);
    }
    public function supportDelete($id)
    {
        ContentSupport::find($id)->delete();
        return back();
    }
    public function supportEdit($id)
    {
        $item = ContentSupport::find($id);
        return view('admin.content.supportedit',['item'=>$item]);
    }
    public function supportStore(Request $request)
    {
        $comment = ContentSupport::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }
    public function supportView($action,$id)
    {
        $comment = ContentSupport::with('content.user')->find($id);
        $comment->mode = $action;
        $comment->save();

        ## Notification Center
        if($action == 'publish')
            sendNotification(0,['[c.title]'=>$comment->content->title],get_option('notification_template_content_support_new'),'user',$comment->content->user->id);


        return back();
    }

    ## Category Section
    public function category()
    {
        $list = ContentCategory::withCount('contents','childs','filters')->where('parent_id','0')->get();
        return view('admin.content.categroy',array('lists'=>$list));
    }
    public function categoryEdit($id)
    {
        $list = ContentCategory::withCount('contents','childs')->where('parent_id','0')->get();
        $item = ContentCategory::find($id);
        return view('admin.content.categroyedit',array('lists'=>$list,'item'=>$item));
    }
    public function categoryStore(Request $request)
    {
        if($request->edit != '') {
            $category = ContentCategory::find($request->edit);
            $category->title = $request->title;
            $category->image = $request->image;
            $category->class = $request->class;
            $category->req_icon = $request->req_icon;
            $category->commision = $request->commision;
            $category->parent_id = $request->parent_id;
            $category->color = $request->color;
            $category->background = $request->background;
            $category->icon = $request->icon;
            $category->app_icon = $request->app_icon;
            $category->save();
        }
        else {
            $category = new ContentCategory;
            $category->title = $request->title;
            $category->image = $request->image;
            $category->commision = $request->commision;
            $category->parent_id = $request->parent_id;
            $category->class = $request->class;
            $category->color = $request->color;
            $category->background = $request->background;
            $category->req_icon = $request->req_icon;
            $category->icon = $request->icon;
            $category->save();
        }
        return back();
    }
    public function categoryDelete($id)
    {
        ContentCategory::find($id)->delete();
        return back();
    }
    public function childs($id)
    {
        $list = ContentCategory::withCount('contents','filters')->where('parent_id',$id)->get();
        $item = ContentCategory::find($id);
        return view('admin.content.categroychild',array('lists'=>$list,'item'=>$item));
    }

    ## Filter Section
    public function categoryFilter($id)
    {
        $filters = ContentCategoryFilter::withCount('tags')->where('category_id',$id)->orderBy('sort')->get();
        return view('admin.content.filters',['lists'=>$filters,'id'=>$id]);
    }
    public function categoryFilterStore(Request $request,$mode)
    {
        if($mode == 'new')
            ContentCategoryFilter::insert($request->all());
        if($mode == 'edit')
            ContentCategoryFilter::find($request->id)->update(['filter'=>$request->filter,'sort'=>$request->sort]);
        return back();
    }
    public function categoryFilterDelete($id)
    {
        ContentCategoryFilter::find($id)->delete();
        return back();
    }
    public function categoryFilterEdit($id,$fid){
        $filters = ContentCategoryFilter::where('category_id',$id)->orderBy('sort')->get();
        $item = ContentCategoryFilter::find($fid);
        return view('admin.content.filtersedit',['lists'=>$filters,'id'=>$id,'item'=>$item]);
    }

    ## Tags Section
    public function categoryFilterTags($id){
        $filter = ContentCategoryFilter::find($id);
        $tags = ContentCategoryFilterTag::where('filter_id',$id)->orderBy('sort')->get();
        return view('admin.content.tags',['lists'=>$tags,'filter'=>$filter,'id'=>$id]);
    }
    public function categoryFilterTagNew(Request $request,$mode){
        if($mode == 'new')
            ContentCategoryFilterTag::insert($request->all());
        if($mode == 'edit') {
            ContentCategoryFilterTag::find($request->id)->update(['tag'=>$request->tag,'sort'=>$request->sort]);
        }
        return back();
    }
    public function categoryFilterTagDelete($id)
    {
        ContentCategoryFilterTag::find($id)->delete();
        return back();
    }
    public function categoryFilterTagEdit($id,$fid){
        $filter = ContentCategoryFilter::find($id);
        $tags = ContentCategoryFilterTag::where('filter_id',$id)->orderBy('sort')->get();
        $item = ContentCategoryFilterTag::find($fid);
        return view('admin.content.tagsedit',['filter'=>$filter,'lists'=>$tags,'id'=>$id,'item'=>$item]);
    }

    ## Part Section
    public function partDelete($id)
    {
        ContentPart::find($id)->delete();
        return Redirect::to(URL::previous().'#parts');
    }
    public function partEdit($id,$pid){
        $item = Content::with('parts','metas','category')->where('id',$id)->first();
        $meta = arrayToList($item->metas,'option','value');
        $contentMenu = contentMenu();
        $part = ContentPart::find($pid);
        $products = Content::where('mode','publish')->get();

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = 'source/content-'.$id.'/video/part-'.$pid.'.mp4';

        if(file_exists($storagePath.$file))
            $convert = $storagePath.$file;
        else
            $convert = false;

        return view('admin.content.partedit',['item'=>$item,'meta'=>$meta,'menus'=>$contentMenu,'part'=>$part,'convert'=>$convert,'products'=>$products]);
    }
    public function partStore($id,Request $request)
    {
        ContentPart::find($id)->update($request->all());
        return Redirect::to(URL::previous().'#part');
    }

    ## Usage
    public function contentUsage($id, Request $request){
        $list = \App\Models\Usage::with('user')->where('product_id', $id)->select('user_id', DB::raw('count(*) as total'))->groupBy('user_id')->orderBy('total','DESC')->get();
        return view('admin.content.usage',['list'=>$list]);
    }
}
