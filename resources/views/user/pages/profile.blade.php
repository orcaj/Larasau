@extends('user.layout.layout')
@section('pages')
        <div class="h-20"></div>
        <div class="container-fluid">
            <div class="container">
                <div class="accordion-off col-xs-12">
                    <ul id="accordion" class="accordion off-filters-li">
                        <li class="open">
                            <div class="link"><h2><span class="usericon mdi mdi-account"></span>{{{ trans('main.account_info') }}}</h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu dblock">
                                <div class="h-10"></div>
                                <form method="post" class="form-horizontal" action="/user/profile/store">

                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.realname') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <input type="text" name="name" value="{{{ $user['name'] or '' }}}" class="form-control">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.email') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <input type="text" value="{{{ $user['email'] or '' }}}"  class="form-control text-left disabled" disabled>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" value="Save" class="btn btn-orange pull-left">
                                        </div>
                                    </div>
                                </form>
                                <div class="h-10"></div>
                            </ul>
                        </li>
                        <li>
                            <div class="link"><h2><span class="usericon mdi mdi-account-details"></span> {{{ trans('main.personal_info') }}} </h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu">
                                <div class="h-10"></div>
                                <form method="post" class="form-horizontal" action="/user/profile/meta/store">

                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.biography') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <textarea name="biography" rows="5" class="form-control res-vertical">{{{ $meta['biography'] or '' }}}</textarea>
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.short_biography') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <textarea name="short_biography" maxlength="400" rows="5" class="form-control res-vertical">{{{ $meta['short_biography'] or '' }}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.province') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" class="form-control" name="state" value="{!! $meta['state'] or '' !!}">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.city') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="city" value="{{{ $meta['city'] or '' }}}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.birthday') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="birthday" value="{{{ $meta['birthday'] or '' }}}" class="form-control">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.age') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="old" value="{{{ $meta['old'] or '' }}}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.phone_number') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="phone" value="{{{ $meta['phone'] or '' }}}" class="form-control">
                                        </div>
                                        <div class="col-md-6 tab-con">
                                            <input type="submit" class="btn btn-orange pull-left" value="Save">
                                        </div>
                                    </div>
                                </form>
                                <div class="h-10"></div>
                            </ul>
                        </li>
                        <li>
                            <div class="link"><h2><span class="usericon mdi mdi-credit-card-settings"></span> {{{ trans('main.financial') }}} </h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu">
                                <div class="h-10"></div>
                                @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1)
                                    <div class="alert alert-success">
                                        <p>{{{ trans('main.financial_approved') }}}</p>
                                    </div>
                                @endif
                                <form method="post" class="form-horizontal" action="/user/profile/meta/store">
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.bank_name') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="bank_name" value="{{{ $meta['bank_name'] or '' }}}" class="form-control" @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1) disabled @endif>
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.account_number') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" placeholder="Number Only" name="bank_account" value="{{{ $meta['bank_account'] or '' }}}" class="form-control text-center" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1) disabled @endif>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label class="control-label col-md-1 tab-con">{{{ trans('main.creditcard') }}}</label>
                                            <div class="col-md-5 tab-con">
                                                <input type="text" name="bank_card" class="form-control text-center" dir="ltr" value="{{{ $meta['bank_card'] or '' }}}" @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1) disabled @endif>
                                            </div>
                                            <label class="control-label col-md-1 tab-con">{{{ trans('main.identity_scan') }}}</label>
                                            <div class="col-md-5 tab-con">
                                                <div class="input-group">
                                                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="melli_card"><span class="formicon mdi mdi-eye"></span></span>
                                                    <input type="text" name="melli_card" class="form-control" value="{{{ $meta['melli_card'] or '' }}}" @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1) disabled @endif>
                                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.passport_id') }}}</label>
                                        <div class="col-md-5 tab-con">
                                            <input type="text" name="melli_code" class="form-control text-center" dir="ltr" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" value="{{{ $meta['melli_code'] or '' }}}" @if(isset($userMeta['seller_apply']) && $userMeta['seller_apply']==1) disabled @endif>
                                        </div>
                                        @if(!isset($userMeta['seller_apply']) || $userMeta['seller_apply']!=1)
                                            <div class="col-md-6">
                                                <input type="submit" class="btn btn-orange pull-left" value="Save">
                                            </div>
                                        @endif
                                    </div>
                                </form>
                                <div class="h-10"></div>
                            </ul>
                        </li>
                        <li>
                            <div class="link"><h2><span class="usericon mdi mdi-folder-multiple-image"></span> {{{ trans('main.images') }}} </h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu">
                                <div class="h-10"></div>
                                <form method="post" class="form-horizontal" action="/user/profile/meta/store">
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.avatar') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <div class="input-group">
                                                <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar"><span class="formicon mdi mdi-eye"></span></span>
                                                <input type="text" name="avatar" class="form-control" value="{{{ $meta['avatar'] or '' }}}">
                                                <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                            </div>
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.profile_cover') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <div class="input-group">
                                                <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="profile_image"><span class="formicon mdi mdi-eye"></span></span>
                                                <input type="text" name="profile_image" class="form-control" value="{{{ $meta['profile_image'] or '' }}}">
                                                <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" value="Save" class="btn btn-orange pull-left">
                                        </div>
                                    </div>
                                </form>

                                <div class="h-10"></div>
                            </ul>
                        </li>
                        <li>
                            <div class="link"><h2><span class="usericon mdi mdi-lock-alert"></span> {{{ trans('main.security') }}} </h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu">
                                <div class="h-10"></div>
                                <form method="post" class="form-horizontal" action="/user/security/change">
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.new_password') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <input type="password" name="password" class="form-control text-center">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.retype_password') }}}</label>
                                        <div class="col-md-4 tab-con">
                                            <input type="password" name="repassword" class="form-control text-center">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" value="Change" class="btn btn-orange pull-left">
                                        </div>
                                    </div>
                                </form>
                                <div class="h-10"></div>
                            </ul>
                        </li>
                        <li>
                            <div class="link"><h2><span class="usericon mdi mdi-map-marker"></span> {{{ trans('main.postal') }}} </h2><i class="mdi mdi-chevron-down"></i></div>
                            <ul class="submenu">
                                <div class="h-10"></div>
                                <form method="post" class="form-horizontal" action="/user/profile/meta/store">
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.province') }}}</label>
                                        <div class="col-md-3 tab-con">
                                            <input type="text" class="form-control" name="state" value="{!! $meta['state'] or '' !!}">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.city') }}}</label>
                                        <div class="col-md-3 tab-con">
                                            <input type="text" name="city" value="{{{ $meta['city'] or '' }}}" class="form-control">
                                        </div>
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.zip_code') }}}</label>
                                        <div class="col-md-3 tab-con">
                                            <input type="text" name="postalcode" value="{{{ $meta['postalcode'] or '' }}}" class="form-control text-center">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-1 tab-con">{{{ trans('main.address') }}}</label>
                                        <div class="col-md-7 tab-con">
                                            <textarea name="address" rows="4" class="form-control">{{{ $meta['address'] or '' }}}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="submit" class="btn btn-custom pull-left" value="Save">
                                        </div>
                                    </div>
                                </form>
                                <div class="h-10"></div>
                            </ul>
                        </li>
                </div>
            </div>
        </div>
        <div class="h-10"></div>
@endsection
@section('script')
    <script>$('#profile-hover').addClass('item-box-active');</script>
@endsection
