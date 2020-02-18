@extends('auth.layout')
@section('title', 'Register')

@section('content')
<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
            <div class="col-lg-7">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                    </div>
                    <form class="user" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <input id="fullname" type="text" class="form-control form-control-user" name="name" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" aria-describedby="password-addon">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="password-addon">
                                        <i class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            {{ __('Register Account') }}
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
                        <a class="small" href="{{ route('login') }}">
                            {{ __('Already have an account? Login!') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
