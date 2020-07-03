<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SellController extends Controller
{
    public function lists(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));

        $lists = Sell::with('user','buyer','content','transaction')->orderBy('id','DESC');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));
        if(Input::get('buyer')!==null)
            $lists->where('buyer_id',Input::get('buyer'));
        if(Input::get('content')!==null)
            $lists->where('content_id',Input::get('content'));
        if(Input::get('type')!==null) {
            switch (Input::get('type')) {
                case 'download':
                    $lists->where('type', 'download');
                    break;
                case 'post':
                    $lists->where('type', 'post');
                    break;
                case 'success':
                    $lists->where('post_feedback', '1');
                    break;
                case 'fail':
                    $lists->where('post_feedback', '2')->orWhere('post_feedback','3');
                    break;
                case 'wait':
                    $lists->where('post_feedback', null)->where('type','post');
                    break;
            }
        }


        $contents = Content::all();
        $users = User::all();
        $lists = $lists->get();
        return view('admin.sell.sell',['lists'=>$lists,'contents'=>$contents,'users'=>$users]);
    }
}
