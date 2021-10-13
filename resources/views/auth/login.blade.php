@extends('layouts.master')
@section('content')
<!-- <div class="main-wrapper"> -->
        <div class="login-signup-box d-flex justify-content-between">
            <div class="form-box">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-head">
                        <h2>{{ __('Sign In') }}</h2>
                        @if(session()->has('successmessage'))
                            <span class="successmessage">
                            {!! session()->get('successmessage') !!}
                            </span>
                        @endif
                        @if(session()->has('error'))
                               <span class="dangermessage" role="alert">
                                   <strong> {{ session('error') }} </strong>
                               </span>
                           @endif
                           @if(session()->has('success'))
                               <span class="successmessage" role="alert">
                                   <strong> {{ session('success') }} </strong>
                               </span>
                           @endif
                        <!-- <h2>Login</h2> -->
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Enter email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="checkbox-field pb-3">
                        <input type="checkbox" class="form-check-input" id="inputCheck">
                        <label class="form-check-label" for="inputCheck">Keep me logged in.</label>
                    </div>
                    <?php $recaptcha = getGeneralSetting('google_recapcha'); ?>
                    @if($recaptcha == 'yes')
                        @if(config('services.recaptcha.key'))
                            <div class="form-group">
                                <div class="g-recaptcha" 
                                     data-sitekey="{{config('services.recaptcha.key')}}">
                                </div>
                            </div>
                            @error('g-recaptcha-response') 
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                    @endif
                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary">Sign In</button>
                        <p>Don’t have an account? <a href="{{ route('register') }}" class="pink-text">Sign Up</a></p>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    <!-- </div> -->
@endsection
<script src='https://www.google.com/recaptcha/api.js'></script>
