<?php

namespace App\Http\Controllers\User;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Models\AdsPlan;
use App\Models\AdsRequest;
use App\Models\Content;
use App\Models\Discount;
use App\Models\DiscountContent;
use App\Models\Sell;
use App\Models\SellRate;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Balance;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Unicodeveloper\Paystack\Facades\Paystack;

class BuyController extends Controller
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

    public function lists(){
        global $user;
        if($user['vendor'] == 1)
            $buyList = Sell::with(['content'=>function($q){
            $q->with(['metas','category','user']);
        },'transaction.balance','rate'=>function($r) use($user){
            $r->where('user_id',$user['id'])->first();
        }])->where('buyer_id',$user['id'])->orderBy('id','DESC')->get();
        else
            $buyList = Sell::with(['content'=>function($q){
                $q->with(['metas','category','user']);
            },'transaction.balance','rate'=>function($r) use($user){
                $r->where('user_id',$user['id'])->first();
            }])->where('buyer_id',$user['id'])->where('type','<>','subscribe')->orderBy('id','DESC')->get();
        return view('user.sell.buy',['list'=>$buyList]);
    }
    public function buyPrint($id){
        global $user;
        $buy = Sell::with(['content'=>function($q){
            $q->with(['metas','category','user']);
        },'transaction.balance'])->where('buyer_id',$user['id'])->find($id);
        return view('user.sell.print',['title'=>trans('main.print_invoice'),'item'=>$buy]);
    }
    public function buyConfirm(Request $request,$id){
        global $user;
        $sell = Sell::where('buyer_id',$user['id'])->find($id);
        if(!$sell)
            return abort(404);

        if($sell->post_confrim != '')
            return redirect()->back()->with('msg',trans('main.parcel_confirm'));

        $sell->update([
            'post_confirm'=>$request->post_confirm,
            'post_feedback'=>$request->post_feedback
        ]);
        return redirect()->back()->with('msg',trans('main.parcel'));
    }

    ## Off Section ##
    public function off(){
        global $user;
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        $userContentIds = $userContent->pluck('id')->toArray();
        $discounts = DiscountContent::with('content')->whereIn('off_id',$userContentIds)->where('type','content')->get();
        return view('user.sell.off',['userContent'=>$userContent,'discounts'=>$discounts]);
    }
    public function offEdit($id){
        global $user;
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        $userContentIds = $userContent->pluck('id')->toArray();
        $discounts = DiscountContent::with('content')->whereIn('off_id',$userContentIds)->where('type','content')->get();
        $discount = DiscountContent::with('content.user')->find($id);
        if($discount->content->user->id == $user['id']) {
            return view('user.sell.off', ['userContent' => $userContent, 'discounts' => $discounts,'discount'=>$discount]);
        }else{
            return abort(404);
        }
    }
    public function offStore(Request $request){
        global $user;
        $check_user_has_content = Content::where('user_id',$user['id'])->where('id',$request->off_id)->count();

        if($check_user_has_content == 1) {
            $fist_date = strtotime($request->first_date) + 12600;
            $last_date = strtotime($request->last_date) + 12600;
            $array = [
                'first_date'=>$fist_date,
                'last_date'=>$last_date,
                'off_id'=>$request->off_id,
                'off'=>$request->off,
                'mode'=>'draft',
                'type'=>'content',
                'create_at'=>time()
            ];
            DiscountContent::create($array);
            return redirect()->back()->with('msg',trans('main.discount_add_success'));
        }
    }
    public function offEditStore($id,Request $request){
        global $user;
        $check_user_has_content = Content::where('user_id',$user['id'])->where('id',$request->off_id)->count();

        if($check_user_has_content == 1) {
            $fist_date = strtotime($request->first_date) + 12600;
            $last_date = strtotime($request->last_date) + 12600;
            $array = [
                'first_date'=>$fist_date,
                'last_date'=>$last_date,
                'off_id'=>$request->off_id,
                'off'=>$request->off,
                'mode'=>'draft',
                'type'=>'content',
                'create_at'=>time()
            ];
            DiscountContent::find($id)->update($array);
            return redirect()->back()->with('msg',trans('main.discount_edit'));
        }
    }
    public function offDelete($id){
        global $user;
        $discount = DiscountContent::with('content.user')->find($id);
        if($discount->content->user->id == $user['id']){
            DiscountContent::find($id)->delete();
            return redirect()->back()->with('msg',trans('main.discount_delete'));
        }else{
            return redirect()->back()->with('msg',trans('main.discount_delete_unable'));
        }
    }

    ## Promotion Section ##
    public function promotion(){
        global $user;
        $plans = AdsPlan::where('mode','publish')->orderBy('id','DESC')->get();
        $list = AdsRequest::with(['plan','content'])->where('user_id',$user['id'])->orderBy('id','DESC')->get();
        return view('user.promotion.promotion',['plans'=>$plans,'list'=>$list]);
    }
    public function promotionBuy($id){
        global $user;
        $plan = AdsPlan::find($id);
        $plans = AdsPlan::where('mode','publish')->orderBy('id','DESC')->get();
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        return view('user.promotion.promotionBuy',['plan'=>$plan,'userContent'=>$userContent,'plans'=>$plans]);
    }
    public function promotionPay(Request $request){
        global $user;
        $plan = AdsPlan::find($request->plan_id);
        $Amount = $plan->price;
        $Description = $request->description;

        if($request->gateway == 'paypal') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName($plan->title)
                ->setCurrency(currency())
                ->setQuantity(1)
                ->setPrice($Amount);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency(currency())
                ->setTotal($Amount);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Purchase Promotion');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(url('/') . '/user/video/promotion/buy/pay/verify')
                ->setCancelUrl(url('/') . '/user/video/promotion/buy/cancel');
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
            $ids = $payment->getId();
            \Session::put('paypal_payment_id', $ids);
            $requestA = [
                'user_id' => $user['id'],
                'content_id' => $request->content_id,
                'plan_id' => $plan->id,
                'description' => $request->description,
                'mode' => 'pending',
                'transaction' => $ids,
                'create_at' => time()
            ];
            AdsRequest::create($requestA);
            /** add payment ID to session **/
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            \Session::put('error', 'Unknown error occurred');
            return Redirect::route('paywithpaypal');
        }
        if($request->gateway == 'paytm'){
            $payment = PaytmWallet::with('receive');
            $requestA = [
                'user_id'       => $user['id'],
                'content_id'    => $request->content_id,
                'plan_id'       => $plan->id,
                'description'   => $request->description,
                'mode'          => 'pending',
                'transaction'   => '0',
                'create_at'     => time()
            ];
            $Transaction = AdsRequest::create($requestA);
            $payment->prepare([
                'order'         => $Transaction->id,
                'user'          => $user['id'],
                'email'         => $user['email'],
                'mobile_number' => '00187654321',
                'amount'        => $Amount,
                'callback_url'  => url('/') . '/user/video/promotion/buy/pay/verify?gateway=paytm'
            ]);
            return $payment->receive();
        }
        if($request->gateway == 'paystack'){
            $payStack    = new \Unicodeveloper\Paystack\Paystack();
            $requestA = [
                'user_id'       => $user['id'],
                'content_id'    => $request->content_id,
                'plan_id'       => $plan->id,
                'description'   => $request->description,
                'mode'          => 'pending',
                'transaction'   => '0',
                'create_at'     => time()
            ];
            $Transaction = AdsRequest::create($requestA);
            $payStack->getAuthorizationResponse([
                "amount"        => $Amount,
                "reference"     => Paystack::genTranxRef(),
                "email"         => $user['email'],
                "callback_url"  => url('/') . '/user/video/promotion/buy/pay/verify?gateway=paystack',
                'metadata'      => json_encode(['transaction'=>$Transaction->id])
            ]);
            return redirect($payStack->url);
        }
    }
    public function promotionVerify(Request $request){
        global $user;
        if(!isset($request->gateway)) {
            $payment_id = \Session::get('paypal_payment_id');
            \Session::forget('paypal_payment_id');
            if (empty($request->PayerID) || empty($request->token)) {
                \Session::put('error', 'Payment failed');
                return Redirect::route('/');
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            $result = $payment->execute($execution, $this->_api_context);
            $request = AdsRequest::with('plan')->where('transaction', $payment_id)->where('user_id', $user['id'])->first();
            $Amount = $request->plan->price;

            if ($result->getState() == 'approved') {

                $request->update(['mode' => 'pay']);

                Balance::create([
                    'title' => trans('main.product_promotion'),
                    'description' => trans('main.promoted'),
                    'type' => 'add',
                    'price' => $Amount,
                    'mode' => 'auto',
                    'user_id' => $user['id'],
                    'exporter_id' => 0,
                    'create_at' => time()
                ]);

                return redirect('/user/video/promotion')->with('msg', trans('main.promotion_success_approval'));
            }
        }
        if(isset($request->gateway) && $request->gateway == 'paytm'){
            $transaction = PaytmWallet::with('receive');
            $Order       = AdsRequest::with('plan')->find($transaction->getOrderId());
            $Amount      = $Order->plan->price;
            if($transaction->isSuccessful()){
                $Order->update(['mode' => 'pay']);
                Balance::create([
                    'title'         => trans('main.product_promotion'),
                    'description'   => trans('main.promoted'),
                    'type'          => 'add',
                    'price'         => $Amount,
                    'mode'          => 'auto',
                    'user_id'       => $user['id'],
                    'exporter_id'   => 0,
                    'create_at'     => time()
                ]);
                return redirect('/user/video/promotion')->with('msg', trans('main.promotion_success_approval'));
            }
        }
        if(isset($request->gateway) && $request->gateway == 'paystack'){
            $payment = Paystack::getPaymentData();
            if(isset($payment['status']) && $payment['status'] == true){
                $Order  = AdsRequest::with('plan')->find($payment['data']['metadata']['transaction']);
                $Amount = $Order->plan->price;
                $Order->update(['mode' => 'pay']);
                Balance::create([
                    'title'         => trans('main.product_promotion'),
                    'description'   => trans('main.promoted'),
                    'type'          => 'add',
                    'price'         => $Amount,
                    'mode'          => 'auto',
                    'user_id'       => $user['id'],
                    'exporter_id'   => 0,
                    'create_at'     => time()
                ]);
                return redirect('/user/video/promotion')->with('msg', trans('main.promotion_success_approval'));
            }
        }

        return redirect('/user/video/promotion')->with('msg',trans('main.promotion_failed_later'));
    }

    ## Subscribe ##
    public function subscribeList(Request $request){
        global $user;
        $buyList = Sell::with(['content'=>function($q){
            $q->with(['metas','category','user']);
        },'transaction.balance','rate'=>function($r) use($user){
            $r->where('user_id',$user['id'])->first();
        }])->where('buyer_id',$user['id'])->where('type','subscribe')->orderBy('id','DESC')->get();
        return view('user.sell.subscribe',['list'=>$buyList]);
    }

    ## Buy Rate ##
    public function buyRateStore($id,$rate){
        global $user;
        $ifHasSell = Sell::where('buyer_id',$user['id'])->find($id);
        if($ifHasSell){
            $sellRate = SellRate::firstOrNew(['user_id'=>$user['id'],'sell_id'=>$id]);
            $sellRate->rate = $rate;
            $sellRate->seller_id = $ifHasSell->user_id;
            $sellRate->save();
            return 1;
        }
        return 0;
    }

}
