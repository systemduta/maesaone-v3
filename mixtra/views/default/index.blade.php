@extends('mixtra::layouts.app')

@section('title', $module->name)

@push('head')
@endpush

@section('content')
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">{{ $module->name }} Module</h3>
                </div>
                <div class="col-auto float-right ml-auto">
                    @if(MITBooster::getCurrentMethod() == 'getIndex')
                        @if($button_export)
                            <div class="btn-group">
                                <button type="button"
                                    class="btn waves-effect waves-light btn-sm btn-warning btn-export dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-upload"></i> <span class="sm-button-action">{{trans("locale.button_export")}}</span>
                                </button>
                                <div class="dropdown-menu">
{{--                                    <a class="dropdown-item" href="{{ MITBooster::mainpath('export-data').'?format=pdf&'.http_build_query(Request::all()) }}" title="{{trans('locale.pdf')}}"><i class="fa fa-file-pdf"></i> {{trans('locale.export_pdf')}} </a>--}}
                                    <a class="dropdown-item" href="{{ MITBooster::mainpath('export-data').'?format=xlsx&'.http_build_query(Request::all()) }}" title="{{trans('locale.xlsx')}}"><i class="fa fa-file-excel"></i> {{trans('locale.export_xlsx')}} </a>
                                    <a class="dropdown-item" href="{{ MITBooster::mainpath('export-data').'?format=xls&'.http_build_query(Request::all()) }}" title="{{trans('locale.xls')}}"><i class="fa fa-file-excel"></i> {{trans('locale.export_xls')}} </a>
                                    <a class="dropdown-item" href="{{ MITBooster::mainpath('export-data').'?format=csv&'.http_build_query(Request::all()) }}" title="{{trans('locale.csv')}}"><i class="fa fa-file-csv"></i> {{trans('locale.export_csv')}} </a>
                                </div>
                            </div>
                        @endif
                        @if($button_reload)
                            <a href="{{ MITBooster::mainpath().'?'.http_build_query(Request::all()) }}"
                                id='btn_show_data'
                                class="btn btn-sm btn-success"
                                title="{{trans('locale.action_reload_data')}}">
                                <i class="fa fa-sync-alt"></i> {{trans('locale.action_reload_data')}}
                            </a>
                        @endif
                        @if($button_add && MITBooster::isCreate())
                            <a href="{{ MITBooster::mainpath('add').'?return_url='.urlencode(Request::fullUrl()) }}"
                                id='btn_add_data'
                                class="btn btn-sm btn-info"
                                title="{{trans('locale.action_add_data')}}">
                                <i class="fa fa-plus-circle"></i> {{trans('locale.action_add_data')}}
                            </a>
                        @endif
                    @endif

                </div>
            </div>
        </div>
        <!-- /Page Header -->

        @if (Session::has('message'))
        <?php
            $message_type = Session::pull('message_type');
            $message = Session::pull('message');
        ?>
        <div class="alert alert-{{ $message_type }} alert-dismissible fade show">
            <div class="alert-body">
                <h3 class="text-{{ $message_type }}">
                    <i class="fa fa-{{ $message_type }}"></i> {{ $message_type }}
                </h3>
                {{ $message }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif

        <div class="row my-2">
            <div class="col-sm-4">
                @if($button_bulk_action && (($button_delete && MITBooster::isDelete()) || $button_selected) )
                <div class="btn-group selected-action">
                    <button type="button"
                        class="btn waves-effect waves-light btn-sm btn-primary dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-check-square"></i>
                        {{trans("locale.button_selected_action")}}
                    </button>
                    <div class="dropdown-menu">
                        @if($button_delete && MITBooster::isDelete())
                        <a class="dropdown-item" href="javascript:void(0)" data-name="delete" data-toggle="modal" data-target="#delete" onclick="deleteSelected()">
                            <i class="fa fa-trash"></i> {{trans("locale.action_delete_selected")}}
                        </a>
                        @endif
                        @if($button_selected)
                            @foreach($button_selected as $button)
                                <a class="dropdown-item" href="javascript:void(0)"
                                    data-name='{{$button["name"]}}' title='{{$button["label"]}}'>
                                    <i class="fa fa-{{$button['icon']}}"></i> {{$button['label']}}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-8 text-right">
                <div class="btn-group">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm input-fulltext" placeholder="{{trans('locale.filter_search')}}" name="q" value="{{ Request::get('q') }}"/>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm waves-effect waves-light btn-primary" onclick="setFullText()">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button"
                                class="btn btn-sm waves-effect waves-light btn-primary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $limit }}</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="setLimit(5)">10</a>
                                <a class="dropdown-item" href="#" onclick="setLimit(10)">25</a>
                                <a class="dropdown-item" href="#" onclick="setLimit(20)">50</a>
                                <a class="dropdown-item" href="#" onclick="setLimit(100)">100</a>
                                <a class="dropdown-item" href="#" onclick="setLimit(200)">200</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            @include("mixtra::default.table")
        </div>
        <div class="row">
            <div class="col-sm-8 mt-1 sm-footer">
            {!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}
            </div>
            <div class="col-sm-4 mt-1 text-right sm-footer" style="padding-top: 7px;">
                <p>
                    <?php
                    $from = $result->count() ? ($result->perPage() * $result->currentPage() - $result->perPage() + 1) : 0;
                    $to = $result->perPage() * $result->currentPage() - $result->perPage() + $result->count();
                    $total = $result->total();
                    ?>
                    {{ trans("locale.filter_rows_total") }} : {{ $from }} {{ trans("locale.filter_rows_to") }} {{ $to }}
                    {{ trans("locale.filter_rows_of") }} {{ $total }}</p>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Delete Modal -->
    @include("mixtra::default.delete")
    <!-- /Delete Modal -->

</div>
<!-- /Page Wrapper -->
@endsection

@push('bottom')
<script type="text/javascript">

    function setLimit(limit){
        var url = '{{ MITBooster::urlFullText("limit") }}&limit='+limit;
        url = url.replace('&amp;','&');
        $(location).attr('href', url);
    }

    function setFullText() {
        var q = $('.input-fulltext').val();
        var url = '{{ MITBooster::urlFullText("q") }}&q='+q;
        url = url.replace('&amp;','&');
        $(location).attr('href', url);
    }

    $(function () {
        $('.input-fulltext').keypress(function (e) {
            if (e.which == 13) {
                setFullText();
                return false;
            }
        });
    });
</script>
@endpush

