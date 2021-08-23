@push('head')
    <style>
    .dropify-wrapper .dropify-message span.file-icon {
        font-size: 20px;
    }
    </style>
@endpush

@push('bottom')
    <script type="text/javascript">
        $('.upload').dropify();
    </script>
@endpush