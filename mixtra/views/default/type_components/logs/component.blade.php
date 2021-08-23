@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>
    <div class="{{ $col_width }}">
        {!! $value !!}
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif
