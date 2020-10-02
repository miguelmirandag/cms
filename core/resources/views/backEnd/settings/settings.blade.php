@extends('backEnd.layout')
@section('headerInclude')
    <link rel="stylesheet"
          href="{{ URL::to('backEnd/libs/jquery/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}"
          type="text/css"/>
@endsection
@section('content')
    <div class="padding">
        <div class="row-col">
            <div class="col-sm-3 col-lg-2">
                <div class="p-y">
                    <div class="nav-active-border left b-primary">
                        <ul class="nav nav-sm">
                            <li class="nav-item">
                                <a class="nav-link block {{ ( Session::get('active_tab') == 'infoTab' || Session::get('active_tab') =="") ? 'active' : '' }}"
                                   href data-toggle="tab" data-target="#tab-1"
                                   onclick="document.getElementById('active_tab').value='infoTab'"><i
                                        class="material-icons">&#xe30c;</i>
                                    &nbsp; {!!  __('backend.siteInfoSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}"
                                   href
                                   data-toggle="tab" data-target="#tab-2"
                                   onclick="document.getElementById('active_tab').value='contactsTab'"><i
                                        class="material-icons">&#xe0ba;</i>
                                    &nbsp; {!!  __('backend.siteContactsSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'socialTab') ? 'active' : '' }}"
                                   href
                                   data-toggle="tab" data-target="#tab-3"
                                   onclick="document.getElementById('active_tab').value='socialTab'"><i
                                        class="material-icons">&#xe80d;</i>
                                    &nbsp; {!!  __('backend.siteSocialSettings') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'styleTab') ? 'active' : '' }}"
                                   href
                                   data-toggle="tab" data-target="#tab-5"
                                   onclick="document.getElementById('active_tab').value='styleTab'"><i
                                        class="material-icons">&#xe41d;</i>
                                    &nbsp; {!!  __('backend.styleSettings') !!}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'emailTab') ? 'active' : '' }}"
                                   href
                                   data-toggle="tab" data-target="#tab-6"
                                   onclick="document.getElementById('active_tab').value='emailTab'"><i
                                        class="material-icons">&#xe0be;</i>
                                    &nbsp; {!!  __('backend.emailNotifications') !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link block {{  ( Session::get('active_tab') == 'statusTab') ? 'active' : '' }}"
                                   href
                                   data-toggle="tab" data-target="#tab-4"
                                   onclick="document.getElementById('active_tab').value='statusTab'"><i
                                        class="material-icons">&#xe8c6;</i>
                                    &nbsp; {!!  __('backend.siteStatusSettings') !!}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-7 col-lg-10 light lt">

                {{Form::open(['route'=>['settingsUpdateSiteInfo'],'method'=>'POST', 'files' => true ])}}
                <input type="hidden" id="active_tab" name="active_tab" value="{{ Session::get('active_tab') }}"/>
                <div class="tab-content pos-rlt">
                    <button type="submit" class="btn btn-info m-a pull-right">{{ __('backend.update') }}</button>
                    <div
                        class="tab-pane {{ ( Session::get('active_tab') == 'infoTab' || Session::get('active_tab') =="") ? 'active' : '' }}"
                        id="tab-1">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe30c;</i>
                                &nbsp; {!!  __('backend.siteInfoSettings') !!}</h5></div>
                        <div class="p-a-md col-md-12">
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group">
                                    <label>{!!  __('backend.websiteTitle') !!}
                                    </label> {!! @Helper::languageName($ActiveLanguage) !!}
                                    {!! Form::text('site_title_'.@$ActiveLanguage->code,$Setting->{'site_title_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction)) !!}
                                </div>
                            @endforeach
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group">
                                    <label>{!!  __('backend.metaDescription') !!}
                                    </label> {!! @Helper::languageName($ActiveLanguage) !!}
                                    {!! Form::textarea('site_desc_'.@$ActiveLanguage->code,$Setting->{'site_desc_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction,'rows'=>'2')) !!}
                                </div>
                            @endforeach
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group">
                                    <label>{!!  __('backend.metaKeywords') !!}
                                    </label> {!! @Helper::languageName($ActiveLanguage) !!}
                                    {!! Form::textarea('site_keywords_'.@$ActiveLanguage->code,$Setting->{'site_keywords_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction,'rows'=>'2')) !!}
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label>{!!  __('backend.websiteUrl') !!}</label>
                                {!! Form::text('site_url',$Setting->site_url, array('placeholder' => 'http//:www.sitename.com/','class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane {{  ( Session::get('active_tab') == 'contactsTab') ? 'active' : '' }}"
                         id="tab-2">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe0ba;</i>
                                &nbsp; {!!  __('backend.siteContactsSettings') !!}</h5></div>
                        <div class="p-a-md col-md-12">
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group">
                                    <label>{!!  __('backend.contactAddress') !!}
                                    </label> {!! @Helper::languageName($ActiveLanguage) !!}
                                    {!! Form::text('contact_t1_'.@$ActiveLanguage->code,$Setting->{'contact_t1_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction)) !!}
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label>{!!  __('backend.contactPhone') !!}</label>
                                {!! Form::text('contact_t3',$Setting->contact_t3, array('placeholder' => __('backend.contactPhone'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                            <div class="form-group">
                                <label>{!!  __('backend.contactFax') !!}</label>
                                {!! Form::text('contact_t4',$Setting->contact_t4, array('placeholder' => __('backend.contactFax'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                            <div class="form-group">
                                <label>{!!  __('backend.contactMobile') !!}</label>
                                {!! Form::text('contact_t5',$Setting->contact_t5, array('placeholder' => __('backend.contactMobile'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                            <div class="form-group">
                                <label>{!!  __('backend.contactEmail') !!}</label>
                                {!! Form::text('contact_t6',$Setting->contact_t6, array('placeholder' => __('backend.contactEmail'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="form-group">
                                    <label>{!!  __('backend.worksTime') !!}
                                    </label> {!! @Helper::languageName($ActiveLanguage) !!}
                                    {!! Form::text('contact_t7_'.@$ActiveLanguage->code,$Setting->{'contact_t7_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction)) !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane {{  ( Session::get('active_tab') == 'socialTab') ? 'active' : '' }}"
                         id="tab-3">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe80d;</i>
                                &nbsp; {!!  __('backend.siteSocialSettings') !!}</h5></div>
                        <div class="p-a-md col-md-12">

                            <div class="form-group">
                                <label><i class="fa fa-facebook"></i> &nbsp; {!!  __('backend.facebook') !!}</label>
                                {!! Form::text('social_link1',$Setting->social_link1, array('placeholder' => __('backend.facebook'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-twitter"></i> &nbsp; {!!  __('backend.twitter') !!}</label>
                                {!! Form::text('social_link2',$Setting->social_link2, array('placeholder' => __('backend.twitter'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-google-plus"></i> &nbsp; {!!  __('backend.google') !!}
                                </label>
                                {!! Form::text('social_link3',$Setting->social_link3, array('placeholder' => __('backend.google'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-linkedin"></i> &nbsp; {!!  __('backend.linkedin') !!}</label>
                                {!! Form::text('social_link4',$Setting->social_link4, array('placeholder' => __('backend.linkedin'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-youtube-play"></i> &nbsp; {!!  __('backend.youtube') !!}
                                </label>
                                {!! Form::text('social_link5',$Setting->social_link5, array('placeholder' => __('backend.youtube'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-instagram"></i> &nbsp; {!!  __('backend.instagram') !!}
                                </label>
                                {!! Form::text('social_link6',$Setting->social_link6, array('placeholder' => __('backend.instagram'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-pinterest"></i> &nbsp; {!!  __('backend.pinterest') !!}
                                </label>
                                {!! Form::text('social_link7',$Setting->social_link7, array('placeholder' => __('backend.pinterest'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-tumblr"></i> &nbsp; {!!  __('backend.tumblr') !!}</label>
                                {!! Form::text('social_link8',$Setting->social_link8, array('placeholder' => __('backend.tumblr'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-snapchat"></i> &nbsp; {!!  __('backend.snapchat') !!}</label>
                                {!! Form::text('social_link9',$Setting->social_link9, array('placeholder' => __('backend.snapchat'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                            <div class="form-group">
                                <label><i class="fa fa-whatsapp"></i> &nbsp; {!!  __('backend.whatapp') !!}</label>
                                {!! Form::text('social_link10',$Setting->social_link10, array('placeholder' => __('backend.whatapp'),'class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane {{  ( Session::get('active_tab') == 'statusTab') ? 'active' : '' }}"
                         id="tab-4">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe8c6;</i>
                                &nbsp; {!!  __('backend.siteStatusSettings') !!}</h5></div>
                        <div class="p-a-md col-md-12">
                            <div class="form-group">
                                <label>{{ __('backend.siteStatusSettings') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('site_status','1',$Setting->site_status ? true : false , array('id' => 'site_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('site_status','0',$Setting->site_status ? false : true , array('id' => 'site_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group"
                                 id="close_msg_div" {!!   ($Setting->site_status) ? "style='display:none'":"" !!}>
                                <label>{!!  __('backend.siteStatusSettingsMsg') !!} </label>
                                {!! Form::textarea('close_msg',$Setting->close_msg, array('placeholder' => __('backend.siteStatusSettingsMsg'),'class' => 'form-control','rows'=>'4')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {{  ( Session::get('active_tab') == 'emailTab') ? 'active' : '' }}"
                         id="tab-6">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe0be;</i>
                                &nbsp; {!!  __('backend.emailNotifications') !!}</h5></div>
                        <div class="p-a-md col-md-12">
                            <div class="form-group">
                                <label>{!!  __('backend.websiteNotificationEmail') !!}</label>
                                {!! Form::text('site_webmails',$Setting->site_webmails, array('placeholder' => 'email@sitename.com','class' => 'form-control', 'dir'=>'ltr')) !!}
                            </div>
                            <div class="form-group">
                                <label>{{ __('backend.websiteNotificationEmail1') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_messages_status','1',$Setting->notify_messages_status ? true : false , array('id' => 'seo_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_messages_status','0',$Setting->notify_messages_status ? false : true , array('id' => 'seo_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('backend.websiteNotificationEmail2') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_comments_status','1',$Setting->notify_comments_status ? true : false , array('id' => 'seo_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_comments_status','0',$Setting->notify_comments_status ? false : true , array('id' => 'seo_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('backend.websiteNotificationEmail3') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_orders_status','1',$Setting->notify_orders_status ? true : false , array('id' => 'seo_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('notify_orders_status','0',$Setting->notify_orders_status ? false : true , array('id' => 'seo_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane {{  ( Session::get('active_tab') == 'styleTab') ? 'active' : '' }}" id="tab-5">
                        <div class="p-a-md"><h5><i class="material-icons">&#xe41d;</i>
                                &nbsp; {!!  __('backend.styleSettings') !!}</h5></div>
                        <div class="p-a-md col-md-12">

                            <div class="form-group row">
                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                    <div class="col-sm-6 m-b-2">
                                        <label>{!!  __('backend.siteLogo') !!}</label> {!! @Helper::languageName($ActiveLanguage) !!}
                                        @if($Setting->{'style_logo_'.@$ActiveLanguage->code}!="")
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-12 box p-a-xs text-center">
                                                        <a target="_blank"
                                                           href="{{ URL::to('uploads/settings/'.$Setting->{'style_logo_'.@$ActiveLanguage->code}) }}"><img
                                                                src="{{ URL::to('uploads/settings/'.$Setting->{'style_logo_'.@$ActiveLanguage->code}) }}"
                                                                class="img-responsive" id="style_logo_{{ @$ActiveLanguage->code }}_prv"
                                                                style="width: auto;max-width: 260px;max-height: 60px">
                                                            <br>
                                                            <small>{{ $Setting->{'style_logo_'.@$ActiveLanguage->code} }}</small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-12 box p-a-xs text-center">
                                                        <img
                                                            src="{{ URL::to('uploads/settings/nologo.png') }}"
                                                            class="img-responsive" id="style_logo_{{ @$ActiveLanguage->code }}_prv"
                                                            style="width: auto;max-width: 260px;max-height: 60px">
                                                        <br>
                                                        <small>nologo.png</small>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        {!! Form::file('style_logo_'.@$ActiveLanguage->code, array('class' => 'form-control','id'=>'style_logo_'.@$ActiveLanguage->code,'accept'=>'image/*')) !!}
                                        <small>
                                            <i class="material-icons">&#xe8fd;</i>( 260x60 px ) -
                                            {!!  __('backend.imagesTypes') !!}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="style_fav">{!!  __('backend.favicon') !!}</label>
                                    @if($Setting->style_fav!="")
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12 box p-a-xs text-center">
                                                    <a target="_blank"
                                                       href="{{ URL::to('uploads/settings/'.$Setting->style_fav) }}"><img
                                                            src="{{ URL::to('uploads/settings/'.$Setting->style_fav) }}"
                                                            class="img-responsive" id="style_fav_prv"
                                                            style="max-width: 60px;height: 60px">
                                                        <br>
                                                        <small>{{ $Setting->style_fav }}</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12 box p-a-xs text-center">
                                                    <a target="_blank"
                                                       href="{{ URL::to('uploads/settings/nofav.png') }}"><img
                                                            src="{{ URL::to('uploads/settings/nofav.png') }}"
                                                            class="img-responsive" id="style_fav_prv"
                                                            style="max-width: 60px;height: 60px">
                                                        <br>
                                                        <small>nofav.png</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {!! Form::file('style_fav', array('class' => 'form-control','id'=>'style_fav','accept'=>'image/*')) !!}
                                    <small>
                                        <i class="material-icons">&#xe8fd;</i> ( 32x32 px ) -
                                        {!!  __('backend.imagesTypes') !!}
                                    </small>
                                </div>
                                <div class="col-sm-6">
                                    <label for="style_apple">{!!  __('backend.appleIcon') !!}</label>
                                    @if($Setting->style_apple!="")
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12 box p-a-xs text-center">
                                                    <a target="_blank"
                                                       href="{{ URL::to('uploads/settings/'.$Setting->style_apple) }}"><img
                                                            src="{{ URL::to('uploads/settings/'.$Setting->style_apple) }}"
                                                            class="img-responsive" id="style_apple_prv"
                                                            style="width: 60px;height: 60px">
                                                        <br>
                                                        <small>{{ $Setting->style_apple }}</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12 box p-a-xs text-center">
                                                    <a target="_blank"
                                                       href="{{ URL::to('uploads/settings/nofav.png') }}"><img
                                                            src="{{ URL::to('uploads/settings/nofav.png') }}"
                                                            class="img-responsive" id="style_apple_prv"
                                                            style="max-width: 60px;height: 60px">
                                                        <br>
                                                        <small>nofav.png</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {!! Form::file('style_apple', array('class' => 'form-control','id'=>'style_apple','accept'=>'image/*')) !!}
                                    <small>
                                        <i class="material-icons">&#xe8fd;</i> ( 180x180 px ) -
                                        {!!  __('backend.imagesTypes') !!}
                                    </small>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>{!!  __('backend.styleColor1') !!}</label>

                                    <div>
                                        <div id="cp1" class="input-group colorpicker-component">
                                            {!! Form::text('style_color1',$Setting->style_color1, array('placeholder' => '','class' => 'form-control','id'=>'style_color1', 'dir'=>'ltr')) !!}
                                            <span class="input-group-addon" id="cpbg"><i></i></span>
                                        </div>
                                    </div>
                                    <small><a href="javascript:null"
                                              onclick="update_restcolor()">{!!  __('backend.restoreDefault') !!}</a>
                                    </small>
                                </div>

                                <div class="col-sm-4">
                                    <label>{!!  __('backend.styleColor2') !!}</label>

                                    <div>
                                        <div id="cp2" class="input-group colorpicker-component">
                                            {!! Form::text('style_color2',$Setting->style_color2, array('placeholder' => '','class' => 'form-control','id'=>'style_color2', 'dir'=>'ltr')) !!}
                                            <span class="input-group-addon" id="cpbg2"><i></i></span>
                                        </div>
                                    </div>
                                    <small><a href="javascript:null"
                                              onclick="update_restcolor2()">{!!  __('backend.restoreDefault') !!}</a>
                                    </small>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>{{ __('backend.layoutMode') }} : </label>
                                    <div class="radio">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('style_type','0',$Setting->style_type ? false : true , array('id' => 'style_type1','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.wide') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('style_type','1',$Setting->style_type ? true : false , array('id' => 'style_type2','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.boxed') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-8"
                                     id="bgtyps" {!!   (!$Setting->style_type) ? "style='display:none'":"" !!}>
                                    <label>{{ __('backend.backgroundStyle') }} : </label>
                                    <div class="radio">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('style_bg_type','0',($Setting->style_bg_type==0) ? true : false , array('id' => 'style_bg_type1','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.colorBackground') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('style_bg_type','1',($Setting->style_bg_type==1) ? true : false , array('id' => 'style_bg_type2','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.patternsBackground') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('style_bg_type','2',($Setting->style_bg_type==2) ? true : false , array('id' => 'style_bg_type3','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.imageBackground') }}
                                        </label>
                                    </div>
                                    <div class="row"
                                         id="bgtclr" {!!   ($Setting->style_bg_type!=0) ? "style='display:none'":"" !!}>
                                        <div class="col-sm-11">
                                            <div id="cp3" class="input-group colorpicker-component">
                                                {!! Form::text('style_bg_color',$Setting->style_bg_color, array('placeholder' => '','class' => 'form-control','id'=>'style_bg_color', 'dir'=>'ltr')) !!}
                                                <span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"
                                         id="bgtptr" {!!   ($Setting->style_bg_type!=1) ? "style='display:none'":"" !!}>
                                        <div>
                                            @for($i=1;$i<=24;$i++)
                                                <?php
                                                $img_name = "p" . $i . ".png";
                                                ?>
                                                <div class="col-sm-3">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('style_bg_pattern',$img_name,($Setting->style_bg_pattern==$img_name) ? true : false , array('id' => 'style_bg_pattern'.$i,'class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        <img src="{{ URL::to('uploads/pattern/'.$img_name) }}"
                                                             style="width: 40px;height: 40px;border: 2px solid #fff"
                                                             alt="">
                                                    </label>
                                                </div>
                                            @endfor

                                        </div>
                                    </div>

                                    <div class="row"
                                         id="bgtimg" {!!   ($Setting->style_bg_type!=2) ? "style='display:none'":"" !!}>
                                        <div>
                                            @if($Setting->style_bg_image!="")
                                                <div>
                                                    <div>
                                                        <div class="col-sm-12 box p-a-xs text-center">
                                                            <a target="_blank"
                                                               href="{{ URL::to('uploads/settings/'.$Setting->style_bg_image) }}"><img
                                                                    src="{{ URL::to('uploads/settings/'.$Setting->style_bg_image) }}"
                                                                    class="img-responsive"
                                                                    style="max-height: 200px;width: auto">
                                                                <br>
                                                                <small>{{ $Setting->style_bg_image }}</small>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            {!! Form::file('style_bg_image', array('class' => 'form-control','id'=>'style_bg_image','accept'=>'image/*')) !!}
                                            <small>
                                                <i class="material-icons">&#xe8fd;</i>( 260x60 px ) -
                                                {!!  __('backend.imagesTypes') !!}
                                            </small>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>{{ __('backend.fixedHeader') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_header','1',$Setting->style_header ? true : false , array('id' => 'style_header1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_header','0',$Setting->style_header ? false : true , array('id' => 'style_header2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>{{ __('backend.footerStyle') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_footer','1',($Setting->style_footer ==1) ? true : false , array('id' => 'style_footer1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.footerStyle') }} #1
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_footer','2',($Setting->style_footer ==2) ? true : false , array('id' => 'style_footer2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.footerStyle') }} #2
                                    </label>
                                </div>

                                <label>{{ __('backend.footerStyleBg') }} : </label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        @if($Setting->style_footer_bg!="")
                                            <div>
                                                <div>
                                                    <div id="footer_bg" class="col-sm-8 box p-a-xs">
                                                        <a target="_blank"
                                                           href="{{ URL::to('uploads/settings/'.$Setting->style_footer_bg) }}"><img
                                                                src="{{ URL::to('uploads/settings/'.$Setting->style_footer_bg) }}"
                                                                class="img-responsive">
                                                            {{ $Setting->style_footer_bg }}
                                                        </a>
                                                        <br>
                                                        <a onclick="document.getElementById('footer_bg').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                                           class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                                    </div>
                                                    <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                                        <a onclick="document.getElementById('footer_bg').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                                            <i class="material-icons">
                                                                &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                                    </div>

                                                    {!! Form::hidden('photo_delete','0', array('id'=>'photo_delete')) !!}
                                                </div>
                                            </div>

                                        @endif
                                        {!! Form::file('style_footer_bg', array('class' => 'form-control','id'=>'style_footer_bg','accept'=>'image/*')) !!}
                                        <small>
                                            <i class="material-icons">&#xe8fd;</i>( 260x60 px ) -
                                            {!!  __('backend.imagesTypes') !!}
                                        </small>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="form-group">
                                <label>{{ __('backend.newsletterSubscribe') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_subscribe','1',$Setting->style_subscribe ? true : false , array('id' => 'style_subscribe1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_subscribe','0',$Setting->style_subscribe ? false : true , array('id' => 'style_subscribe2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>{{ __('backend.preLoad') }} : </label>
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_preload','1',$Setting->style_preload ? true : false , array('id' => 'style_preload1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('style_preload','0',$Setting->style_preload ? false : true , array('id' => 'style_preload2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>


@endsection
@section('footerInclude')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#site_status1").click(function () {
                $("#close_msg_div").css("display", "none");
            });
            $("#site_status2").click(function () {
                $("#close_msg_div").css("display", "block");
            });

            $("#style_type1").click(function () {
                $("#bgtyps").css("display", "none");
            });
            $("#style_type2").click(function () {
                $("#bgtyps").css("display", "inline-block");
            });

            $("#style_bg_type1").click(function () {
                $("#bgtimg").css("display", "none");
                $("#bgtptr").css("display", "none");
                $("#bgtclr").css("display", "inline-block");
            });
            $("#style_bg_type2").click(function () {
                $("#bgtimg").css("display", "none");
                $("#bgtclr").css("display", "none");
                $("#bgtptr").css("display", "inline-block");
            });
            $("#style_bg_type3").click(function () {
                $("#bgtptr").css("display", "none");
                $("#bgtclr").css("display", "none");
                $("#bgtimg").css("display", "inline-block");
            });
        });

    </script>
    <script src="{{ URL::to('backEnd/libs/jquery/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(function () {
            $('#cp1').colorpicker({
                colorSelectors: {
                    'black': '#000000',
                    'white': '#ffffff',
                    'red': '#FF0000',
                    'default': '#777777',
                    'primary': '#337ab7',
                    'success': '#5cb85c',
                    'info': '#5bc0de',
                    'warning': '#f0ad4e',
                    'danger': '#d9534f'
                }
            });
            $('#cp2').colorpicker({
                colorSelectors: {
                    'black': '#000000',
                    'white': '#ffffff',
                    'red': '#FF0000',
                    'default': '#777777',
                    'primary': '#337ab7',
                    'success': '#5cb85c',
                    'info': '#5bc0de',
                    'warning': '#f0ad4e',
                    'danger': '#d9534f'
                }
            });
            $('#cp3').colorpicker({
                colorSelectors: {
                    'black': '#000000',
                    'white': '#ffffff',
                    'red': '#FF0000',
                    'default': '#777777',
                    'primary': '#337ab7',
                    'success': '#5cb85c',
                    'info': '#5bc0de',
                    'warning': '#f0ad4e',
                    'danger': '#d9534f'
                }
            });
        });

        function update_restcolor() {
            document.getElementById("style_color1").value = '#0cbaa4';
            $("#cpbg i").css("background-color", "#0cbaa4");
        }

        function update_restcolor2() {
            document.getElementById("style_color2").value = '#2e3e4e';
            $("#cpbg2 i").css("background-color", "#2e3e4e");
        }
    </script>
    <script type="text/javascript">
        function readURL(input, prv) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + prv).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        @foreach(Helper::languagesList() as $ActiveLanguage)
        $("#style_logo_{{ @$ActiveLanguage->code }}").change(function () {
            readURL(this, "style_logo_{{ @$ActiveLanguage->code }}_prv");
        });
        @endforeach

    </script>
@endsection

