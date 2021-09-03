<style>
    .scrollable {
        overflow-y: scroll;
    }

    .bodycontainer {
        height: 400px !important;
        width: 100%;
    }
</style>

<!-- Nav tabs -->
<br/>
<hr/>
<ul class="nav nav-tabs nav-fill" id="tab-{{$name}}" role="tablist">
<?php
    $active = 'active';
?>

@foreach($form['tabpages'] as $tabpage)
    <li class="nav-item"> 
        <a class="nav-link {{$active}}" id="profile-tab-fill" data-toggle="tab" href="#{{$tabpage['name']}}" role="tab" aria-controls="profile-fill" aria-selected="false">
            <span class="hidden-sm-up"><i class="{{$tabpage['image']}}"></i></span>
            <span class="hidden-xs-down">{{$tabpage['label']}}</span>
        </a>
    </li>

<?php
    if ($active != '') {
        $active = '';
    }
?>
@endforeach
</ul>

<!-- Tab panes -->
<div class="tab-content pt-1">
    <?php
        $active = 'active';
    ?>

    @foreach($form['tabpages'] as $tabpage)

    @if(file_exists(base_path('/mixtra/views/default/type_components/'.$type.'/asset.blade.php')))
        @include('mixtra::default.type_components.'.$type.'.asset')
    @elseif(file_exists(resource_path('views/mixtra/type_components/'.$type.'/asset.blade.php')))
        @include('mixtra.type_components.'.$type.'.asset')
    @endif

    <div class="tab-pane p-20 {{$active}} bodycontainer" id="{{$tabpage['name']}}" role="tabpanel" aria-labelledby="home-tab-fill">

    {{-- <div class="tab-pane p-20 {{$active}} bodycontainer scrollable" id="{{$tabpage['name']}}" role="tabpanel" aria-labelledby="home-tab-fill"> --}}
        <?php
        $header_group_class = "";
        foreach ($tabpage['pages'] as $form) {
            $name = $form['name'];
            @$join = $form['join'];
            @$value = (isset($form['value'])) ? $form['value'] : '';
            @$value = (isset($row->{$name})) ? $row->{$name} : $value;

            $old = old($name);
            $value = (! empty($old)) ? $old : $value;

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

            if (isset($form['callback_php'])) {
                @eval("\$value = ".$form['callback_php'].";");
            }

            if (isset($form['callback'])) {
                $value = call_user_func($form['callback'], $row);
            }

            $form['type'] = (isset($form['type'])) ? $form['type'] : 'text';
            $type = @$form['type'];
            $required = (@$form['required']) ? "required" : "";
            $required = (@strpos($form['validation'], 'required') !== false) ? "required" : $required;
            $readonly = (@$form['readonly']) ? "readonly" : "";
            $disabled = (@$form['disabled']) ? "disabled" : "";
            $placeholder = (@$form['placeholder']) ? "placeholder='".$form['placeholder']."'" : "";
            $col_width = @$form['width'] ?: "col-sm-10";
            $label_width = @$form['label_width'] ?: "col-sm-2";
        ?>
            @if(file_exists(base_path('/mixtra/views/default/type_components/'.$type.'/component.blade.php')))
                @include('mixtra::default.type_components.'.$type.'.component')
            @elseif(file_exists(resource_path('views/mixtra/type_components/'.$type.'/component.blade.php')))
                @include('mixtra.type_components.'.$type.'.component')
            @else
                <p class='text-danger'>{{$type}} is not found in type component system</p><br/>
            @endif

        <?php
        if ($active != '') {
            $active = '';
        }}
        ?>
    </div>
    @endforeach
</div>