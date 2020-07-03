<?php

namespace App\Http\Controllers\User;

use App\Models\ContentCategory;
use App\Models\ContentPart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentMeta;
use Illuminate\Support\Facades\Redirect;

class ContentController extends Controller
{

    public function contentList()
    {
        global $user;
        $lists = Content::where('user_id',$user['id'])->with('category')->withCount('sells','partsactive')->orderBy('id','DESC')->get();
        return view('user.content.list',['lists'=>$lists]);
    }
    public function contentDelete($id){
        global $user;
        Content::where('id',$id)->where('user_id',$user['id'])->update(['mode'=>'delete']);
        return back();
    }
    public function contentRequest($id){
        global $user;
        $content = Content::where('user_id',$user['id'])->find($id);

        ## Notification Center
        sendNotification(0,['[u.name]'=>$user['name'],'[c.title]'=>$content->title],get_option('notification_template_content_pre_publish'),'user',$user['id']);

        $content->update(['mode'=>'request']);
        return back();
    }
    public function contentDraft($id){
        global $user;
        Content::where('id',$id)->where('user_id',$user['id'])->update(['mode'=>'draft']);
        return back();
    }

    ## New Section ##

    public function contentNew(){
        $contentMenu = ContentCategory::with(['childs','filters'=>function($q){
            $q->with(['tags']);
        }])->get();
        return view('user.content.new',['menus'=>$contentMenu]);
    }
    public function contentStore(Request $request){
        global $user;
        $newContent = $request->all();
        $newContent['create_at'] = time();
        $newContent['mode'] = 'draft';
        $newContent['user_id'] = $user['id'];
        $content_id = Content::insertGetId($newContent);
        return redirect('/user/content/edit/'.$content_id);

    }

    ## Edit Section ##

    public function contentEdit($id){
        global $user;
        $item = Content::with('parts','filters')->where('id',$id)->where('user_id',$user['id'])->first();
        $meta = arrayToList($item->metas,'option','value');
        if(isset($meta['precourse']) && $meta['precourse'] != '') {
            $preCourseContent = Content::where('mode', 'publish')->whereIn('id', explode(',',rtrim($meta['precourse'], ',')))->get();
        }else{
            $preCourseContent = [];
        }
        $contentMenu = ContentCategory::with(['childs','filters'=>function($q){
            $q->with(['tags']);
        }])->get();
        return view('user.content.edit',['item'=>$item,'meta'=>$meta,'menus'=>$contentMenu,'preCourse'=>$preCourseContent]);
    }
    public function contentEditStore($id,Request $request){
        global $user;
        $request->request->add(['mode'=>'draft']);
        $content = Content::where('user_id',$user['id'])->find($id);

        if($content)
        {
            $request = $request->all();
            print_r($request);
            if(isset($request['filters']) && count($request['filters'])>0) {
                $content->filters()->sync($request['filters']);
            }
            unset($request['filters']);
            $content->update($request);
            echo 'true';
        }
        else
        {
            echo 'false';
        }

    }
    public function contentEditStoreRequest($id,Request $request){
        global $user;
        $request->request->add(['mode'=>'request']);
        $content = Content::where('user_id',$user['id'])->find($id);

        if($content)
        {
            $request = $request->all();
            print_r($request);
            if(isset($request['filters']) && count($request['filters'])>0) {
                $content->filters()->sync($request['filters']);
            }
            unset($request['filters']);
            $content->update($request);
            echo 'true';
        }
        else
        {
            echo 'false';
        }

    }
    public function contentMetaStore($id,Request $request){
        global $user;
        $content = Content::where('user_id',$user['id'])->find($id);
        if($content){
            ContentMeta::updateOrNew($content->id,$request->all());
            echo 'true';
        }
    }

    ## Part Section ##

    public function contentPartList($id){
        global $user;
        $content = Content::with(['parts'=>function($q){$q->orderBy('sort');}])->where('user_id',$user['id'])->find($id);
        return view('user.content.part.list',['lists'=>$content->parts,'id'=>$id]);
    }
    public function contentPartNew($id){
        return view('user.content.part.new',['id'=>$id]);
    }
    public function contentPartEdit($id){
        global $user;
        $contentPart = ContentPart::with('content')->find($id);
        if($contentPart && $contentPart->content->user_id = $user['id']){
            return $contentPart;
        }else{
            return 0;
        }
    }
    public function contentPartDelete($id){
        global $user;
        $part = ContentPart::with('content')->find($id);
        if($part->content->user_id == $user['id']){
            $part->update(['mode'=>'delete']);
        }
        return back();
    }
    public function contentPartDraft($id){
        global $user;
        $part = ContentPart::with('content')->find($id);
        if($part->content->user_id == $user['id']){
            $part->update(['mode'=>'draft']);
        }
        return back();
    }
    public function contentPartRequest($id){
        global $user;
        $part = ContentPart::with('content')->find($id);
        if($part->content->user_id == $user['id']){
            $part->update(['mode'=>'request']);
        }
        return back();
    }
    public function contentPartStore(Request $request)
    {
        global $user;
        $content = Content::where('user_id',$user['id'])->find($request->content_id);
        if($content){
            $request->request->add(['create_at'=>time(),'mode'=>'request']);
            $newPart = ContentPart::create($request->all());
            echo $newPart->id;
        }else{
            echo 'error';
        }

    }
    public function contentPartEditStore(Request $request,$id)
    {
        global $user;
        $content = Content::where('user_id',$user['id'])->find($request->content_id);
        if($content){
            $request->request->add(['mode'=>'request']);
            ContentPart::find($id)->update($request->all());
            return back();
        }else{
            return back();
        }

    }

    ## Json Section
    public function contentPartsJson($id){
        global $user;
        $result = [];
        $content = Content::with(['parts'=>function($q){$q->orderBy('sort');}])->where('user_id',$user['id'])->find($id);
        foreach ($content->parts as $index=>$part){
            $result[$index] = $part;
            $result[$index]['create_at'] = date('d F Y H:i',$part['create_at']);
            switch ($part['mode']){
                case 'request':
                    $result[$index]['mode'] = '<b style="color:orange">Waiting</b>';
                    break;
                case 'delete':
                    $result[$index]['mode'] = '<b style="color:red;">Delete</b>';
                    break;
                case 'draft':
                    $result[$index]['mode'] = '<b style="color:goldenrod;">Save</b>';
                    break;
                case 'publish':
                    $result[$index]['mode'] = '<b style="color:green;">Publish</b>';
                    break;
            }
            switch ($part['free']){
                case '1':
                    $result[$index]['price'] = '<img src="/assets/images/svg/ulck.svg" style="width: 20px;height: auto;" title="Paid">';
                    $result[$index]['title'] .= '&nbsp;(Free)&nbsp;';
                    break;
                case '0':
                    $result[$index]['price'] = '<img src="/assets/images/svg/lck.svg" style="width: 20px;height: auto;" title="Free">';;
                    break;
                case '':
                    $result[$index]['price'] = '<img src="/assets/images/svg/ulck.svg" style="width: 20px;height: auto;" title="Paid">';
                    break;
            }
        }
        return $result;
    }
}
