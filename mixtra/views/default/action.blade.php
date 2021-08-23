    <div class="dropdown dropdown-action" style="height: 18px;">
        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
        <div class="dropdown-menu dropdown-menu-right">
            @if(MITBooster::isRead() && $button_detail)
            <a class="dropdown-item" href="{{ MITBooster::mainpath("detail/".$row->$primary)."?return_url=".urlencode(Request::fullUrl()) }}"><i class="fa fa-eye m-r-5"></i> {{trans("locale.action_detail_data")}}</a>
            @endif
            @if(MITBooster::isUpdate() && $button_edit)
            <a class="dropdown-item" href="{{ MITBooster::mainpath("edit/".$row->$primary)."?return_url=".urlencode(Request::fullUrl()) }}"><i class="fa fa-pencil-alt m-r-5"></i> {{trans("locale.action_edit_data")}}</a>
            @endif
            @if(MITBooster::isDelete() && $button_delete)
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete" onclick="selectedData({{$row->$primary}})"><i class="fa fa-trash m-r-5"></i> {{trans("locale.action_delete_data")}}</a>
            @endif
        </div>
    </div>