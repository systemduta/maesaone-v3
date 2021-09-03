<?php $asset_already = []; ?>
@foreach ($forms as $form)
    <?php $type = isset($form['type']) ? $form['type'] : 'text'; ?>
    
    @if (!in_array($type, $asset_already))
        @if (file_exists(base_path('/mixtra/views/default/type_components/'.$type.'/asset.blade.php')))
            @include('mixtra::default.type_components.'.$type.'.asset')
        @elseif (file_exists(resource_path('views/mixtra/type_components/'.$type.'/asset.blade.php')))
            @include('mixtra.type_components.'.$type.'.asset')
        @endif
    @endif

    <?php
    $asset_already[] = $type;

    $name = $form['name'];
    $value = isset($form['value']) ? $form['value'] : '';
    // If Edit
    if (isset($row)) {
        $value = isset($row->{$name}) ? $row->{$name} : $value;
    }

    // Get Old Value
    $old = old($name);
    $value = (!empty($old)) ? $old : $value;
    
    // Validation
    $validation = array();
    $validation_raw = isset($form['validation']) ? explode('|', $form['validation']) : array();
    if ($validation_raw) {
        foreach ($validation_raw as $vr) {
            $vr_a = explode(':', $vr);
            if ($vr_a[1]) {
                $key = $vr_a[0];
                $validation[$key] = $vr_a[1];
            } else {
                $validation[$vr] = true;
            }
        }
    }

    // Required, Readonly and Disabled
    $required = isset($form['required']) ? "required" : "";
    $disabled = isset($form['disabled']) ? "disabled" : "";
    $readonly = isset($form['readonly']) ? "readonly" : "";
    // If Detail View
    if ($command == 'detail') {
        $readonly = 'readonly';
        if ($type != 'text') {
            $disabled = 'disabled';
        }
    }

    $placeholder = isset($form['placeholder']) ? "placeholder='".$form['placeholder']."'" : "";
    $col_width = isset($form['width']) ? $form['width'] : "col-sm-10";
    $label_width = isset($form['label_width']) ? $form['label_width'] : "col-sm-2";
    $class = isset($form['class']) ? $form['class'] : "";

    ?>
    
    @if(file_exists(base_path('/mixtra/views/default/type_components/'.$type.'/component.blade.php')))
        @include('mixtra::default.type_components.'.$type.'.component')
    @elseif(file_exists(resource_path('views/mixtra/type_components/'.$type.'/component.blade.php')))
        @include('mixtra.type_components.'.$type.'.component')
    @else
        <p class='text-danger'>{{$type}} is not found in type component system</p><br/>
    @endif

@endforeach