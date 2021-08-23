@extends('mixtra::layouts.app')

@section('title', 'Settings')

@push('head')
    <style>
    .dropify-wrapper .dropify-message span.file-icon p {
        font-size: 20px;
    }
    </style>
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="card-title"><i class='fa fa-cog'></i> {{$page_title}} </h4>  
            
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

            <form method='post' 
                id="form" 
                class="form-horizontal" 
                enctype="multipart/form-data" 
                action='{{MITBooster::mainpath("save-setting?group_setting=$page_title")}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="card">
                    <div class="card-body">
                    <?php
                    $set = DB::table('mit_settings')->where('group_setting', $page_title)->orderby('id')->get();
                    foreach ($set as $s):
                        $value = $s->content;
                        $name = $s->name;
                    ?>

                        <div class="form-group row px-5">
                            <label for="{{$s->name}}" class="col-sm-2 col-form-label col-form-label-sm" style="padding-top: 7px;">
                                {{$s->label}}
                            </label>
                            <div class="col-sm-10">
                            <?php
                                switch ($s->content_input_type) {
                                    case 'text':
                                        echo "<input type='text' class='form-control form-control-sm' title='$s->name' name='$s->name' value='$value'/>";
                                        break;
                                    case 'upload_image':
                                        $default_data = "";
                                        if (isset($s->content) && $s->content != '') {
                                            echo "<input type='hidden' id='$s->name' name='$s->name' value='$value' />";
                                            $default_data = 'data-default-file="'.asset($s->content).'"';
                                        }
                                        echo "<input type='file' name='$s->name' id='$s->name' class='upload_image $s->name' data-max-file-size='".config('mixtra.default_upload_max_size', '1k')."' data-allowed-file-extensions='".config('mixtra.image_extensions', 'jpg,png,gif,bmp')."' $default_data />";
                                        echo "<footer><small class='text-muted'>Support File: ".config('mixtra.image_extensions', 'jpg,png,gif,bmp')."</small></footer>";
                                        echo "<footer><small class='text-muted'>Max Size: ".config('mixtra.default_upload_max_size', '1k')."</small></footer>";
                                        break;
                                }
                            ?>
                            </div>
                        </div>

                    <?php
                    endforeach;
                    ?>
                    </div>

                    <div class="card-footer">
                        <button type="submit" name="submit" value='{{trans("locale.button_save")}}' class='btn btn-sm btn-success'>{{trans("locale.button_save")}}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('bottom')
<script type="text/javascript">
    $('.upload_image').dropify();
    @foreach ($set as $s)
        $('.{{$s->name}}').on('dropify.afterClear', function(event, element){
            $('#{{$s->name}}').val(null);
        });
    @endforeach

</script>
@endpush
