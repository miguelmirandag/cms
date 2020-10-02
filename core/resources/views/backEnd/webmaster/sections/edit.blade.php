@extends('backEnd.layout')

@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.sectionEdit') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    {{ __('backend.webmasterTools') }} /
                    <a href="">{{ __('backend.siteSectionsSettings') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("WebmasterSections")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php
        $tab_1 = "active";
        $tab_2 = "";
        $tab_3 = "";
        if (Session::has('activeTab')) {
            if (Session::get('activeTab') == "fields") {
                $tab_1 = "";
                $tab_2 = "active";
                $tab_3 = "";
            }
            if (Session::get('activeTab') == "seo") {
                $tab_1 = "";
                $tab_2 = "";
                $tab_3 = "active";
            }
        }
        ?>
        <div class="box nav-active-border b-info">
            <ul class="nav nav-md">
                <li class="nav-item inline">
                    <a class="nav-link {{ $tab_1 }}" href data-toggle="tab" data-target="#tab_details">
                        <span class="text-md"><i class="material-icons">
                                &#xe31e;</i> {{ __('backend.topicTabSection') }}</span>
                    </a>
                </li>
                <li class="nav-item inline">
                    <a class="nav-link  {{ $tab_2 }}" href data-toggle="tab" data-target="#tab_custom">
                    <span class="text-md"><i class="material-icons">
                            &#xe30d;</i> {{ __('backend.customFields') }}</span>
                    </a>
                </li>
                @if(Helper::GeneralWebmasterSettings("seo_status"))
                    <li class="nav-item inline">
                        <a class="nav-link  {{ $tab_3 }}" href data-toggle="tab" data-target="#tab_seo">
                    <span class="text-md"><i class="material-icons">
                            &#xe8e5;</i> {{ __('backend.seoTabTitle') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content clear b-t">
                <div class="tab-pane  {{ $tab_1 }}" id="tab_details">
                    <div class="box-body">
                        {{Form::open(['route'=>['WebmasterSectionsUpdate',$WebmasterSections->id],'method'=>'POST'])}}

                        @foreach(Helper::languagesList() as $ActiveLanguage)
                            <div class="form-group row">
                                <label
                                    class="col-sm-2 form-control-label">{!!  __('backend.sectionName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-10">
                                    {!! Form::text('title_'.@$ActiveLanguage->code,$WebmasterSections->{'title_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control','required'=>'', 'dir'=>@$ActiveLanguage->direction)) !!}
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group row">
                            <label for="type"
                                   class="col-sm-2 form-control-label">{!!  __('backend.sectionType') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('type','0',($WebmasterSections->type==0) ? true : false, array('id' => 'site_status1','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.typeTextPages') }}
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('type','1',($WebmasterSections->type==1) ? true : false, array('id' => 'site_status2','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.typePhotos') }}
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('type','2',($WebmasterSections->type==2) ? true : false, array('id' => 'site_status3','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.typeVideos') }}
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('type','3',($WebmasterSections->type==3) ? true : false, array('id' => 'site_status4','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.typeSounds') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sections_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.hasCategories') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('sections_status','0',($WebmasterSections->sections_status==0) ? true : false, array('id' => 'sections_status1','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.withoutCategories') }}
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('sections_status','1',($WebmasterSections->sections_status==1) ? true : false, array('id' => 'sections_status2','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.mainCategoriesOnly') }}
                                        </label>
                                    </div>
                                    <div style="margin-bottom: 5px;">
                                        <label class="ui-check ui-check-md">
                                            {!! Form::radio('sections_status','2',($WebmasterSections->sections_status==2) ? true : false, array('id' => 'sections_status3','class'=>'has-value')) !!}
                                            <i class="dark-white"></i>
                                            {{ __('backend.mainAndSubCategories') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <br/>
                            <label><h5><i class="material-icons">&#xe1db;</i> {{ __('backend.optionalFields') }}
                                </h5></label>
                            <hr class="m-a-0">
                        </div>
                        <div class="form-group row">
                            <label for="date_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.dateField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('date_status','1',($WebmasterSections->date_status==1) ? true : false, array('id' => 'date_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('date_status','0',($WebmasterSections->date_status==0) ? true : false, array('id' => 'date_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expire_date_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.expireDateField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('expire_date_status','1',($WebmasterSections->expire_date_status==1) ? true : false, array('id' => 'expire_date_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('expire_date_status','0',($WebmasterSections->expire_date_status==0) ? true : false, array('id' => 'expire_date_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="longtext_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.longTextField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('longtext_status','1',($WebmasterSections->longtext_status==1) ? true : false, array('id' => 'longtext_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('longtext_status','0',($WebmasterSections->longtext_status==0) ? true : false, array('id' => 'longtext_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editor_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.allowEditor') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('editor_status','1',($WebmasterSections->editor_status==1) ? true : false, array('id' => 'editor_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('editor_status','0',($WebmasterSections->editor_status==0) ? true : false, array('id' => 'editor_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="attach_file_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.attachFileField') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('attach_file_status','1',($WebmasterSections->attach_file_status==1) ? true : false, array('id' => 'attach_file_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('attach_file_status','0',($WebmasterSections->attach_file_status==0) ? true : false, array('id' => 'attach_file_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="section_icon_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.sectionIconPicker') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('section_icon_status','1',($WebmasterSections->section_icon_status==1) ? true : false, array('id' => 'section_icon_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('section_icon_status','0',($WebmasterSections->section_icon_status==0) ? true : false, array('id' => 'section_icon_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="icon_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.topicsIconPicker') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('icon_status','1',($WebmasterSections->icon_status==1) ? true : false, array('id' => 'icon_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('icon_status','0',($WebmasterSections->icon_status==0) ? true : false, array('id' => 'icon_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <br/>
                            <label><h5><i class="material-icons">&#xe8d8;</i> {{ __('backend.additionalTabs') }}
                                </h5></label>
                            <hr class="m-a-0">
                        </div>
                        <div class="form-group row">
                            <label for="multi_images_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.additionalImages') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('multi_images_status','1',($WebmasterSections->multi_images_status==1) ? true : false, array('id' => 'multi_images_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('multi_images_status','0',($WebmasterSections->multi_images_status==0) ? true : false, array('id' => 'multi_images_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="extra_attach_file_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.additionalFiles') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('extra_attach_file_status','1',($WebmasterSections->extra_attach_file_status==1) ? true : false, array('id' => 'extra_attach_file_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('extra_attach_file_status','0',($WebmasterSections->extra_attach_file_status==0) ? true : false, array('id' => 'extra_attach_file_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="maps_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.attachGoogleMaps') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('maps_status','1',($WebmasterSections->maps_status==1) ? true : false, array('id' => 'maps_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('maps_status','0',($WebmasterSections->maps_status==0) ? true : false, array('id' => 'maps_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="order_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.attachOrderForm') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('order_status','1',($WebmasterSections->order_status==1) ? true : false, array('id' => 'order_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('order_status','0',($WebmasterSections->order_status==0) ? true : false, array('id' => 'order_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comments_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.reviewsAvailable') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('comments_status','1',($WebmasterSections->comments_status==1) ? true : false, array('id' => 'comments_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('comments_status','0',($WebmasterSections->comments_status==0) ? true : false, array('id' => 'comments_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="related_status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.relatedTopics') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('related_status','1',($WebmasterSections->related_status==1) ? true : false, array('id' => 'related_status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.yes') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('related_status','0',($WebmasterSections->related_status==0) ? true : false, array('id' => 'related_status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <br/>
                            <label><h5><i class="material-icons">&#xe8ac;</i> {{ __('backend.active_disable') }}
                                </h5></label>
                            <hr class="m-a-0">
                        </div>
                        <div class="form-group row">
                            <label for="link_status"
                                   class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('status','1',($WebmasterSections->status==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="ui-check ui-check-md">
                                        {!! Form::radio('status','0',($WebmasterSections->status==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
                                        <i class="dark-white"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row m-t-md">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                                        &#xe31b;</i> {!! __('backend.update') !!}</button>
                                <a href="{{route("WebmasterSections")}}"
                                   class="btn btn-default m-t"><i class="material-icons">
                                        &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                            </div>
                        </div>

                        {{Form::close()}}
                    </div>
                </div>


                {{-- Custom Fields--}}

                <div class="tab-pane  {{ $tab_2 }}" id="tab_custom">

                    <div class="box-body">
                        @if (Session::has('fieldST'))
                            @if (Session::get('fieldST') == "create")

                                <div>
                                    {{Form::open(['route'=>['webmasterFieldsStore',$WebmasterSections->id],'method'=>'POST'])}}

                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                </label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('title_'.@$ActiveLanguage->code,'', array('placeholder' => '','class' => 'form-control','required'=>'', 'dir'=>@$ActiveLanguage->direction)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    <div class="form-group row">
                                        <label for="type0"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsType') !!}</label>
                                        <div class="col-sm-3">
                                            <div class="radio">
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','0',true, array('id' => 'type0','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType0') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','1',false, array('id' => 'type1','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType1') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','2',false, array('id' => 'type2','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType2') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','3',false, array('id' => 'type3','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType3') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','4',false, array('id' => 'type4','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType4') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','5',false, array('id' => 'type5','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType5') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','6',false, array('id' => 'type6','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType6') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','7',false, array('id' => 'type7','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType7') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','8',false, array('id' => 'type8','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType8') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','9',false, array('id' => 'type9','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType9') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','10',false, array('id' => 'type10','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType10') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','11',false, array('id' => 'type11','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType11') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','12',false, array('id' => 'type12','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType12') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div id="options" style="display: none">
                                                <div class="row">
                                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                                        @if($ActiveLanguage->box_status)
                                                            <div class="col-sm-1 col-xs-1 text-center"
                                                                 style="padding: 0;">
                                                                <br>
                                                                <?php
                                                                $i2 = 0;
                                                                ?>
                                                                @for($i=1;$i<=12;$i++)
                                                                    <?php
                                                                    $i2++;
                                                                    $bg_volor = "#f0f0f0";
                                                                    if ($i2 == 2) {
                                                                        $i2 = 0;
                                                                        $bg_volor = "#f9f9f9";
                                                                    }
                                                                    ?>
                                                                    <div
                                                                        style="font-size: 1rem;line-height: 1.62;background: {{$bg_volor}}">
                                                                        <small>
                                                                            <small>{{$i}}</small>
                                                                        </small>
                                                                    </div>
                                                                @endfor
                                                            </div>

                                                            <div class="col-sm-3 col-xs-5">
                                                                <div>
                                                                    {!!  __('backend.customFieldsOptions') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                                    :
                                                                </div>
                                                                {!! Form::textarea('details_'.@$ActiveLanguage->code,'', array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction,'rows'=>'12','style'=>'white-space: nowrap;')) !!}
                                                            </div>

                                                        @endif
                                                    @endforeach
                                                </div>
                                                <small>
                                                    <i class="material-icons">&#xe8fd;</i> {!!  __('backend.customFieldsOptionsHelp') !!}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="required1"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsRequired') !!}</label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('required','0',true, array('id' => 'required2','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.customFieldsOptional') }}
                                                </label>
                                                &nbsp; &nbsp;
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('required','1',false, array('id' => 'required1','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.customFieldsRequired') }} (*)
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="default_val">
                                        <label for="default_value"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsDefault') !!}
                                        </label>
                                        <div class="col-sm-10">
                                            {!! Form::text('default_value','', array('placeholder' => '','class' => 'form-control','id'=>'default_value')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="default_value"
                                               class="col-sm-2 form-control-label">{!!  __('backend.language') !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="lang_code" id="lang_code" class="form-control c-select">
                                                <option value="all">{{ __('backend.customFieldsForAllLang') }}</option>
                                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                                    @if($ActiveLanguage->box_status)
                                                        <option
                                                            value="{{ $ActiveLanguage->code }}">{{ $ActiveLanguage->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row m-t-md">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary m-t"><i
                                                    class="material-icons">
                                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                                            <a href="{{ route('webmasterFields',[$WebmasterSections->id]) }}"
                                               class="btn btn-default m-t"><i class="material-icons">
                                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                        </div>
                                    </div>

                                    {{Form::close()}}
                                </div>

                            @endif

                            @if (Session::get('fieldST') == "edit")
                                <div>
                                    {{Form::open(['route'=>['webmasterFieldsUpdate',$WebmasterSections->id,Session::get('WebmasterSectionField')->id],'method'=>'POST'])}}

                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                        @if($ActiveLanguage->box_status)
                                            <div class="form-group row">
                                                <label
                                                    class="col-sm-2 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                </label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('title_'.@$ActiveLanguage->code,Session::get('WebmasterSectionField')->{'title_'.@$ActiveLanguage->code}, array('placeholder' =>'','class' => 'form-control','required'=>'', 'dir'=>@$ActiveLanguage->direction)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="form-group row">
                                        <label for="type0"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsType') !!}</label>
                                        <div class="col-sm-3">
                                            <div class="radio">
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','0',(Session::get('WebmasterSectionField')->type==0) ? true : false, array('id' => 'type0','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType0') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','1',(Session::get('WebmasterSectionField')->type==1) ? true : false, array('id' => 'type1','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType1') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','2',(Session::get('WebmasterSectionField')->type==2) ? true : false, array('id' => 'type2','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType2') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','3',(Session::get('WebmasterSectionField')->type==3) ? true : false, array('id' => 'type3','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType3') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','4',(Session::get('WebmasterSectionField')->type==4) ? true : false, array('id' => 'type4','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType4') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','5',(Session::get('WebmasterSectionField')->type==5) ? true : false, array('id' => 'type5','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType5') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','6',(Session::get('WebmasterSectionField')->type==6) ? true : false, array('id' => 'type6','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType6') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','7',(Session::get('WebmasterSectionField')->type==7) ? true : false, array('id' => 'type7','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType7') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','8',(Session::get('WebmasterSectionField')->type==8) ? true : false, array('id' => 'type8','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType8') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','9',(Session::get('WebmasterSectionField')->type==9) ? true : false, array('id' => 'type9','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType9') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','10',(Session::get('WebmasterSectionField')->type==10) ? true : false, array('id' => 'type10','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType10') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','11',(Session::get('WebmasterSectionField')->type==11) ? true : false, array('id' => 'type11','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType11') }}
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 5px;">
                                                    <label class="ui-check ui-check-md">
                                                        {!! Form::radio('type','12',(Session::get('WebmasterSectionField')->type==12) ? true : false, array('id' => 'type12','class'=>'has-value')) !!}
                                                        <i class="dark-white"></i>
                                                        {{ __('backend.customFieldsType12') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-7">

                                            <div id="options"
                                                 style="display: {{(Session::get('WebmasterSectionField')->type==6 || Session::get('WebmasterSectionField')->type==7) ? "inline" : "none"}}">
                                                <div class="row">
                                                    @foreach(Helper::languagesList() as $ActiveLanguage)
                                                        @if($ActiveLanguage->box_status)
                                                            <div class="col-sm-1 col-xs-1 text-center"
                                                                 style="padding: 0;">
                                                                <br>
                                                                <?php
                                                                $i2 = 0;
                                                                ?>
                                                                @for($i=1;$i<=12;$i++)
                                                                    <?php
                                                                    $i2++;
                                                                    $bg_volor = "#f0f0f0";
                                                                    if ($i2 == 2) {
                                                                        $i2 = 0;
                                                                        $bg_volor = "#f9f9f9";
                                                                    }
                                                                    ?>
                                                                    <div
                                                                        style="font-size: 1rem;line-height: 1.62;background: {{$bg_volor}}">
                                                                        <small>
                                                                            <small>{{$i}}</small>
                                                                        </small>
                                                                    </div>
                                                                @endfor
                                                            </div>

                                                            <div class="col-sm-3 col-xs-5">
                                                                <div>
                                                                    {!!  __('backend.customFieldsOptions') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                                                    :
                                                                </div>
                                                                {!! Form::textarea('details_'.@$ActiveLanguage->code,Session::get('WebmasterSectionField')->{'details_'.@$ActiveLanguage->code}, array('placeholder' => '','class' => 'form-control', 'dir'=>@$ActiveLanguage->direction,'rows'=>'12','style'=>'white-space: nowrap;')) !!}
                                                            </div>

                                                        @endif
                                                    @endforeach
                                                </div>
                                                <small>
                                                    <i class="material-icons">&#xe8fd;</i> {!!  __('backend.customFieldsOptionsHelp') !!}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="required1"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsRequired') !!}</label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('required','0',(Session::get('WebmasterSectionField')->required==0) ? true : false, array('id' => 'required2','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.customFieldsOptional') }}
                                                </label>
                                                &nbsp; &nbsp;
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('required','1',(Session::get('WebmasterSectionField')->required==1) ? true : false, array('id' => 'required1','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.customFieldsRequired') }} (*)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="default_val"
                                         style="display: {{(Session::get('WebmasterSectionField')->type==8 || Session::get('WebmasterSectionField')->type==9 || Session::get('WebmasterSectionField')->type==10) ? "none" : "block"}}">
                                        <label for="default_value"
                                               class="col-sm-2 form-control-label">{!!  __('backend.customFieldsDefault') !!}
                                        </label>
                                        <div class="col-sm-10">
                                            {!! Form::text('default_value',Session::get('WebmasterSectionField')->default_value, array('placeholder' => '','class' => 'form-control','id'=>'default_value')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="default_value"
                                               class="col-sm-2 form-control-label">{!!  __('backend.language') !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="lang_code" id="lang_code" class="form-control c-select">
                                                <option
                                                    value="all" {{ (Session::get('WebmasterSectionField')->lang_code=="all")?"selected='selected'":"" }}>{{ __('backend.customFieldsForAllLang') }}</option>
                                                @foreach(Helper::languagesList() as $ActiveLanguage)
                                                    @if($ActiveLanguage->box_status)
                                                        <option
                                                            value="{{ $ActiveLanguage->code }}" {{ (Session::get('WebmasterSectionField')->lang_code==$ActiveLanguage->code)?"selected='selected'":"" }}>{{ $ActiveLanguage->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="link_status"
                                               class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                                        <div class="col-sm-10">
                                            <div class="radio">
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('status','1',(Session::get('WebmasterSectionField')->status==1) ? true : false, array('id' => 'status1','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.active') }}
                                                </label>
                                                &nbsp; &nbsp;
                                                <label class="ui-check ui-check-md">
                                                    {!! Form::radio('status','0',(Session::get('WebmasterSectionField')->status==0) ? true : false, array('id' => 'status2','class'=>'has-value')) !!}
                                                    <i class="dark-white"></i>
                                                    {{ __('backend.notActive') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-t-md">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary m-t"><i
                                                    class="material-icons">
                                                    &#xe31b;</i> {!! __('backend.update') !!}</button>
                                            <a href="{{ route('webmasterFields',[$WebmasterSections->id]) }}"
                                               class="btn btn-default m-t"><i class="material-icons">
                                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                        </div>
                                    </div>

                                    {{Form::close()}}
                                </div>
                            @endif
                        @else

                            @if(count($WebmasterSections->allCustomFields)>0)
                                <div class="row p-a">
                                    <a class="btn btn-fw primary"
                                       href="{{route("webmasterFieldsCreate",[$WebmasterSections->id])}}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('backend.customFieldsNewField') }}
                                    </a>
                                </div>
                            @endif
                            @if(count($WebmasterSections->allCustomFields) == 0)
                                <div class="row p-a">
                                    <div class="col-sm-12">
                                        <div class=" p-a text-center light ">
                                            {{ __('backend.noData') }}
                                            <br>
                                            <br>
                                            <a class="btn btn-fw primary"
                                               href="{{route("webmasterFieldsCreate",[$WebmasterSections->id])}}">
                                                <i class="material-icons">&#xe02e;</i>
                                                &nbsp; {{ __('backend.customFieldsNewField') }}
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(count($WebmasterSections->allCustomFields)>0)
                                {{Form::open(['route'=>['webmasterFieldsUpdateAll',$WebmasterSections->id],'method'=>'post'])}}
                                <div class="row">
                                    <table class="table table-striped  b-t">
                                        <thead>
                                        <tr>
                                            <th style="width:20px;">
                                                <label class="ui-check m-a-0">
                                                    <input id="checkAll4" type="checkbox"><i></i>
                                                </label>
                                            </th>
                                            <th>{{ __('backend.customFieldsTitle') }}</th>
                                            <th>{{ __('backend.customFieldsType') }}</th>
                                            <th class="text-center"
                                                style="width:120px;">{{ __('backend.customFieldsRequired') }}</th>
                                            <th class="text-center"
                                                style="width:100px;">{{ __('backend.language') }}</th>
                                            <th class="text-center"
                                                style="width:120px;">{{ __('backend.customFieldsStatus') }}</th>
                                            <th class="text-center"
                                                style="width:200px;">{{ __('backend.options') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $title_var = "title_" . @Helper::currentLanguage()->code;
                                        $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                                        ?>
                                        @foreach($WebmasterSections->allCustomFields as $customField)
                                            <?php
                                            if ($customField->$title_var != "") {
                                                $field_title = $customField->$title_var;
                                            } else {
                                                $field_title = $customField->$title_var2;
                                            }

                                            $type_var = "customFieldsType" . $customField->type;
                                            ?>
                                            <tr>
                                                <td><label class="ui-check m-a-0">
                                                        <input type="checkbox" name="ids[]"
                                                               value="{{ $customField->id }}"><i
                                                            class="dark-white"></i>
                                                        {!! Form::hidden('row_ids[]',$customField->id, array('class' => 'form-control row_no')) !!}
                                                    </label>
                                                </td>
                                                <td>
                                                    {!! Form::text('row_no_'.$customField->id,$customField->row_no, array('class' => 'pull-left form-control row_no')) !!}
                                                    <small>
                                                        {!! $field_title !!}
                                                    </small>
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ __('backend.'.$type_var) }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <small>
                                                        {{ ($customField->required==1) ? __('backend.customFieldsRequired')."(*)":__('backend.customFieldsOptional') }}
                                                    </small>
                                                </td>

                                                <td class="text-center">
                                                    <small>
                                                        {{ $customField->lang_code }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <i class="fa {{ ($customField->status==1) ? "fa-check text-success":"fa-times text-danger" }} inline"></i>
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-sm success"
                                                       href="{{ route("webmasterFieldsEdit",["webmasterId"=>$WebmasterSections->id,"field_id"=>$customField->id]) }}">
                                                        <small><i class="material-icons">
                                                                &#xe3c9;</i> {{ __('backend.edit') }}</small>
                                                    </a>
                                                    @if(@Auth::user()->permissionsGroup->delete_status)
                                                        <button class="btn btn-sm warning" data-toggle="modal"
                                                                data-target="#mf-{{ $customField->id }}"
                                                                ui-toggle-class="bounce"
                                                                ui-target="#animate">
                                                            <small><i class="material-icons">
                                                                    &#xe872;</i> {{ __('backend.delete') }}
                                                            </small>
                                                        </button>
                                                    @endif

                                                </td>
                                            </tr>
                                            <!-- .modal -->
                                            <div id="mf-{{ $customField->id }}" class="modal fade" data-backdrop="true">
                                                <div class="modal-dialog" id="animate">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                        </div>
                                                        <div class="modal-body text-center p-lg">
                                                            <p>
                                                                {{ __('backend.confirmationDeleteMsg') }}
                                                                <br>
                                                                <strong>[ {!! $field_title !!} ]</strong>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn dark-white p-x-md"
                                                                    data-dismiss="modal">{{ __('backend.no') }}</button>
                                                            <a href="{{ route("webmasterFieldsDestroy",["webmasterId"=>$WebmasterSections->id,"field_id"=>$customField->id]) }}"
                                                               class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div>
                                            </div>
                                            <!-- / .modal -->
                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                                <div class="row">
                                    <div class="col-sm-3 hidden-xs">
                                        <!-- .modal -->
                                        <div id="mf-all" class="modal fade" data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <p>
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark-white p-x-md"
                                                                data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <button type="submit"
                                                                class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <select name="action" id="action4"
                                                class="input-sm form-control w-sm inline v-middle" required>
                                            <option value="">{{ __('backend.bulkAction') }}</option>
                                            <option value="order">{{ __('backend.saveOrder') }}</option>
                                            <option value="activate">{{ __('backend.activeSelected') }}</option>
                                            <option value="block">{{ __('backend.blockSelected') }}</option>
                                            @if(@Auth::user()->permissionsGroup->delete_status)
                                                <option value="delete">{{ __('backend.deleteSelected') }}</option>
                                            @endif
                                        </select>
                                        <button type="submit" id="submit_all4"
                                                class="btn btn-sm white">{{ __('backend.apply') }}</button>
                                        <button id="submit_show_msg4" class="btn btn-sm white" data-toggle="modal"
                                                style="display: none"
                                                data-target="#mf-all" ui-toggle-class="bounce"
                                                ui-target="#animate">{{ __('backend.apply') }}
                                        </button>
                                    </div>
                                </div>
                                {{Form::close()}}
                            @endif
                        @endif
                    </div>
                </div>
                {{-- End of Custom Fields --}}


                @if(Helper::GeneralWebmasterSettings("seo_status"))
                    <div class="tab-pane  {{ $tab_3 }}" id="tab_seo">

                        <div class="box-body">
                            {{Form::open(['route'=>['WebmasterSectionsSEOUpdate',$WebmasterSections->id],'method'=>'POST'])}}

                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                <div class="row">
                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <div>
                                                <small>{!!  __('backend.topicSEOTitle') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}

                                                {!! Form::text('seo_title_'.@$ActiveLanguage->code,$WebmasterSections->{'seo_title_'.@$ActiveLanguage->code}, array('class' => 'form-control','id'=>'seo_title_'.@$ActiveLanguage->code,'maxlength'=>'65', 'dir'=>@$ActiveLanguage->direction)) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div>
                                                <small>{!!  __('backend.friendlyURL') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}

                                                {!! Form::text('seo_url_slug_'.@$ActiveLanguage->code,$WebmasterSections->{'seo_url_slug_'.@$ActiveLanguage->code}, array('class' => 'form-control','id'=>'seo_url_slug_'.@$ActiveLanguage->code, 'dir'=>@$ActiveLanguage->direction)) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div>
                                                <small>{!!  __('backend.topicSEODesc') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}

                                                {!! Form::textarea('seo_description_'.@$ActiveLanguage->code,$WebmasterSections->{'seo_description_'.@$ActiveLanguage->code}, array('class' => 'form-control','id'=>'seo_description_'.@$ActiveLanguage->code,'maxlength'=>'165', 'dir'=>@$ActiveLanguage->direction,'rows'=>'2')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div>
                                                <small>{!!  __('backend.topicSEOKeywords') !!}</small> {!! @Helper::languageName($ActiveLanguage) !!}

                                                {!! Form::textarea('seo_keywords_'.@$ActiveLanguage->code,$WebmasterSections->{'seo_keywords_'.@$ActiveLanguage->code}, array('class' => 'form-control','id'=>'seo_keywords_'.@$ActiveLanguage->code, 'dir'=>@$ActiveLanguage->direction,'rows'=>'2')) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php
                                        $seo_example_title = $WebmasterSections->{'title_' . @$ActiveLanguage->code};
                                        $seo_example_desc = Helper::GeneralSiteSettings("site_desc_" . @$ActiveLanguage->code);
                                        if ($WebmasterSections->{'seo_title_' . @$ActiveLanguage->code} != "") {
                                            $seo_example_title = $WebmasterSections->{'seo_title_' . @$ActiveLanguage->code};
                                        }
                                        if ($WebmasterSections->{'seo_description_' . @$ActiveLanguage->code} != "") {
                                            $seo_example_desc = $WebmasterSections->{'seo_description_' . @$ActiveLanguage->code};
                                        }
                                        $seo_example_url = Helper::sectionURL($WebmasterSections->id, @$ActiveLanguage->code);
                                        ?>
                                        <div class="form-group">
                                            <div class="search-example-div">
                                                {!! @Helper::languageName($ActiveLanguage) !!}
                                                <div class="search-example" dir="{{ @$ActiveLanguage->direction }}">
                                                    <a id="title_in_engines_{{ @$ActiveLanguage->code }}"
                                                       href="{{ $seo_example_url }}"
                                                       target="_blank">{{ $seo_example_title }}</a>
                                                    <span
                                                        id="url_in_engines_{{ @$ActiveLanguage->code }}">{{ $seo_example_url }}</span>
                                                    <div
                                                        id="desc_in_engines_{{ @$ActiveLanguage->code }}">{{ $seo_example_desc }}
                                                        ...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div>
                                                <i class="material-icons">&#xe8fd;</i>
                                                <small>{!!  __('backend.seoTabSettings') !!}</small>
                                            </div>
                                        </div>
                                        <br>
                                        <br>

                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group">
                                <div>
                                    <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                                    <a href="{{ route('WebmasterSectionsEdit',$WebmasterSections->id) }}"
                                       class="btn btn-default m-t"><i class="material-icons">
                                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                                </div>
                            </div>
                            {{Form::close()}}
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
@section('footerInclude')
    <script type="text/javascript">
        $("#checkAll4").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#action4").change(function () {
            if (this.value == "delete") {
                $("#submit_all4").css("display", "none");
                $("#submit_show_msg4").css("display", "inline-block");
            } else {
                $("#submit_all4").css("display", "inline-block");
                $("#submit_show_msg4").css("display", "none");
            }
        });
        $("input:radio[name=type]").click(function () {
            if ($(this).val() == 6 || $(this).val() == 7) {
                $("#options").css("display", "inline");
            } else {
                $("#options").css("display", "none");
            }
            if ($(this).val() == 8 || $(this).val() == 9 || $(this).val() == 10) {
                $("#default_val").css("display", "none");
            } else {
                $("#default_val").css("display", "block");
            }
        });

        // Js Slug
        function slugify(string) {
            return string
                .toString()
                .trim()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        }

        @foreach(Helper::languagesList() as $ActiveLanguage)
        $("#seo_title_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#title_in_engines_{{ @$ActiveLanguage->code}}").text($(this).val());
            } else {
                $("#title_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo $WebmasterSections->{'title_' . @$ActiveLanguage->code}; ?>");
            }
        });
        $("#seo_description_{{ @$ActiveLanguage->code}}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#desc_in_engines_{{ @$ActiveLanguage->code }}").text($(this).val());
            } else {
                $("#desc_in_engines_{{ @$ActiveLanguage->code}}").text("<?php echo Helper::GeneralSiteSettings("site_desc_" . @$ActiveLanguage->code); ?>");
            }
        });
        $("#seo_url_slug_{{ @$ActiveLanguage->code }}").on('keyup change', function () {
            if ($(this).val() != "") {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo url(''); ?>/" + slugify($(this).val()));
            } else {
                $("#url_in_engines_{{ @$ActiveLanguage->code }}").text("<?php echo Helper::sectionURL($WebmasterSections->id,@$ActiveLanguage->code); ?>");
            }
        });
        @endforeach
    </script>
@endsection
