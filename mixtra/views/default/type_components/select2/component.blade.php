@if(!isset($form['begin_group']) || $form['begin_group'] || $form['begin_group'] == 'true')
<div class="form-group row mb-1">
@endif

    <label for="{{ $name }}" class="{{ $label_width }} col-form-label col-form-label-sm" style="padding-top: 7px;">{{ $form['label'] }}
        @if($required)
        <span class='text-danger'>*</span>
        @endif
    </label>

    <div class="{{ $col_width }}">
        @if(!isset($form['dataenum']) && !isset($form['datatable']))
            <em>{{trans('locale.there_is_no_option')}}</em>
        @else
        <div>
            <select style='width:100%' class='select2 form-control form-control-sm custom-select' id="{{$name}}" name="{{$name}}"
                {{$required}} {{$readonly}} {{$disabled}}>
                <option value=''>{{trans('locale.text_prefix_option')}}</option>

            <?php
            if (isset($form['datatable'])) {
                $datatable = $form['datatable'];
                $datatable = (is_array($datatable)) ? $datatable : explode(",", $datatable);

                $select_table = $datatable[0];
                $select_title = $datatable[1];

                $result = DB::table($select_table)->select('id', $select_title);

                $select_where = isset($form['datatable_where']) ? $form['datatable_where'] : "";
                if ($select_where) {
                    $result->whereRaw($select_where);
                }
                $result = $result->orderBy($select_title, 'asc')->get();

                foreach ($result as $r) {
                    $option_label = $r->{$select_title};
                    $option_value = $r->id;
                    $selected = $option_value == $value ? "selected" : "";
                    echo "<option ".$selected." value='".$option_value."'>".$option_label."</option>";
                }
            } else {
                $dataenum = $form['dataenum'];
                $dataenum = (is_array($dataenum)) ? $dataenum : explode(";", $dataenum);

                foreach ($dataenum as $k=>$d) {
                    if (strpos($d, '|')) {
                        $val = substr($d, 0, strpos($d, '|'));
                        $label = substr($d, strpos($d, '|') + 1);
                    } else {
                        $val = $label = $d;
                    }
                    $selected = $val == $value ? "selected" : "";
                    echo "<option ".$selected." value='".$val."'>".$label."</option>";
                }
            }
            ?>

            </select>

            <div class="text-danger small">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
            @if(isset($form['help']))
            <p class='text-muted small'>{{ @$form['help'] }}</p>
            @endif
        </div>
        @endif
    </div>

@if(!isset($form['end_group']) || $form['end_group'] || $form['end_group'] == 'true')
</div>
@endif

@push('bottom')
    <script type="text/javascript">
        $(function () {
            @if($readonly)
            $('#{{$name}}').select2({disabled: true});
            @else
            $('#{{$name}}').select2();
            @endif
            
        })
    </script>
@endpush