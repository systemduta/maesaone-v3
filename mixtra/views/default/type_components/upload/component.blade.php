@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>
    <div class="{{ $col_width }}">
        <?php
        $max_size = 'data-max-file-size='.config('mixtra.default_upload_max_size', '1k');
        // dd($max_size);
        
        $file_extensions = "";
        if (isset($form['class']) && $form['class'] == 'image') {
            $file_extensions = "data-allowed-file-extensions='".config('mixtra.image_extensions', 'jpg,png,gif,bmp')."'";
        }

        $default_data = "";
        if (isset($value)) {
            echo "<input type='hidden' id='$name' name='_$name' value='$value' />";
            $default_data = "data-default-file='".asset($value)."'";
        }
        ?>

        <input type="file" title="{{ $form['label'] }}" class="form-control form-control-sm upload {{ $class }} {{ $name }}" 
            {{ $max_size }} {!! $file_extensions !!} {!! $default_data !!}
            name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" multiple  style="height: 200px;"/>
        @if (isset($form['class']) && $form['class'] == 'image')
        <footer><small class='text-muted'>Support File: {{ config('mixtra.image_extensions', 'jpg,png,gif,bmp') }}</small></footer>
        @endif
        <footer><small class='text-muted'>Max Size: {{ config('mixtra.default_upload_max_size', '1k') }}</small></footer>

        <div class="text-danger small">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        @if(isset($form['help']))
        <p class='text-muted small'>{{ @$form['help'] }}</p>
        @endif
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif

@push('bottom')
    <script type="text/javascript">
        $('.{{$name}}').on('dropify.afterClear', function(event, element){
            $('#{{$name}}').val(null);
        });
    </script>
@endpush

