@push('bottom')
    <!-- Sweet-Alert  -->
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

	<script type="text/javascript">
        function numberToString(text, decimals) {
            text = text.toString().replace(/,/g, '');
            var number = parseFloat(Math.round(text * 100) / 100).toFixed(decimals);
            
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    		return parts.join(".");
            
            // return $.number(number, 2, '.', ',');
            // return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
	</script>
@endpush
@push('head')
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: 0.25rem !important; 
        }

        .select2-container .select2-selection--single {
            height: 30px
        }
    </style>
    <!--alerts CSS -->
    <link href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush