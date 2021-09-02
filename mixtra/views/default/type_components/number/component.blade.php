<?php
$decimals = !isset($form["decimals"]) || $form["decimals"] == '' ? 0 : $form["decimals"];
if ($value == null || $value == '') {
    $value = 0;
}
?>

@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>
    <div class="{{ $col_width }}">
        <input type="text" title="{{ $form['label'] }}" class="form-control form-control-sm text-right {{ $class }}" 
            name="{{ $name }}" id="{{ $name }}" value="{{ number_format($value,$decimals) }}"
            {{ $required }} {{ $readonly }} {{$disabled}} {!! $placeholder !!} />
        <div class="text-danger small">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        @if(isset($form['help']))
        <p class='text-muted small'>{{ @$form['help'] }}</p>
        @endif
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif

@push('bottom')

    @if($readonly == '')
    <script type="text/javascript">
        $('#{{$name}}').blur(function() {
            var value = $(this).val();
            $(this).val(numberToString(value, {{$decimals}}));
        });
        $('#{{$name}}').keypress(function onlyNumberKey(event) {
            var ASCIICode = (event.which) ? event.which : event.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        });
    </script>
    @endif

@endpush

