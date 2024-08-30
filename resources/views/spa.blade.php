<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/style.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <app id="app" :user='{!! json_encode($current_user) !!}' :roles='{!! json_encode($roles) !!}'></app>

<script>
  window.api_token = '{{$current_user->api_token}}'
  window.app_version = '{{config("app.version")}}'
</script>
<script src="{{ mix('js/main.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
$('#ui-view').ajaxLoad();
$(document).ajaxComplete(function() {
  Pace.restart()
});
</script>
</body>
</html>