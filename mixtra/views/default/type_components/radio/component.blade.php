@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>

    <div class="{{ $col_width }}">
        @if(!isset($form['dataenum']))
            <em>{{trans('locale.there_is_no_option')}}</em>
        @else
        <div id="{{ $name }}">
            <?php
            $dataenum = $form['dataenum'];
            $dataenum = (is_array($dataenum)) ? $dataenum : explode(";", $dataenum);
            ?>
            @foreach($dataenum as $k=>$d)
                <?php
                if (strpos($d, '|')) {
                    $val = substr($d, 0, strpos($d, '|'));
                    $label = substr($d, strpos($d, '|') + 1);
                } else {
                    $val = $label = $d;
                }
                $checked = $val == $value ? "checked" : "";
                ?>
                <div class="form-check form-check-inline" style="padding-top: 7px;">
                    <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}" value="{{$val}}" {{$checked}} {{$readonly}} {{$disabled}}> 
                    <label class="form-check-label" for="{{ $name }}">{{$label}}</label>
                </div>
            @endforeach
        </div>
        @endif

        <div class="text-danger small">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        @if(isset($form['help']))
        <p class='text-muted small'>{{ @$form['help'] }}</p>
        @endif
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif

