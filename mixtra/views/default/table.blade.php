<form id='form-table' method='post' action='{{ MITBooster::mainpath("action-selected") }}'>
    <input type='hidden' name='button_name' value=''/>
    <input type='hidden' name='_token' value='{{csrf_token()}}'/>
    <table id="table_index" class="table table-hover table-striped table-bordered table-primary">
        <thead>
            <tr>
                @if ($button_bulk_action)
                <th width='3%'><input type='checkbox' id='checkall'/></th>
                @endif
                @if ($show_numbering)
                <th width="1%">No.</th>
                @endif
                <?php
                    foreach ($columns as $col) {
                        if (isset($col['hide']) && $col['hide']) {
                            continue;
                        }
                        $label = $col['label'];
                        $field = $col['field'];
                        $class = isset($col['class']) ? $col['class'] : "";
                        $width = isset($col['width']) ? "min-width:".$col['width']."px;width:".$col['width']."px;" : "";

                        echo "<th style='$width' class='$class'>";
                        $params = Request::all();
                        $sorting = null;
                        foreach ($params as $key => $param) {
                            $k = str_replace("sort_", "", $key);
                            if ($k == $field) {
                                $sorting = $param;
                            }
                        }

                        if (isset($sorting)) {
                            switch ($sorting) {
                                case 'asc':
                                    $url = MITBooster::urlFullText($field, 'sort', 'desc');
                                    echo "<a href='$url' class='text-white' title='Click to sort descending'>$label <i class='fa fa-sort-alpha-down'></i></a>";
                                    break;
                                case 'desc':
                                    $url = MITBooster::urlFullText($field, 'sort', 'asc');
                                    echo "<a href='$url' class='text-white' title='Click to sort ascending'>$label <i class='fa fa-sort-alpha-down-alt'></i></a>";
                                    break;
                                default:
                                    $url = MITBooster::urlFullText($field, 'sort', 'asc');
                                    echo "<a href='$url' class='text-white' title='Click to sort ascending'>$label</a>";
                                    break;
                            }
                        } else {
                            $url = MITBooster::urlFullText($field, 'sort', 'asc');
                            echo "<a href='$url' class='text-white' title='Click to sort ascending'>$label</a>";
                        }

                        echo "</th>";
                    }
                ?>
                @if($button_table_action)
                    @if(MITBooster::isUpdate() || MITBooster::isDelete() || MITBooster::isRead())
                        <th style="width:80px;text-align: center">Action</th>
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
        @if($result == null || count($result)==0)
        <tr class='table-warning'>
            <?php
                $colspan = count($columns);
                if ($button_bulk_action && $show_numbering) {
                    $colspan += 3;
                } elseif (($button_bulk_action && !$show_numbering) || (!$button_bulk_action && $show_numbering)) {
                    $colspan += 2;
                } else {
                    $colspan += 1;
                }
            ?>
            <td colspan='{{ $colspan }}' class="text-center">
                <i class='fa fa-search'></i> No Data Avaliable
            </td>
        </tr>
        @else
            @foreach($html_contents as $html)
            <tr>
                @foreach($html as $h)
                <td>{!! $h !!}</td>
                @endforeach
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</form>

@push("bottom")
<script type="text/javascript">
    $(function () {
        $("#table_index #checkall").click(function () {
            var is_checked = $(this).is(":checked");
            $("#table_index .checkbox").prop("checked", !is_checked).trigger("click");
        })
    })

    $('.selected-action div a').click(function () {
        selectedData(0);
        
        var name = $(this).data('name');
        $('#form-table input[name="button_name"]').val(name);
        var title = $(this).attr('title');
    });

</script>
@endpush