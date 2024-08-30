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
                    <h2 class="h2 g-color-white padding-mega-v3 " style="margin-bottom: 20px;color: white;margin-top: 20px;font-size: 1.5rem;">
                        <b>COMEX</b><br>Aurea logistics system</h2>
                    <div class="col g-px-5--lg align-self-center" style="color: white">
                        To get started, enter your login details:
                    </div>
                </header>
                <form class="login100-form validate-form" method="post" action="">
                    @csrf
                    <div class="wrap-input100 validate-input" data-validate="Insert username">
                        <input class="input100" type="text" id="username" name="username" placeholder="Username">
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Insert password">
                        <input class="input100" type="password" autocomplete="current-password" id="current-password" name="password" placeholder="Password" readonly
                               onfocus="this.removeAttribute('readonly');">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    <div class="mb-4 align-self-center text-right">
                        <a class="g-font-size-14 g-color-white" href="{{route('forgot-password')}}">Forgot password?</a>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="login100-form-btn btn-block">
                            Login
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>


@endsection
