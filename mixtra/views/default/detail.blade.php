@extends('mixtra::layouts.app')

@section('title', $module->name)

@section('content')
@section('content')
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="card-title"> {{ $command == "edit" ? "Edit" : ($command == "add" ? "Add" : "View" ) }} {{ $module->name }} </h4>  
            
            @if (Session::get('message')!='')
                <div class="alert alert-{{ Session::get('message_type') }} alert-dismissible fade show mx-2" role="alert">
                    <div class="alert-body">
                        <h3 class="text-{{ Session::get('message_type') }}">
                            <i class="fa fa-info"></i> {{ Session::get('message_type') }}
                        </h3> 
                        {!!Session::get('message')!!}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif

            <?php
                $action = isset($row) ? MITBooster::mainpath("edit-save/".$row->id) : MITBooster::mainpath("add-save");
                $return_url = isset($return_url) ? $return_url : Request::get('return_url');
                if ($command == 'edit') {
                    $return_url = Request::fullUrl();
                }
            ?>
            <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data" action='{{$action}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="return_url" value="{{ $return_url }}">

                <div class="card">
                    <div class="card-body">
                        @include("mixtra::default.form")
                    </div>
                    <div class="card-footer">
                        @if(Request::get('return_url'))
                            <a href='{{ Request::get('return_url') }}' 
                                class='btn btn-sm btn-secondary'>
                                <i class='fa fa-chevron-circle-left'></i> {{trans("locale.button_back")}}
                            </a>
                        @else
                            <a href='{{ MITBooster::mainpath("?".http_build_query(@$_GET)) }}' 
                                class='btn btn-sm btn-dark'>
                                <i class='fa fa-chevron-circle-left'></i> {{trans("locale.button_back")}}
                            </a>
                        @endif
                        @if(MITBooster::isCreate() || MITBooster::isUpdate())
                            @if(MITBooster::isCreate() && $command == 'add')
                                <button type="submit" name="submit" value='{{trans("locale.button_save_more")}}' class='btn btn-sm btn-success'>{{trans("locale.button_save_more")}}</button>
                            @endif
                            @if(MITBooster::isUpdate() && $command == 'edit')
                                <button type="submit" name="submit" value='{{trans("locale.button_save_close")}}' class='btn btn-sm btn-success'>{{trans("locale.button_save_close")}}</button>
                            @endif
                            @if($command != 'detail')
                                <button type="submit" name="submit" value='{{trans("locale.button_save")}}' class='btn btn-sm btn-info'>{{trans("locale.button_save")}}</button>
                            @endif
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection