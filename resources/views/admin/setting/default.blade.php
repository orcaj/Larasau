@extends('admin.newlayout.layout',['breadcom'=>['Settings','Default Placeholders']])
@section('title')
   {{{ trans('admin.default_placeholders') }}}
@endsection
@section('page')

   <div class="card">

      <div class="card-body">
         <div id="images" class="tab-pane active">
            <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

               <div class="form-group">
                  <label class="col-md-3 control-label">{{{ trans('admin.user_avatar') }}}</label>
                  <div class="col-md-6">
                     <div class="input-group">
                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="default_user_avatar"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                        <input type="text" placeholder="Displays as default users profile picture" name="default_user_avatar" dir="ltr" value="{{{$_setting['default_user_avatar'] or ''}}}" class="form-control">
                        <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-md-3 control-label">{{{ trans('admin.user_profile_cover') }}}</label>
                  <div class="col-md-6">
                     <div class="input-group">
                        <span class="input-group-prepend cu-p view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="default_user_cover" >
                           <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                        </span>
                        <input type="text" name="default_user_cover" dir="ltr" placeholder="Displays as user profile header background (1920*200px)" value="{{{$_setting['default_user_cover'] or ''}}}" class="form-control">
                        <span class="input-group-append click-for-upload cu-p">
                           <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                        </span>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-md-3 control-label">{{{ trans('admin.channel_icon') }}}</label>
                  <div class="col-md-6">
                     <div class="input-group">
                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="default_chanel_icon"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                        <input type="text" name="default_chanel_icon" dir="ltr" placeholder="Displays as default channel icon" value="{{{$_setting['default_chanel_icon'] or ''}}}" class="form-control">
                        <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-md-3 control-label">{{{ trans('admin.channel_cover') }}}</label>
                  <div class="col-md-6">
                     <div class="input-group">
                        <span class="cu-p input-group-prepend view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="default_chanel_cover"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                        <input type="text" placeholder="Displays as channel header background (1920*200px)" name="default_chanel_cover" dir="ltr" value="{{{$_setting['default_chanel_cover'] or ''}}}" class="form-control">
                        <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                     </div>
                  </div>
               </div>


               <div class="form-group">
                  <label class="col-md-3 control-label">{{{ trans('admin.customer_dashboard_cover') }}}</label>
                  <div class="col-md-6">
                     <div class="input-group">
                        <span class="cu-p input-group-prepend view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="default_chanel_cover"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                        <input type="text" placeholder="Displays as Customer Dashboard Cover (1920*200px)" name="default_customer_dashboard_cover" dir="ltr" value="{{{$_setting['default_customer_dashboard_cover'] or ''}}}" class="form-control">
                        <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <div class="col-md-12">
                     <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.save_changes') }}}</button>
                  </div>
               </div>

            </form>
         </div>
      </div>
   </div>
@endsection
