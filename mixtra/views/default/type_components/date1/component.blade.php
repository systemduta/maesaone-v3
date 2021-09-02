<?php
$datetype = isset($form["datetype"]) ? $form["datetype"] : "datetime";
?>

@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>
    <div class="cal-icon {{ $col_width }}">
        <input type="text" title="{{ $form['label'] }}" class="form-control datetimepicker form-control-sm {{ $class }}" 
            name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"
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
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
@endpush
