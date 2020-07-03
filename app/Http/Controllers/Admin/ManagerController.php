<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Usermeta;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ManagerController extends Controller
{

    public function lists()
    {
        $userList = User::with('usermetas')->where('admin','1')->orderBy('id','DESC')->get();
        return view('admin.manager.list',array('users'=>$userList));
    }
    public function item($id)
    {
        $user = User::where('id',$id)->first();
        $userMetas = Usermeta::where('user_id',$id)->pluck('value','option')->all();
        return view('admin.manager.item',array('user'=>$user,'meta'=>$userMetas));
    }

    public function capatibility($id,Request $request){
        $capatibility_array = $request->capatibility;
        Usermeta::updateOrNew($id,['capatibility'=>serialize($capatibility_array)]);
        return Redirect::to(URL::previous().'#capatibility');
    }

    public function newAdmin(){
        return view('admin.manager.new');
    }

    public function storeadmin(Request $request){

        $duplicateUsername = User::where('username',$request->username)->first();
        $duplicateEmail = User::where('email',$request->email)->first();
        if(!empty($duplicateEmail)){
            $request->session()->flash('ErrorEmail','duplicate');
            return back();
        }
        if(!empty($duplicateUsername)){
            $request->session()->flash('ErrorUsername','duplicate');
            return back();
        }

        if(empty($duplicate)) {
            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = encrypt($request->password);
            $user->name = $request->name;
            $user->mode = $request->mode;
            $user->create_at = time();
            $user->admin = 1;
            $user->last_view = time();
            if ($user->save())
                return redirect('/admin/manager/item/' . $user->id);
        }else{
            $request->session()->flash('Error','duplicate');
            return back();
        }

    }

}
