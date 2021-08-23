<div class="modal custom-modal fade" id="delete" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ trans('locale.delete_title_confirm') }}</h3>
                    <p>{{ trans('locale.delete_description_confirm') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            {{-- $row->$primary --}}
                            <a href="javascript:void(0);" class="btn btn-primary continue-btn deleteLink" onclick="deleteData();">{{ trans('locale.confirmation_yes') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ trans('locale.confirmation_no') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('bottom')
<script>
    var selected_id = 0;

    function selectedData($id) {
        selected_id = $id
    }

    function deleteData() {
        if(selected_id != 0) {
            $('.deleteLink').attr("href", '{{ MITBooster::mainpath("delete") }}/'+selected_id);
        } else {
            $('#form-table').submit();
        }
    }
</script>
@endpush
