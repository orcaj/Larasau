<?php

namespace App\Http\Controllers\Admin;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Models\Transaction;

class BalanceController extends Controller
{
    public function lists()
    {
        $lists = Balance::with('exporter','user')->orderBy('id','DESC')->get();
        return view('admin.balance.list',['lists'=>$lists]);
    }
    public function newItem(){
        $users = User::all();
        return view('admin.balance.new',['users'=>$users]);
    }
    public function store(Request $request)
    {
        global $admin;
        $request->request->add(['create_at'=>time(),'exporter_id'=>$admin['id'],'mode'=>'user']);
        $request->request->remove('date');
        $request->request->remove('fdate');
        $request->request->remove('time');
        $balance = Balance::create($request->all());
        if(isset($request->user_id)){
            $userUp = User::find($request->user_id);
            if($request->type == 'add') {
                if($request->account == 'credit')
                    $userUp->update(['credit' => $userUp->credit + $request->price]);
                else
                    $userUp->update(['income' => $userUp->income + $request->price]);
                ## Notification Center
                sendNotification(0,['[b.amount]'=>$request->price,'[b.description]'=>$request->description,'[b.type]'=>'Add'],get_option('notification_template_withdraw_new'),'user',$request->user_id);
            }
            else {
                if($request->account == 'credit')
                    $userUp->update(['credit' => $userUp->credit - $request->price]);
                else
                    $userUp->update(['income' => $userUp->income - $request->price]);
                ## Notification Center
                sendNotification(0,['[b.amount]'=>$request->price,'[b.description]'=>$request->description,'[b.type]'=>'کسر'],get_option('notification_template_withdraw_new'),'user',$request->user_id);
            }
        }
        return redirect('/admin/balance/list');
    }
    public function edit($id)
    {
        $balance = Balance::find($id);
        $users = User::all();
        if(!$balance)
            abort(404);
        return view('admin.balance.edit',['item'=>$balance,'users'=>$users]);
    }
    public function editStore($id,Request $request)
    {
        global $admin;
        $request->request->add(['create_at'=>strtotime($request->date)+timeToSecond($request->time)-12600,'exporter_id'=>$admin['id'],'mode'=>'user']);
        $request->request->remove('date');
        $request->request->remove('fdate');
        $request->request->remove('time');
        Balance::find($id)->update($request->all());
        if(isset($request->user_id)){
            $userUp = User::find($request->user_id);
            if($request->type == 'add')
                $userUp->update(['credit'=>$userUp->credit+$request->price]);
            else
                $userUp->update(['credit'=>$userUp->credit-$request->price]);
        }
        return back();
    }
    public function delete($id)
    {
        Balance::find($id)->delete();
        return back();
    }
    public function withdraw(){
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;
        $users = User::with(['usermetas','sells'=>function($q){
            $q->where('mode','pay')->where('type','post')->where('post_confirm',null);
        }]);
        if(Input::get('fdate')!==null && Input::get('ldate')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->orderBy('id','DESC');
        elseif(Input::get('fdate')!==null && Input::get('ldate')!==null && Input::get('price')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->orderBy('id','DESC');
        elseif(Input::get('fdate')==null && Input::get('ldate')==null && Input::get('user_id')!==null)
            $users->where('income','>=',Input::get('price'))->orderBy('id','DESC');
        else
            $users->orderBy('id','DESC');

        if(Input::get('withdraw') != null)
        {
            $users->where('income','>=',Input::get('withdraw'));
        }else{
            $users->where('income','>=',get_option('site_withdraw_price',0));
        }

        $users = $users->get();
        $first = $users->first();
        $last = $users->last();

        $users_not_apply = [];
        $users_sell_post = [];
        foreach ($users as $index=>$wuser){
            $seller_apply = userMeta($wuser->id,'seller_apply',0);
            if($seller_apply == 0){
                $users->forget($index);
                $users_not_apply[] = $wuser;
            }
            if(count($wuser->sells)>0) {
                $users->forget($index);
                $users_sell_post[] = $wuser;
            }
        }


        $allsum = $users->sum('income');
        return view('admin.balance.withdraw',['users'=>$users,'users_not_apply'=>(object)$users_not_apply, 'users_sell_post'=>(object)$users_sell_post ,'first'=>$first,'last'=>$last,'allsum'=>$allsum]);
    }
    public function withdrawAll(Request $request){
        $fdate = strtotime(Input::get('fdate'));
        $ldate = strtotime(Input::get('ldate'));
        $users = User::with(['usermetas','sells'=>function($q){
            $q->where('mode','pay')->where('type','post')->where('post_confirm',null);
        }]);
        if(Input::get('fdate')!==null && Input::get('ldate')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('income','>=',get_option('site_withdraw_price',0))->orderBy('id','DESC');
        elseif(Input::get('fdate')!==null && Input::get('ldate')!==null && Input::get('price')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('income','>=',Input::get('price'))->orderBy('id','DESC');
        elseif(Input::get('fdate')==null && Input::get('ldate')==null && Input::get('user_id')!==null)
            $users->where('income','>=',Input::get('price'))->orderBy('id','DESC');
        else
            $users->where('income','>=',get_option('site_withdraw_price',0))->orderBy('id','DESC');

        $users = $users->get();
        foreach ($users as $index=>$wuser){
            $seller_apply = userMeta($wuser->id,'seller_apply',0);
            if($seller_apply == 0)
                $users->forget($index);
            if(count($wuser->sells)>0)
                $users->forget($index);
        }

        global $admin;
        foreach ($users as $user){
            Balance::insert([
                'title'=>trans('admin.withdraw_period'),
                'type'=>'minus',
                'price'=>$user->income,
                'mode'=>'user',
                'user_id'=>$user->id,
                'description'=>$request->description,
                'exporter_id'=>$admin['id'],
                'create_at'=>time()
            ]);
            User::find($user->id)->update(['income'=>0]);
            ## Notification Center
            sendNotification(0,['[b.amount]'=>$user->income,'[b.description]'=>$request->description],get_option('notification_template_withdraw_pay'),'user',$user->id);
        }

        return back();
    }
    public function withdrawExcel()
    {
        $fdate = strtotime(Input::get('fdate'));
        $ldate = strtotime(Input::get('ldate'));
        $users = User::with(['usermetas','sells'=>function($q){
            $q->where('mode','pay')->where('type','post')->where('post_confirm','');
        }]);
        if(Input::get('fdate')!==null && Input::get('ldate')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('income','>=',get_option('site_withdraw_price',0))->orderBy('id','DESC');
        elseif(Input::get('fdate')!==null && Input::get('ldate')!==null && Input::get('price')!==null)
            $users->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('income','>=',Input::get('price'))->orderBy('id','DESC');
        elseif(Input::get('fdate')==null && Input::get('ldate')==null && Input::get('user_id')!==null)
            $users->where('income','>=',Input::get('price'))->orderBy('id','DESC');
        else
            $users->where('income','>=',get_option('site_withdraw_price',0))->orderBy('id','DESC');

        $users = $users->get();
        foreach ($users as $index=>$wuser){
            $seller_apply = userMeta($wuser->id,'seller_apply',0);
            if($seller_apply == 0)
                $users->forget($index);
            if(count($wuser->sells)>0)
                $users->forget($index);
        }

        $allsum = $users->sum('income');
        Excel::create(trans('admin.withdrawal_list'), function($excel) use($users,$allsum){
            $excel->sheet('Sheetname', function($sheet) use($users,$allsum){
                $sheet->freezeFirstRow();
                $sheet->setAutoSize(true);
                $sheet->cell('A1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('B1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('C1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('D1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('E1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('F1', function($cell) {$cell->setBackground('#FFAB25');});

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Tahoma',
                        'size'      =>  12,
                        'text-align'  => 'center'
                    )));
                $sheet->appendRow(array(
                    trans('admin.username'),
                    trans('admin.real_name'),
                    trans('admin.amount'),
                    trans('admin.bank_name'),
                    trans('admin.creditcard_number'),
                    trans('admin.account_number')
                ));
                foreach ($users as $user){
                    $meta = arrayToList($user->userMetas,'option','value');

                    if(isset($meta['bank_name']))
                        $bank_name = $meta['bank_name'];
                    else
                        $bank_name = '';

                    if(isset($meta['bank_card']))
                        $bank_card = $meta['bank_card'];
                    else
                        $bank_card = '';

                    if(isset($meta['bank_account']))
                        $bank_account = $meta['bank_account'];
                    else
                        $bank_account = '';

                    $sheet->appendRow(array(
                        $user->username,
                        $user->name,
                        $user->income,
                        $bank_name,
                        $bank_card,
                        $bank_account
                    ));
                }
                $sheet->appendRow(array(
                    trans('admin.total_payment_table_header'),
                    $allsum.trans('admin.currency')
                ));
            });
        })->download('xls');
    }
    public function listsExcel()
    {
        $fdate = strtotime(Input::get('fdate'))+12600;
        $ldate = strtotime(Input::get('ldate'))+12600;
        if(Input::get('fdate')!==null && Input::get('ldate')!==null)
            $lists = Balance::with('exporter','user')->where('create_at','>',$fdate)->where('create_at','<',$ldate)->orderBy('id','DESC')->get();
        elseif(Input::get('fdate')!==null && Input::get('ldate')!==null && Input::get('user_id')!==null)
            $lists = Balance::with('exporter','user')->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('user_id',Input::get('user_id'))->orderBy('id','DESC')->get();
        elseif(Input::get('fdate')==null && Input::get('ldate')==null && Input::get('user_id')!==null)
            $lists = Balance::with('exporter','user')->where('user_id',Input::get('user_id'))->orderBy('id','DESC')->get();
        else
            $lists = Balance::with('exporter','user')->orderBy('id','DESC')->get();
        Excel::create(trans('admin.financial_documents'), function($excel) use($lists){
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

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Tahoma',
                        'size'      =>  12,
                        'text-align'  => 'center'
                    )));
                $sheet->appendRow(array(
                    trans('admin.document_number'),
                    trans('admin.th_date'),
                    trans('admin.description'),
                    trans('admin.document_type'),
                    trans('admin.amount'),
                    trans('admin.creator'),
                    trans('admin.user'),
                    trans('admin.extra_info')
                ));
                foreach ($lists as $item) {
                    if ($item->type == 'add')
                        $type = trans('admin.addiction');
                    else
                        $type = trans('admin.deduction');
                    if ($item->mode == 'user') {
                        $mode = $item->exporter->name;
                    }
                    else {
                        $mode = trans('admin.automatic');
                    }

                    if(empty($item->user))
                        $tuser = '';
                    else
                        $tuser = $item->user->name;


                    $sheet->appendRow(array(
                        $item->id,
                        date('d F Y | H:i', $item->create_at),
                        $item->title,
                        $type,
                        $item->price,
                        $mode,
                        $tuser,
                        $item->description
                    ));
                }
            });
        })->download('xls');
    }
    public function analyze(){
        $users = User::all();
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));
        $lists = Balance::with('exporter','user')->where(function ($q){
            $q->where('user_id','')->orwhere('user_id',0)->orWhere('user_id',null);
        });
        if(Input::get('fsdate')!==null && Input::get('lsdate')!==null)
            $lists->where('create_at','>',$fdate)->where('create_at','<',$ldate)->orderBy('id','DESC');
        elseif(Input::get('fsdate')!==null && Input::get('lsdate')!==null && Input::get('user_id')!==null)
            $lists->where('create_at','>',$fdate)->where('create_at','<',$ldate)->where('user_id',Input::get('user_id'))->orderBy('id','DESC');
        elseif(Input::get('fsdate')==null && Input::get('lsdate')==null && Input::get('user_id')!==null)
            $lists->where('user_id',Input::get('user_id'))->orderBy('id','DESC');
        else
            $lists->orderBy('id','DESC');

        $lists = $lists->get();
        $first = $lists->first();
        $last = $lists->last();
        $alladd = $lists->where('type','add')->sum('price');
        $allminus = $lists->where('type','minus')->sum('price');

        return view('admin.balance.analyze',['lists'=>$lists,'users'=>$users,'first'=>$first,'last'=>$last,'alladd'=>$alladd,'allminus'=>$allminus]);
    }
    public function printer($id)
    {
        $balance = Balance::with('user','exporter')->find($id);
        return view('admin.balance.print',['item'=>$balance]);
    }
}
