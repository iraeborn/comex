@extends('layouts.base')
<title>{{ config('app.name') }}</title>
@section('body')
{{--    @if ($message)--}}
{{--        <p class="alert alert-danger">{{ $message }}</p>--}}
{{--    @endif--}}
    <div class="limiter background">
        <div class="container-login100">
            <div class="wrap-login100">
                <header class="text-center mb-4">
                    <!-- <img src="{{asset('img/logo_mini.webp')}}" class="img-responsive center-block login100-form-logo"> -->
                    <h2 class="h2 g-color-white padding-mega-v3 " style="margin-bottom:50px; color: white">
                        <b>POPCORN</b> System</h2>
                    <div class="col g-px-5--lg align-self-center" style="color: white">
                        To recover your password, enter your username or email:
                    </div>
                </header>
                <form class="login100-form validate-form" method="post" action="">
                    @csrf
                    <div class="wrap-input100 validate-input" data-validate="Insert username">
                        <input class="input100" type="text" id="username" name="username" placeholder="Username">
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="login100-form-btn btn-block">
                            Send
                        </button>
                    </div>

                </form>
                <div class="mb-4">
                    <a class="btn btn-outline-dark login100-form-reset-btn btn-block" href="{{route('home.get')}}">
                        Back to Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>


@endsection
