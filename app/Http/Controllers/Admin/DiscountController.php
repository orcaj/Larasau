<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\Discount;
use App\Models\DiscountContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;

class DiscountController extends Controller
{
    public function lists()
    {
        $lists = Discount::orderBy('id','DESC')->get();
        return view('admin.discount.list',['lists'=>$lists]);
    }
    public function discountNew()
    {
        return view('admin.discount.new');
    }
    public function discountEdit($id)
    {
        $item = Discount::find($id);
        return view('admin.discount.edit',['item'=>$item]);
    }
    public function store(Request $request)
    {
        if(!empty($request->id)){
            $gift = Discount::find($request->id);

        }else{
            $gift = new Discount;
            $gift->create_at = time();
        }

        $gift->expire_at = toTimestamp($request->expire_at);
        $gift->title = $request->title;
        $gift->code = $request->code;
        $gift->type = $request->type;
        $gift->off = $request->off;
        $gift->mode = $request->mode;
        $gift->save();
        return redirect('/admin/discount/edit/'.$gift->id);
    }
    public function discountDelete($id){
        Discount::find($id)->delete();
        return back();
    }

    ## Content Off Section
    public function contentNew(){
        $contents = Content::all();
        $categoreis = ContentCategory::all();
        return view('admin.discount.newcontent',['contents'=>$contents,'categoreis'=>$categoreis]);
    }
    public function contentList(){
        $list = DiscountContent::with(['content.user','category'])->orderBy('id','DESC')->get();
        return view('admin.discount.contentlist',['lists'=>$list]);
    }
    public function contentStore(Request $request){
        $fdate = strtotime($request->fdate)+12600;
        $ldate = strtotime($request->ldate)+12600;

        if($request->type == 'content')
            $off_id = $request->off_id_content;
        elseif($request->type == 'category')
            $off_id = $request->off_id_category;
        elseif($request->type == 'all')
            $off_id = 0;
        else
            $off_id = 0;

        $array = [
            'type'=>$request->type,
            'mode'=>$request->mode,
            'off_id'=>$off_id,
            'off'=>$request->off,
            'first_date'=>$fdate,
            'last_date'=>$ldate,
            'create_at'=>time()
        ];

        $discount = DiscountContent::create($array);

        return redirect('/admin/discount/content/edit/'.$discount->id);
    }
    public function contentEditStore($id,Request $request){
        $fdate = strtotime($request->fdate)+12600;
        $ldate = strtotime($request->ldate)+12600;

        if($request->type == 'content')
            $off_id = $request->off_id_content;
        elseif($request->type == 'category')
            $off_id = $request->off_id_category;
        else
            $off_id = '';

        $array = [
            'type'=>$request->type,
            'mode'=>$request->mode,
            'off_id'=>$off_id,
            'off'=>$request->off,
            'first_date'=>$fdate,
            'last_date'=>$ldate,
        ];

        DiscountContent::find($id)->update($array);
        return redirect('/admin/discount/content/edit/'.$id);
    }
    public function contentEdit($id){
        $contentDiscount = DiscountContent::find($id);
        $contents = Content::all();
        $categoreis = ContentCategory::all();
        return view('admin.discount.contentedit',['contents'=>$contents,'categoreis'=>$categoreis,'discount'=>$contentDiscount]);
    }
    public function contentDelete($id){
        DiscountContent::find($id)->delete();
        return back();
    }

    public function contentDraft($id){
        DiscountContent::find($id)->update(['mode'=>'draft']);
        return back();
    }
    public function contentPublish($id){
        DiscountContent::find($id)->update(['mode'=>'publish']);
        return back();
    }
}
