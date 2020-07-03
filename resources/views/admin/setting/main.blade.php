@extends('admin.newlayout.layout',['breadcom'=>['Settings','General']])
@section('title')
   {{{ trans('admin.general_settings') }}}
@endsection
@section('page')
   <div class="card">
      <div class="card-body">
         <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#main" data-toggle="tab"> {{{ trans('admin.general') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#withdraw" data-toggle="tab"> {{{ trans('admin.financial') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#factor" data-toggle="tab"> {{{ trans('admin.invoice') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#gateway" data-toggle="tab"> {{{ trans('admin.payment') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#popup" data-toggle="tab"> {{{ trans('admin.popup') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#videoAds" data-toggle="tab"> {{{ trans('admin.video_ads') }}} </a></li>
            <li class="nav-item"><a class="nav-link" href="#mainSlide" data-toggle="tab"> {{{ trans('admin.home_hero') }}} </a></li>
         </ul>
         <div class="tab-content">
            <div id="main" class="tab-pane active">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.site_name') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control" name="site_title" value="{{{ $_setting['site_title'] or ''}}}">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.site_description') }}}</label>
                     <div class="col-md-6">
                        <textarea class="form-control" name="site_description">{{{ $_setting['site_description'] or ''}}}</textarea>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.site_email') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control" name="site_email" value="{{{ $_setting['site_email'] or ''}}}">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{!! trans('admin.site_language') !!}</label>
                     <div class="col-md-6">
                        <select name="site_language" class="form-control">
                           <option value="ab" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ab') selected @endif>Abkhazian</option>
                           <option value="aa" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'aa') selected @endif>Afar</option>
                           <option value="AF" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'af') selected @endif>Afrikanns</option>
                           <option value="SQ" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sq') selected @endif>Albanian</option>
                           <option value="AM" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'am') selected @endif>Amharic</option>
                           <option value="AR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ar') selected @endif>Arabic</option>
                           <option value="HY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'hy') selected @endif>Armenian</option>
                           <option value="AS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'as') selected @endif>Assamese</option>
                           <option value="AY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ay') selected @endif>Aymara</option>
                           <option value="AZ" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'az') selected @endif>Azerbaijani</option>
                           <option value="BA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ba') selected @endif>Bashkir</option>
                           <option value="EU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'eu') selected @endif>Basque</option>
                           <option value="BN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'bn') selected @endif>Bengali, Bangla</option>
                           <option value="DZ" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'dz') selected @endif>Bhutani</option>
                           <option value="BH" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'bh') selected @endif>Bihari</option>
                           <option value="BI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'bi') selected @endif>Bislama</option>
                           <option value="BR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'br') selected @endif>Breton</option>
                           <option value="BG" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'bg') selected @endif>Bulgarian</option>
                           <option value="MY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'my') selected @endif>Burmese</option>
                           <option value="BE" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'be') selected @endif>Byelorussian</option>
                           <option value="KM" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'km') selected @endif>Cambodian</option>
                           <option value="CA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ca') selected @endif>Catalan</option>
                           <option value="ZH" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'zh') selected @endif>Chinese (Mandarin)</option>
                           <option value="CO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'co') selected @endif>Corsican</option>
                           <option value="HR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'hr') selected @endif>Croation</option>
                           <option value="CS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'cs') selected @endif>Czech</option>
                           <option value="DA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'da') selected @endif>Danish</option>
                           <option value="NL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'nl') selected @endif>Dutch</option>
                           <option value="EN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'en') selected @endif>English, American</option>
                           <option value="EO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'eo') selected @endif>Esperanto</option>
                           <option value="ET" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'et') selected @endif>Estonian</option>
                           <option value="FO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fo') selected @endif>Faeroese</option>
                           <option value="FJ" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fj') selected @endif>Fiji</option>
                           <option value="FI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fi') selected @endif>Finnish</option>
                           <option value="FR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fr') selected @endif>French</option>
                           <option value="FY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fy') selected @endif>Frisian</option>
                           <option value="GD" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'gd') selected @endif>Gaelic (Scots Gaelic)</option>
                           <option value="GL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'gl') selected @endif>Galician</option>
                           <option value="KA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ka') selected @endif>Georgian</option>
                           <option value="DE" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'de') selected @endif>German</option>
                           <option value="EL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'el') selected @endif>Greek</option>
                           <option value="KL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'kl') selected @endif>Greenlandic</option>
                           <option value="GN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'gn') selected @endif>Guarani</option>
                           <option value="GU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'gu') selected @endif>Gujarati</option>
                           <option value="HA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ha') selected @endif>Hausa</option>
                           <option value="IW" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'iw') selected @endif>Hebrew</option>
                           <option value="HI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'hi') selected @endif>Hindi</option>
                           <option value="HU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'hu') selected @endif>Hungarian</option>
                           <option value="IS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'is') selected @endif>Icelandic</option>
                           <option value="IN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'in') selected @endif>Indonesian</option>
                           <option value="IA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ia') selected @endif>Interlingua</option>
                           <option value="IE" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ie') selected @endif>Interlingue</option>
                           <option value="IK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ik') selected @endif>Inupiak</option>
                           <option value="GA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ga') selected @endif>Irish</option>
                           <option value="IT" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'it') selected @endif>Italian</option>
                           <option value="JA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ja') selected @endif>Japanese</option>
                           <option value="JW" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'jw') selected @endif>Javanese</option>
                           <option value="KN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'kn') selected @endif>Kannada</option>
                           <option value="KS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ks') selected @endif>Kashmiri</option>
                           <option value="KK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'kk') selected @endif>Kazakh</option>
                           <option value="RW" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'rw') selected @endif>Kinyarwanda</option>
                           <option value="KY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ky') selected @endif>Kirghiz</option>
                           <option value="RN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'rn') selected @endif>Kirundi</option>
                           <option value="KO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ko') selected @endif>Korean</option>
                           <option value="KU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ku') selected @endif>Kurdish</option>
                           <option value="LO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'lo') selected @endif>Laothian</option>
                           <option value="LA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'la') selected @endif>Latin</option>
                           <option value="LV" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'lv') selected @endif>Latvian, Lettish</option>
                           <option value="LN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ln') selected @endif>Lingala</option>
                           <option value="LT" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'lt') selected @endif>Lithuanian</option>
                           <option value="MK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mk') selected @endif>Macedonian</option>
                           <option value="MG" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mg') selected @endif>Malagasy</option>
                           <option value="MS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ms') selected @endif>Malay</option>
                           <option value="ML" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ml') selected @endif>Malayalam</option>
                           <option value="MT" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mt') selected @endif>Maltese</option>
                           <option value="MI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mi') selected @endif>Maori</option>
                           <option value="MR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mr') selected @endif>Marathi</option>
                           <option value="MO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mo') selected @endif>Moldavian</option>
                           <option value="MN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'mn') selected @endif>Mongolian</option>
                           <option value="NA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'na') selected @endif>Nauru</option>
                           <option value="NE" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ne') selected @endif>Nepali</option>
                           <option value="NO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'no') selected @endif>Norwegian</option>
                           <option value="OC" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'oc') selected @endif>Occitan</option>
                           <option value="OR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'or') selected @endif>Oriya</option>
                           <option value="OM" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'om') selected @endif>Oromo, Afan</option>
                           <option value="PS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ps') selected @endif>Pashto, Pushto</option>
                           <option value="FA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'fa') selected @endif>Persian</option>
                           <option value="PL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'pl') selected @endif>Polish</option>
                           <option value="PT" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'pt') selected @endif>Portuguese</option>
                           <option value="PA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'pa') selected @endif>Punjabi</option>
                           <option value="QU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'qu') selected @endif>Quechua</option>
                           <option value="RM" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'rm') selected @endif>Rhaeto-Romance</option>
                           <option value="RO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ro') selected @endif>Romanian</option>
                           <option value="RU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ru') selected @endif>Russian</option>
                           <option value="SM" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sm') selected @endif>Samoan</option>
                           <option value="SG" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sg') selected @endif>Sangro</option>
                           <option value="SA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sa') selected @endif>Sanskrit</option>
                           <option value="SR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sr') selected @endif>Serbian</option>
                           <option value="SH" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sh') selected @endif>Serbo-Croatian</option>
                           <option value="ST" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'st') selected @endif>Sesotho</option>
                           <option value="TN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tn') selected @endif>Setswana</option>
                           <option value="SN" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sn') selected @endif>Shona</option>
                           <option value="SD" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sd') selected @endif>Sindhi</option>
                           <option value="SI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'si') selected @endif>Singhalese</option>
                           <option value="SS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ss') selected @endif>Siswati</option>
                           <option value="SK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sk') selected @endif>Slovak</option>
                           <option value="SL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sl') selected @endif>Slovenian</option>
                           <option value="SO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'so') selected @endif>Somali</option>
                           <option value="ES" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'es') selected @endif>Spanish</option>
                           <option value="SU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'su') selected @endif>Sudanese</option>
                           <option value="SW" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sw') selected @endif>Swahili</option>
                           <option value="SV" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'sv') selected @endif>Swedish</option>
                           <option value="TL" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tl') selected @endif>Tagalog</option>
                           <option value="TG" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tg') selected @endif>Tajik</option>
                           <option value="TA" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ta') selected @endif>Tamil</option>
                           <option value="TT" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tt') selected @endif>Tatar</option>
                           <option value="TE" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'te') selected @endif>Telugu</option>
                           <option value="TH" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'th') selected @endif>Thai</option>
                           <option value="BO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'bo') selected @endif>Tibetan</option>
                           <option value="TI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ti') selected @endif>Tigrinya</option>
                           <option value="TO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'to') selected @endif>Tonga</option>
                           <option value="TS" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ts') selected @endif>Tsonga</option>
                           <option value="TR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tr') selected @endif>Turkish</option>
                           <option value="TK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tk') selected @endif>Turkmen</option>
                           <option value="TW" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'tw') selected @endif>Twi</option>
                           <option value="UK" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'uk') selected @endif>Ukranian</option>
                           <option value="UR" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ur') selected @endif>Urdu</option>
                           <option value="UZ" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'uz') selected @endif>Uzbek</option>
                           <option value="VI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'vi') selected @endif>Vietnamese</option>
                           <option value="VO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'vo') selected @endif>Volapuk</option>
                           <option value="CY" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'cy') selected @endif>Welsh</option>
                           <option value="WO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'wo') selected @endif>Wolof</option>
                           <option value="XH" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'xh') selected @endif>Xhosa</option>
                           <option value="JI" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'ji') selected @endif>Yiddish</option>
                           <option value="YO" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'yo') selected @endif>Yoruba</option>
                           <option value="ZU" @if(isset($_setting['site_language']) && $_setting['site_language'] == 'zu') selected @endif>Zulu</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.logo') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="site_logo" >
                              <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                           </span>
                           <input type="text" name="site_logo" dir="ltr" placeholder="Displays on header (55*55px)" value="{{{$_setting['site_logo'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p" >
                              <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                           </span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.logotype') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="cu-p input-group-prepend view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="site_logo_type"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="site_logo_type" dir="ltr" value="{{{$_setting['site_logo_type'] or ''}}}" placeholder="Displays on header ,Hides when scrolling. (200*55px)" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.videos_watermark') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="video_watermark"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="video_watermark" dir="ltr" value="{{{$_setting['video_watermark'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.requests_icon') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend cu-p view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="request_page_icon" ><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="request_page_icon" dir="ltr" placeholder="Displays on requests page header (80*80px)" value="{{{$_setting['request_page_icon'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.upload_bg') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_page_background"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="upload_page_background" dir="ltr" placeholder="Displays as upload page bacground (1920*1080px)" value="{{{$_setting['upload_page_background'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.login_bg') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="login_page_background"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="login_page_background" dir="ltr" value="{{{$_setting['login_page_background'] or ''}}}" placeholder="Displays as login page bacground (1920*1080px)" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.days_graph') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group w-150">
                           <input type="number" class="spinner-input form-control" name="chart_day_count" value="{{{ $_setting['chart_day_count'] or 0 }}}" maxlength="3">
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="site_disable" value="0">
                              <input type="checkbox" name="site_disable" value="1" class="custom-switch-input" @if(!empty($_setting['site_disable']) && $_setting['site_disable']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.disable_website') }}}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="site_rtl" value="0">
                              <input type="checkbox" name="site_rtl" value="1" class="custom-switch-input" @if(!empty($_setting['site_rtl']) && $_setting['site_rtl']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">RTL Layout</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="site_preloader" value="0">
                              <input type="checkbox" name="site_preloader" value="1" class="custom-switch-input" @if(!empty($_setting['site_preloader']) && $_setting['site_preloader']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{!! trans('admin.preloader') !!}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="become_vendor" value="0">
                              <input type="checkbox" name="become_vendor" value="1" class="custom-switch-input" @if(!empty($_setting['become_vendor']) && $_setting['become_vendor']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{!! trans('admin.become_vendor') !!}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="duplicate_login" value="0">
                              <input type="checkbox" name="duplicate_login" value="1" class="custom-switch-input" @if(!empty($_setting['duplicate_login']) && $_setting['duplicate_login']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{!! trans('admin.avoid_double_login') !!}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>

               </form>
            </div>
            <div id="factor" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.approver') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" placeholder="Displays at the footer of financial balances" dir="ltr" name="factor_seconder" value="{{{ $_setting['factor_seconder'] or '' }}}" />
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.financial_manager_name') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" dir="ltr" name="factor_approver" placeholder="Displays at the footer of financial balances" value="{{{ $_setting['factor_approver'] or '' }}}" />
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.invoice_logo') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="factor_watermark"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="factor_watermark" dir="ltr" placeholder="Displays on invoce and balance header" value="{{{$_setting['factor_watermark'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p" ><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>

               </form>
            </div>
            <div id="gateway" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                  <div class="form-group">
                     <label class="col-md-2 control-label" for="inputDefault">Currency</label>
                     <div class="col-md-8">
                        <select name="currency" class="form-control">
                           <option value="USD" @if(get_option('currency', 'USD') == 'USD') selected @endif>United States Dollar</option>
                           <option value="EUR" @if(get_option('currency', 'USD') == 'EUR') selected @endif>Euro Member Countries</option>
                           <option value="AUD" @if(get_option('currency', 'USD') == 'AUD') selected @endif>Australia Dollar</option>
                           <option value="AED" @if(get_option('currency', 'USD') == 'AED') selected @endif>United Arab Emirates dirham</option>
                           <option value="KAD" @if(get_option('currency', 'USD') == 'KAD') selected @endif>KAD</option>
                           <option value="JPY" @if(get_option('currency', 'USD') == 'JPY') selected @endif>Japan Yen</option>
                           <option value="CNY" @if(get_option('currency', 'USD') == 'CNY') selected @endif>China Yuan Renminbi</option>
                           <option value="SAR" @if(get_option('currency', 'USD') == 'SAR') selected @endif>Saudi Arabia Riyal</option>
                           <option value="KRW" @if(get_option('currency', 'USD') == 'KRW') selected @endif>Korea (South) Won</option>
                           <option value="INR" @if(get_option('currency', 'USD') == 'INR') selected @endif>India Rupee</option>
                           <option value="RUB" @if(get_option('currency', 'USD') == 'RUB') selected @endif>Russia Ruble</option>
						   --------
						   <option value="Lek" @if(get_option('currency', 'USD') == 'Lek') selected @endif>Albania Lek</option>
						   <option value="AFN" @if(get_option('currency', 'USD') == 'AFN') selected @endif>Afghanistan Afghani</option>
						   <option value="ARS" @if(get_option('currency', 'USD') == 'ARS') selected @endif>Argentina Peso</option>
						   <option value="AWG" @if(get_option('currency', 'USD') == 'AWG') selected @endif>Aruba Guilder</option>
						   <option value="AUD" @if(get_option('currency', 'USD') == 'AUD') selected @endif>Australia Dollar</option>
						   <option value="AZN" @if(get_option('currency', 'USD') == 'AZN') selected @endif>Azerbaijan Manat</option>
						   <option value="BSD" @if(get_option('currency', 'USD') == 'BSD') selected @endif>Bahamas Dollar</option>
						   <option value="BBD" @if(get_option('currency', 'USD') == 'BBD') selected @endif>Barbados Dollar</option>
						   <option value="BYN" @if(get_option('currency', 'USD') == 'BYN') selected @endif>Belarus Ruble</option>
						   <option value="BZD" @if(get_option('currency', 'USD') == 'BZD') selected @endif>Belize Dollar</option>
						   <option value="BMD" @if(get_option('currency', 'USD') == 'BMD') selected @endif>Bermuda Dollar</option>
						   <option value="BOB" @if(get_option('currency', 'USD') == 'BOB') selected @endif>Bolivia Bolíviano</option>
						   <option value="BAM" @if(get_option('currency', 'USD') == 'BAM') selected @endif>Bosnia and Herzegovina Convertible Mark</option>
						   <option value="BWP" @if(get_option('currency', 'USD') == 'BWP') selected @endif>Botswana Pula</option>
						   <option value="BGN" @if(get_option('currency', 'USD') == 'BGN') selected @endif>Bulgaria Lev</option>
						   <option value="BRL" @if(get_option('currency', 'USD') == 'BRL') selected @endif>Brazil Real</option>
						   <option value="BND" @if(get_option('currency', 'USD') == 'BND') selected @endif>Brunei Darussalam Dollar</option>
						   <option value="KHR" @if(get_option('currency', 'USD') == 'KHR') selected @endif>Cambodia Riel</option>
						   <option value="CAD" @if(get_option('currency', 'USD') == 'CAD') selected @endif>Canada Dollar</option>
						   <option value="KYD" @if(get_option('currency', 'USD') == 'KYD') selected @endif>Cayman Islands Dollar</option>
						   <option value="CLP" @if(get_option('currency', 'USD') == 'CLP') selected @endif>Chile Peso</option>
						   <option value="COP" @if(get_option('currency', 'USD') == 'COP') selected @endif>Colombia Peso</option>
						   <option value="CRC" @if(get_option('currency', 'USD') == 'CRC') selected @endif>Costa Rica Colon</option>
						   <option value="HRK" @if(get_option('currency', 'USD') == 'HRK') selected @endif>Croatia Kuna</option>
						   <option value="CUP" @if(get_option('currency', 'USD') == 'CUP') selected @endif>Cuba Peso</option>
						   <option value="CZK" @if(get_option('currency', 'USD') == 'CZK') selected @endif>Czech Republic Koruna</option>
						   <option value="DKK" @if(get_option('currency', 'USD') == 'DKK') selected @endif>Denmark Krone</option>
						   <option value="DOP" @if(get_option('currency', 'USD') == 'DOP') selected @endif>Dominican Republic Peso</option>
						   <option value="XCD" @if(get_option('currency', 'USD') == 'XCD') selected @endif>East Caribbean Dollar</option>
						   <option value="EGP" @if(get_option('currency', 'USD') == 'EGP') selected @endif>Egypt Pound</option>
						   <option value="GTQ" @if(get_option('currency', 'USD') == 'GTQ') selected @endif>Guatemala Quetzal</option>
						   <option value="HKD" @if(get_option('currency', 'USD') == 'HKD') selected @endif>Hong Kong Dollar</option>
						   <option value="HUF" @if(get_option('currency', 'USD') == 'HUF') selected @endif>Hungary Forint</option>
						   <option value="IDR" @if(get_option('currency', 'USD') == 'IDR') selected @endif>Indonesia Rupiah</option>
						   <option value="IRR" @if(get_option('currency', 'USD') == 'IRR') selected @endif>Iran Rial</option>
						   <option value="ILS" @if(get_option('currency', 'USD') == 'ILS') selected @endif>Israel Shekel</option>
						   <option value="LBP" @if(get_option('currency', 'USD') == 'LBP') selected @endif>Lebanon Pound</option>
						   <option value="MYR" @if(get_option('currency', 'USD') == 'MYR') selected @endif>Malaysia Ringgit</option>
						   <option value="NGN" @if(get_option('currency', 'USD') == 'NGN') selected @endif>Nigeria Naira</option>
						   <option value="NOK" @if(get_option('currency', 'USD') == 'NOK') selected @endif>Norway Krone</option>
						   <option value="OMR" @if(get_option('currency', 'USD') == 'OMR') selected @endif>Oman Rial</option>
						   <option value="PKR" @if(get_option('currency', 'USD') == 'PKR') selected @endif>Pakistan Rupee</option>
						   <option value="PHP" @if(get_option('currency', 'USD') == 'PHP') selected @endif>Philippines Peso</option>
						   <option value="PLN" @if(get_option('currency', 'USD') == 'PLN') selected @endif>Poland Zloty</option>
						   <option value="RON" @if(get_option('currency', 'USD') == 'RON') selected @endif>Romania Leu</option>
						   <option value="ZAR" @if(get_option('currency', 'USD') == 'ZAR') selected @endif>South Africa Rand</option>
						   <option value="LKR" @if(get_option('currency', 'USD') == 'LKR') selected @endif>Sri Lanka Rupee</option>
						   <option value="SEK" @if(get_option('currency', 'USD') == 'SEK') selected @endif>Sweden Krona</option>
						   <option value="CHF" @if(get_option('currency', 'USD') == 'CHF') selected @endif>Switzerland Franc</option>
						   <option value="THB" @if(get_option('currency', 'USD') == 'THB') selected @endif>Thailand Baht</option>
						   <option value="TRY" @if(get_option('currency', 'USD') == 'TRY') selected @endif>Turkey Lira</option>
						   <option value="UAH" @if(get_option('currency', 'USD') == 'UAH') selected @endif>Ukraine Hryvnia</option>
						   <option value="GBP" @if(get_option('currency', 'USD') == 'GBP') selected @endif>United Kingdom Pound</option>
						   <option value="TWD" @if(get_option('currency', 'USD') == 'TWD') selected @endif>Taiwan New Dollar</option>
						   <option value="VND" @if(get_option('currency', 'USD') == 'VND') selected @endif>Viet Nam Dong</option>
						   <option value="UZS" @if(get_option('currency', 'USD') == 'UZS') selected @endif>Uzbekistan Som</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="gateway_paypal" value="0">
                              <input type="checkbox" name="gateway_paypal" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paypal']) && $_setting['gateway_paypal']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">Paypal</label>
                           </label>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="gateway_paystack" value="0">
                              <input type="checkbox" name="gateway_paystack" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paystack']) && $_setting['gateway_paystack']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">Paystack</label>
                           </label>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="gateway_paytm" value="0">
                              <input type="checkbox" name="gateway_paytm" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paytm']) && $_setting['gateway_paytm']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">Paytm</label>
                           </label>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="gateway_payu" value="0">
                              <input type="checkbox" name="gateway_payu" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_payu']) && $_setting['gateway_payu']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">Payu</label>
                           </label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>
               </form>
            </div>
            <div id="withdraw" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.open_courses_comm') }}}</label>
                     <div class="col-md-3">
                           <input type="number" class="spinner-input form-control" name="site_income" value="{{{ $_setting['site_income'] or 0 }}}" maxlength="3">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.exclusive_courses_comm') }}}</label>
                     <div class="col-md-3">
                           <input type="number" class="spinner-input form-control" name="site_income_private" value="{{{ $_setting['site_income_private'] or 0 }}}" maxlength="3">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.min_withdrawal_amount') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <input type="text" name="site_withdraw_price" value="{!! get_option('site_withdraw_price',0) !!}" class="form-control text-center numtostr">
                           <span class="input-group-append click-for-upload cu-p">
                              <span class="input-group-text">{!! currencySign() !!}</span>
                           </span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>

               </form>
            </div>
            <div id="popup" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="site_popup" value="0">
                              <input type="checkbox" name="site_popup" value="1" class="custom-switch-input" @if(!empty($_setting['site_popup']) && $_setting['site_popup']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.popup') }}}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.popup_image') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="popup_image"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="popup_image" dir="ltr" value="{{{$_setting['popup_image'] or ''}}}" class="form-control">
                           <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.popup_link') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" dir="ltr" name="popup_url" value="{{{ $_setting['popup_url'] or '' }}}" />
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>

               </form>
            </div>
            <div id="videoAds" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">

                  <div class="form-group">
                     <div class="col-md-12">
                        <div class="custom-switches-stacked">
                           <label class="custom-switch">
                              <input type="hidden" name="site_videoads" value="0">
                              <input type="checkbox" name="site_videoads" value="1" class="custom-switch-input" @if(!empty($_setting['site_videoads']) && $_setting['site_videoads']==1) checked @endif />
                              <span class="custom-switch-indicator"></span>
                              <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.enable') }}}</label>
                           </label>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">Xml {{{ trans('admin.video_file') }}} Url</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <input type="text" placeholder="https://" name="site_videoads_source" dir="ltr" value="{{{$_setting['site_videoads_source'] or ''}}}" class="form-control">
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{!! trans('admin.text') !!}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <input type="text" name="site_videoads_title" dir="ltr" value="{{{$_setting['site_videoads_title'] or ''}}}" class="form-control">
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">Roll Type</label>
                     <div class="col-md-6">
                        <select name="site_videoads_roll_type" class="form-control">
                           <option value="preRoll" @if(get_option('site_videoads_roll_type','') == 'preRoll') selected @endif>PreRoll</option>
                           <option value="midRoll" @if(get_option('site_videoads_roll_type','') == 'midRoll') selected @endif>MidRoll</option>
                           <option value="postRoll" @if(get_option('site_videoads_roll_type','') == 'postRoll') selected @endif>PostRoll</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.minimum_time_to_skip') }}}</label>
                     <div class="col-md-3">
                        <div class="input-group">
                           <input type="number" class="spinner-input form-control text-center" name="site_videoads_time" value="{{{ $_setting['site_videoads_time'] or 0 }}}" maxlength="3">
                           <span class="input-group-append"><label class="input-group-text">Seconds</label></span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>

               </form>
            </div>
            <div id="mainSlide" class="tab-pane">
               <form method="post" action="/admin/setting/store" class="form-horizontal form-bordered">
                  <div class="form-group">
                     <label class="col-md-3 control-label">{{{ trans('admin.hero_bg') }}}</label>
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-prepend cu-p view-selected" data-toggle="modal" data-target="#ImageModal" data-whatever="main_page_slide"><span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span></span>
                           <input type="text" name="main_page_slide" dir="ltr" placeholder="Displays as homepage header background (1920*500px)" value="{{{$_setting['main_page_slide'] or ''}}}" class="form-control">
                           <span class="input-group-append  cu-p click-for-upload"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" name="main_page_slide_title" value="{{{ $_setting['main_page_slide_title'] or '' }}}" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.description') }}}</label>
                     <div class="col-md-6">
                        <textarea rows="5" class="form-control text-center" name="main_page_slide_text">{{{ $_setting['main_page_slide_text'] or '' }}}</textarea>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.first_button') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" name="main_page_slide_btn_1_text" value="{{{ $_setting['main_page_slide_btn_1_text'] or '' }}}" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.second_button') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" name="main_page_slide_btn_2_text" value="{{{ $_setting['main_page_slide_btn_2_text'] or '' }}}" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.first_button_link') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" name="main_page_slide_btn_1_url" value="{{{ $_setting['main_page_slide_btn_1_url'] or '' }}}" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.secound_button_link') }}}</label>
                     <div class="col-md-6">
                        <input type="text" class="form-control text-center" name="main_page_slide_btn_2_url" value="{{{ $_setting['main_page_slide_btn_2_url'] or '' }}}" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
@endsection
