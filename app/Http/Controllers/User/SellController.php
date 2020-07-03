<?php

namespace App\Http\Controllers\User;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Models\Balance;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\TransactionCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\In;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Unicodeveloper\Paystack\Facades\Paystack;

class SellController extends Controller
{

    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function sellDownload(){
        global $user;
        $sellList = Sell::with(['buyer','content.metas','transaction','rate'])->where('user_id',$user['id'])->orderBy('id','DESC');
        $count = $sellList->count();
        if(Input::get('p')!=null)
            $sellList->skip(Input::get('p')*20);
        $sellList->take(20);

        ## Update Notifications
        Sell::where('user_id',$user['id'])->where('type','download')->update(['view'=>1]);

        return view('user.sell.download',['lists'=>$sellList->get(),'count'=>$count]);
    }
    public function sellPost(){
        global $user;
        $sellList = Sell::with(['buyer','content','transaction'])->where('user_id',$user['id'])->where('type','post')->where(function ($q){
            $q->where('post_code',null)->orwhere('post_code','')->orWhere('post_confirm','')->orWhere('post_confirm',null);
        })->get();
        $count = $sellList->count();
        if(Input::get('p')!=null)
            $sellList->skip(Input::get('p')*20);
        $sellList->take(20);

        return view('user.sell.post',['lists'=>$sellList,'count'=>$count]);
    }
    public function logs(){
        global $user;
        $logs = Balance::with(['user','exporter'])->where('user_id',$user['id'])->orderBy('id','DESC');
        $count = $logs->count();
        if(Input::get('p')!=null)
            $logs->skip(Input::get('p')*20);
        $logs->take(20);
        return view('user.balance.log',['lists'=>$logs->get(),'count'=>$count]);
    }
    public function charge(){
        global $user;
        return view('user.balance.charge',['user'=>$user]);
    }
    public function chargePay(Request $request){
        global $user;
        if (!is_numeric($request->price) || $request->price == 0)
            return redirect()->back()->with('msg', trans('main.number_only'));

        if($request->type == 'paypal') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName('charge account')
            ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($request->price);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($request->price);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Charge Your Wallet');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(url('/').'/payment/wallet/status')
            ->setCancelUrl(url('/').'/payment/wallet/cancel');
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route('paywithpaypal');
                } else {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('paywithpaypal');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            TransactionCharge::create([
                'user_id'   => $user['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => $payment->getId(),
                'create_at' => time(),
                'bank'      => 'paypal'
            ]);
            /** add payment ID to session **/
            \Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            \Session::put('error', 'Unknown error occurred');
            return Redirect::route('paywithpaypal');
        }
        if($request->type == 'income'){
            if($request->price <= $user['income']){
                User::find($user['id'])->update([
                    'income'=>$user['income']-$request->price,
                    'credit'=>$user['credit']+$request->price
                ]);
                Balance::create([
                    'title'=>trans('main.user_account_charge'),
                    'description'=>trans('main.account_charged'),
                    'type'=>'add',
                    'price'=>$request->price,
                    'mode'=>'auto',
                    'user_id'=>$user['id'],
                    'exporter_id'=>0,
                    'create_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('main.income_deduction'),
                    'description'=>trans('main.charge_transfer'),
                    'type'=>'minus',
                    'price'=>$request->price,
                    'mode'=>'auto',
                    'user_id'=>$user['id'],
                    'exporter_id'=>0,
                    'create_at'=>time()
                ]);
                return redirect()->back()->with('msg',trans('main.account_charged_success'));
            }else{
                return redirect()->back()->with('msg',trans('main.income_not_enough'));
            }
        }
        if($request->type == 'paytm'){
            $payment = PaytmWallet::with('receive');
            $Transaction = TransactionCharge::create([
                'user_id'   => $user['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => $payment->getId(),
                'create_at' => time(),
                'bank'      => 'paytm'
            ]);
            $payment->prepare([
                'order'         => $Transaction->id,
                'user'          => $user['id'],
                'email'         => $user['email'],
                'mobile_number' => '00187654321',
                'amount'        => $Transaction->price,
                'callback_url'  => url('/').'/payment/wallet/status?gateway=paytm'
            ]);

            return $payment->receive();
        }
        if($request->type == 'paystack'){
            $payStack    = new \Unicodeveloper\Paystack\Paystack();
            $Transaction = TransactionCharge::create([
                'user_id'   => $user['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => 0,
                'create_at' => time(),
                'bank'      => 'paystack'
            ]);
            $payStack->getAuthorizationResponse([
                "amount"        => $request->price,
                "reference"     => Paystack::genTranxRef(),
                "email"         => $user['email'],
                "callback_url"  => url('/').'/payment/wallet/status?gateway=paystack',
                'metadata'      => json_encode(['transaction'=>$Transaction->id])
            ]);
            return redirect($payStack->url);
        }

        return redirect()->back()->with('msg',trans('main.feature_disabled'));

    }
    public function report(Request $request){
        global $user;
        $sells = Sell::with(['transaction'])->where('user_id',$user['id'])->where('mode','pay')->orderBy('create_at','DESC');
        if(Input::get('first_date') != null) {
            $first_date = strtotime(Input::get('first_date'));
            $sells->where('create_at','>',$first_date);
        }
        else {
            $first_date = Sell::with(['transaction'])->where('user_id',$user['id'])->where('mode','pay')->orderBy('create_at','DESC')->first();
            if($first_date)
                $first_date = $first_date->create_at;
            else
                $first_date = time();
        }

        if(Input::get('last_date') != null) {
            $last_date = strtotime(Input::get('last_date'));
            $sells->where('create_at','<',$last_date);
        }else{
            $last_date = time();
        }
        $days = ($last_date - $first_date)/86400;
        $prices = 0;
        $income = 0;
        foreach ($sells->get() as $stc){
            $prices += $stc->transaction->price;
            $income += $stc->transaction->income;
        }

        for ($i = 1;$i<13;$i++) {
            $curentYear = date('Y',time());
            $firstDate = mktime('12', '0', '0', $i, '1', $curentYear);
            $lastDate = mktime('12', '0', '0', $i+1, '1', $curentYear);
            $chart['sell'][$i] = Sell::where('user_id',$user['id'])->where('mode','pay')->where('create_at','>',$firstDate)->where('create_at','<',$lastDate)->count();
            $chart['income'][$i] = Transaction::where('user_id',$user['id'])->where('mode','deliver')->where('create_at','>',$firstDate)->where('create_at','<',$lastDate)->sum('income');
        }

        return view('user.balance.report',['user'=>$user,'first_date'=>$request->first_date,'last_date'=>$request->last_date,'days'=>$days,'sellcount'=>$sells->count(),'prices'=>$prices ,'income'=>$income,'chart'=>$chart]);
    }

    public function setPostalCode(Request $request){
        global $user;
        $Sell = Sell::where('user_id',$user['id'])->find($request->sell_id);
        if(!$Sell)
            return redirect()->back()->with('msg',trans('main.failed_update'));

        if($request->post_code == null)
            return redirect()->back()->with('msg',trans('main.parcel_tracking_code'));

        $Sell->post_code = $request->post_code;
        $Sell->post_code_date = time();
        $Sell->save();
        setNotification($user['id'],'sell',$request->sell_id);
        return redirect()->back()->with('msg',trans('main.parcel_tracking_success'));
    }
}
