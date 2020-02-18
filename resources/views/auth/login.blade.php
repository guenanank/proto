@extends('auth.layout')
@section('title', 'Login')

@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <br />
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                            </div>
                            <form method="POST" action="{{ route('login') }}" class="user">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address...">
                                </div>
                                <!--div class="form-group">
                                    <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                </div-->

                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" aria-describedby="password-addon">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="password-addon">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input name="remember" type="checkbox" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    {{ __('Login') }}
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                                @endif
                            </div>

                            <div class="text-center">
                                @if (Route::has('register'))
                                <a class="small" href="{{ route('register') }}">
                                    {{ __('Create an Account!') }}
                                </a>
                                @endif
                            </div>
                            <br /><br /><br /><br /><br />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
