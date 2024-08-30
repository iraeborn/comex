<html ng-controller="LayoutCtrl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('dist/img/favicon.png') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/css/main.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.3.2/dist/cdn.min.js"></script>
    <script src="/dist/js/liveware.js"></script>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.60/inputmask/jquery.inputmask.js"></script>--}}
    <script src="{{ asset('dist/js/mask.js') }}"></script>
</head>

<body>
@include('sweetalert::alert')
@yield('body')

<script id='modelJson' type='application/json'>
    {model}
</script>


<script src="{{ asset('dist/js/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('dist/js/angular.1.2.13.min.js') }}"></script>
<script src="{{ asset('dist/js/assets/app.js') }}"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        App.init();
    });


    var ops = {
        'html': true,
        content: function () {
            return $('#content').html();
        }
    };

    $(function () {
        $('#divPublicacoes').popover(ops);
        $('#divRede').popover(ops);
        $('#divAvaliacao').popover(ops);
    });

</script>

<script src="{{ asset('dist/js/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('dist/js/animsition/js/animsition.min.js') }}"></script>
<script src="{{ asset('dist/js/bootstrap/js/popper.js') }}"></script>
<script src="{{ asset('dist/js/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('dist/js/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('dist/js/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('dist/js/countdowntime/countdowntime.js') }}"></script>
<script src="{{ asset('dist/js/main.js') }}"></script>
</body>
</html>

