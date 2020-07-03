<?php

namespace App\Http\Controllers\Admin;


use App\Models\AdsBox;
use App\Models\AdsPlan;
use App\Models\AdsRequest;
use App\Models\Content;
use App\Models\ContentVip;
use function Couchbase\basicDecoderV1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdsController extends Controller
{
    # Plan Section
    public function plans(){
        $lists = AdsPlan::orderBy('id','DESC')->get();
        return view('admin.ads.list',['lists'=>$lists]);
    }
    public function newPlan(){
        return view('admin.ads.newplan');
    }
    public function newPlanStore(Request $request){
        $newAds = AdsPlan::create($request->all());
        return redirect('admin/ads/plan/edit/'.$newAds->id);
    }
    public function planEdit($id){
        $plan = AdsPlan::find($id);
        return view('admin.ads.planedit',['plan'=>$plan]);
    }
    public function planEditStore($id,Request $request){
        AdsPlan::find($id)->update($request->all());
        return back();
    }
    public function planDelete($id){
        AdsPlan::find($id)->delete();
        return back();
    }

    # Request Section
    public function requests(){
        $lists = AdsRequest::with('plan','user','content')->orderBy('id','DESC')->get();
        return view('admin.ads.requests',['lists'=>$lists]);
    }

    #Boxes
    public function boxs(){
        $lists = AdsBox::orderBy('id','DESC')->get();
        return view('admin.ads.boxs',['lists'=>$lists]);
    }
    public function newbox(){
        return view('admin.ads.newbox');
    }
    public function boxStore(Request $request){
       $new = AdsBox::create($request->all());
       return redirect('/admin/ads/box/edit/'.$new->id);
    }
    public function boxEdit($id){
        $item = AdsBox::find($id);
        return view('admin.ads.editbox',['item'=>$item]);
    }
    public function boxDelete($id){
        AdsBox::find($id)->delete();
        return back();
    }
    public function boxEditStore($id,Request $request){
        AdsBox::find($id)->update($request->all());
        return back();
    }


    ## Vip Section
    public function vipList(){
        $contents = Content::where('mode','publish')->get();
        $lists = ContentVip::with('content')->orderBy('id','DESC')->get();
        return view('admin.ads.vip',['contents'=>$contents,'lists'=>$lists]);
    }
    public function vipStore(Request $request){
        $fdate = strtotime($request->fdate)+12600;
        $ldate = strtotime($request->ldate)+12600;
        $cotnent = Content::find($request->content_id);
        ContentVip::create([
            'content_id'=>$request->content_id,
            'category_id'=>$cotnent->category_id,
            'first_date'=>$fdate,
            'last_date'=>$ldate,
            'mode'=>$request->mode,
            'type'=>$request->type,
            'description'=>$request->description
        ]);
        return back();
    }
    public function vipDelete($id){
        ContentVip::find($id)->delete();
        return back();
    }
    public function vipEdit($id){
        $contents = Content::all();
        $vip = ContentVip::find($id);
        $lists = ContentVip::with('content')->orderBy('id','DESC')->get();
        return view('admin.ads.vipedit',['contents'=>$contents,'lists'=>$lists,'vip'=>$vip]);
    }
    public function vipEditStore(Request $request,$id){
        $fdate = strtotime($request->fdate)+12600;
        $ldate = strtotime($request->ldate)+12600;
        $cotnent = Content::find($request->content_id);
        ContentVip::find($id)->update([
            'content_id'=>$request->content_id,
            'category_id'=>$cotnent->category_id,
            'first_date'=>$fdate,
            'last_date'=>$ldate,
            'mode'=>$request->mode,
            'type'=>$request->type,
            'description'=>$request->description
        ]);
        return back();
    }

}

