@extends('layouts.app')

@section('content')
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white py-3">
                    <h4 class="m-0">{{ __('Login') }}</h4>
                  </div>

                <div class="card-body">
                    <br>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-green">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="text-center" style="margin-top: 20px;">
                        <a href="{{ route('googlelogin') }}">
                        <button onclick="{{ route('googlelogin') }}" style="background-color: #fff; border: 1px solid #ddd; border-radius: 4px; color: #444; font-size: 16px; font-weight: 1000; height: 40px; line-height: 40px; padding: 0 16px; text-align: center;">
                            <img src="https://img.icons8.com/color/16/000000/google-logo.png" alt="Google Logo" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            Login with Google
                        </button>
                        </a>
                    </div>

                    <div class="text-center" style="margin-top: 10px;">
                        <a href="{{ route('googlelogin') }}">

                        <button style="background-color: #333; border: 1px solid #333; border-radius: 4px; color: #fff; font-size: 16px; font-weight: 1000; height: 40px; line-height: 40px; padding: 0 16px; text-align: center;">
                            <img src="https://img.icons8.com/ios-filled/16/ffffff/github.png" alt="GitHub Logo" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            Login with GitHub
                        </button>
                        </a>
                    </div>
                    <br>

                    
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
   
</div>
@endsection
