<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\ContentPart;
use App\Models\Sell;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{
    public function user()
    {
        $userCount = User::where('admin','0')->count();
        $adminCount = User::where('admin','1')->count();
        $buyerCount = Sell::distinct('buyer_id')->count('buyer_id');
        $sellerCount = Sell::distinct('user_id')->count('user_id');
        $dayRegister = User::where('create_at','>',strtotime('-'.get_option('chart_day_count',10).' day')+12600)->get();
        return view('admin.report.user',['userCount'=>$userCount,'adminCount'=>$adminCount,'buyerCount'=>$buyerCount,'sellerCount'=>$sellerCount,'dayRegister'=>$dayRegister]);
    }
    public function content()
    {
        $contentCount = Content::where('mode','publish')->count();
        $videoCount = ContentPart::where('mode','publish')->count();
        $dayRegister = Content::where('create_at','>',strtotime('-'.get_option('chart_day_count',10).' day')+12600)->get();
        $videoRegister = ContentPart::where('create_at','>',strtotime('-'.get_option('chart_day_count',10).' day')+12600)->get();
        return view('admin.report.content',['contentCount'=>$contentCount,'videoCount'=>$videoCount,'dayRegister'=>$dayRegister,'videoRegister'=>$videoRegister]);
    }
    public function balance()
    {
        $sellCount = Sell::count();
        $transactionCount = Transaction::count();
        $transactionRegistr = Transaction::where('create_at','>',strtotime('-'.get_option('chart_day_count',10).' day')+12600)->get();
        $dayRegister = Sell::where('create_at','>',strtotime('-'.get_option('chart_day_count',10).' day')+12600)->get();
        $allIncome = Transaction::where('mode','deliver')->sum('price');
        $userIncome = Transaction::where('mode','deliver')->sum('income');
        $siteIncome = $allIncome-$userIncome;
        return view('admin.report.balance',['dayRegister'=>$dayRegister,'transactionRegister'=>$transactionRegistr,'sellCount'=>$sellCount,'transactionCount'=>$transactionCount,'allIncome'=>$allIncome,'userIncome'=>$userIncome,'siteIncome'=>$siteIncome]);
    }
    public function transaction(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));
        if(Input::get('fsdate')!==null && Input::get('lsdate')!==null)
            $lists = Transaction::where('create_at','>',$fdate)->where('create_at','<',$ldate)->with('user','buyer','content')->orderBy('id','DESC')->get();
        else
            $lists = Transaction::with('user','buyer','content')->orderBy('id','DESC')->get();
        $first = $lists->first();
        $last = $lists->last();
        $allPrice = $lists->sum('price');
        $userIncome = $lists->sum('income');
        $siteIncome = $allPrice-$userIncome;
        return view('admin.report.transaction',['lists'=>$lists,'first'=>$first,'last'=>$last,'allPrice'=>$allPrice,'siteIncome'=>$siteIncome]);
    }
}
