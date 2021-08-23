<div id="role_menu">
    <table class='table table-striped table-hover table-bordered'>
    <thead>
        <tr class='active'>
            <th width='3%'>No</th>
            <th width='60%'>Menu</th>
            <th width="5%">&nbsp;</th>
            <th>View</th>
            <th>Create</th>
            <th>Read</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        <tr class='info'>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <td class="text-center"><input title='Check all vertical' type='checkbox' id='is_visible'/></td>
            <td class="text-center"><input title='Check all vertical' type='checkbox' id='is_create'/></td>
            <td class="text-center"><input title='Check all vertical' type='checkbox' id='is_read'/></td>
            <td class="text-center"><input title='Check all vertical' type='checkbox' id='is_edit'/></td>
            <td class="text-center"><input title='Check all vertical' type='checkbox' id='is_delete'/></td>
        </tr>
    </thead>
    <tbody>
<?php
$no = 1;
$menus = db::table('mit_menus')->whereNotNull('controller')->get();
?>
@foreach($menus as $menu)
    <?php
    if (isset($row)):
        $roles = DB::table('mit_roles_menus')->where('mit_menu_id', $menu->id)->where('mit_role_id', $row->id)->first();
        $is_visible = false;
        $is_create = false;
        $is_read = false;
        $is_edit = false;
        $is_delete = false;
        if($roles != null) {
            $is_visible = $roles->is_visible;
            $is_create = $roles->is_create;
            $is_read = $roles->is_read;
            $is_edit = $roles->is_edit;
            $is_delete = $roles->is_delete;
        }
    ?>

        <tr>
            <td class="text-center"><?php echo $no++;?></td>
            <td>{{$menu->name}}</td>
            <td class="text-center">
                <input type='checkbox' title='Check All Horizontal' 
                    <?=($row->is_superadmin || ($is_create && $is_read && $is_edit && $is_delete)) ? "checked" : ""?> class='select_horizontal'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_visible' name='privileges[<?=$menu->id?>][is_visible]'
                    <?=$row->is_superadmin || $is_visible ? "checked" : ""?> value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_create' name='privileges[<?=$menu->id?>][is_create]'
                    <?=$row->is_superadmin || $is_create ? "checked" : ""?> value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_read' name='privileges[<?=$menu->id?>][is_read]'
                    <?=$row->is_superadmin || $is_read ? "checked" : ""?> value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_edit' name='privileges[<?=$menu->id?>][is_edit]'
                    <?=$row->is_superadmin || $is_edit ? "checked" : ""?> value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_delete' name='privileges[<?=$menu->id?>][is_delete]'
                    <?=$row->is_superadmin || $is_delete ? "checked" : ""?> value='1'/>
            </td>
        </tr>
    <?php
    else:
    ?>

        <tr>
            <td class="text-center"><?php echo $no++;?></td>
            <td>{{$menu->name}}</td>
            <td class="text-center">
                <input type='checkbox' title='Check All Horizontal' class='select_horizontal'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_visible' name='privileges[<?=$menu->id?>][is_visible]' value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_create' name='privileges[<?=$menu->id?>][is_create]' value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_read' name='privileges[<?=$menu->id?>][is_read]' value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_edit' name='privileges[<?=$menu->id?>][is_edit]' value='1'/>
            </td>
            <td class="text-center">
                <input type='checkbox' class='is_delete' name='privileges[<?=$menu->id?>][is_delete]' value='1'/>
            </td>
        </tr>
    <?php
    endif
    ?>
@endforeach
    </tbody>
    </table>
</div>


@push('bottom')
<script type="text/javascript">
    $(function () {
        $test = $('#is_superadmin input:checked').val();
        if($test == '1')
            $('#role_menu').hide();

        $('#is_superadmin input').click(function () {
            var val = $(this).val();
            if (val == '1') {
                $('#role_menu').hide();
            } else {
                $('#role_menu').show();
            }
        })

        $("#is_visible").click(function () {
            var is_ch = $(this).prop('checked');
            console.log('is checked create ' + is_ch);
            $(".is_visible").prop("checked", is_ch);
            console.log('Create all');
        })
        $("#is_create").click(function () {
            var is_ch = $(this).prop('checked');
            console.log('is checked create ' + is_ch);
            $(".is_create").prop("checked", is_ch);
            console.log('Create all');
        })
        $("#is_read").click(function () {
            var is_ch = $(this).is(':checked');
            $(".is_read").prop("checked", is_ch);
        })
        $("#is_edit").click(function () {
            var is_ch = $(this).is(':checked');
            $(".is_edit").prop("checked", is_ch);
        })
        $("#is_delete").click(function () {
            var is_ch = $(this).is(':checked');
            $(".is_delete").prop("checked", is_ch);
        })
        $(".select_horizontal").click(function () {
            var p = $(this).parents('tr');
            var is_ch = $(this).is(':checked');
            p.find("input[type=checkbox]").prop("checked", is_ch);
        })
    });
</script>
@endpush