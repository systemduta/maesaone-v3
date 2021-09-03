@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>
    <div class="{{ $col_width }}">
        <input type="text" title="{{ $form['label'] }}" class="form-control form-control-sm notfocus input_date {{ $class }}" autocomplete="off"
            name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" {{ $required }} {{ $readonly }} {{$disabled}} {!! $placeholder !!} />
        <div class="text-danger small">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        @if(isset($form['help']))
        <p class='text-muted small'>{{ @$form['help'] }}</p>
        @endif
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif

