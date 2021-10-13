@extends('layouts.master')
@section('content')
<div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-between">
            <div class="form-box">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-head">
                        <h2>{{ __('Forgot Password') }}</h2>
                        @if(session()->has('successmessage'))
                            <div class="container alert alert-success">
                            {{ session()->get('successmessage') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                        <p>Already have an account? <a href="{{ route('login') }}" class="pink-text">Sign In</a></p>
                    </div>
                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    </div>
@endsection