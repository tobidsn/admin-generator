@php
use App\Setting;
@endphp

@extends('_admin.master')
@section('content')
<section class="content-header p-2">
   <div class="container-fluid">
       <div class="row mb-0">
           <div class="col-sm-6">
               <h4>{{ $title }}</h4>
           </div>
           <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                   <li class="breadcrumb-item"><a href="#">Home</a></li>
                   <li class="breadcrumb-item active">{{ $title }}</li>
               </ol>
           </div>
       </div>
   </div>
</section> 

<section class="content">
   <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <div class="btn-group p-2">
                            <button type="button" class="btn btn-sm btn-primary btn-save">Save</button>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills p-2">
                            @foreach ($data as $group => $sections)
                            <li class="nav-item"><a class="nav-link{{ $loop->first ? ' active show' : null }}" href="#{{ str_slug($group) }}" data-toggle="tab">{{ $group }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/site/save') }}" id="settings-form" role="form" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="tab-content">
                                @foreach ($data as $group => $sections)
                                <div class="tab-pane{{ $loop->first ? ' active show' : null }}" id="{{ str_slug($group) }}">
                                    @foreach ($sections as $section => $settings)
                                    <fieldset class="form-fieldset no-bg">
                                        <legend class="bg-light p-2 border-primary">{{ $section }}</legend>
                                        @foreach ($settings as $setting)

                                            @switch ($setting->type)
                                                @case(Setting::TYPE_TEXTINPUT)
                                                    @include('_admin.settings.parts.type-textinput')
                                                    @break

                                                @case(Setting::TYPE_TEXTAREA)
                                                    @include('_admin.settings.parts.type-textarea')
                                                    @break

                                                @case(Setting::TYPE_TEXTINPUT_I18N)
                                                    @include('_admin.settings.parts.type-textinput-i18n')
                                                    @break

                                                @case(Setting::TYPE_TEXTAREA_I18N)
                                                    @include('_admin.settings.parts.type-textarea-i18n')
                                                    @break

                                                @case(Setting::TYPE_CHECKSWITCH)
                                                    @include('_admin.settings.parts.type-checkswitch')
                                                    @break

                                                @case(Setting::TYPE_IMAGE)
                                                    @include('_admin.settings.parts.type-image')
                                                    @break

                                                @case(Setting::TYPE_SELECT2MULTI)
                                                    @include('_admin.settings.parts.type-select2multi')
                                                    @break

                                                @default
                                                    @include('_admin.settings.parts.no-layout')
                                            @endswitch
                                        @endforeach
                                    </fieldset>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>

                            <input type="submit" class="d-none">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script>
    var textAreas = document.getElementsByTagName('textarea');
    Array.prototype.forEach.call(textAreas, function(elem) {
        elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
    });

    $('.btn-save').click(function(e) {
        e.preventDefault();
        $('#settings-form').submit();
    });
</script>
@endsection
