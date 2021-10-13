@extends('layouts.master')
@section('content')
<div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-between">
            <div class="form-box">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <div class="form-head">
                        <h2>{{ __('Reset Password') }}</h2>
                        @if(session()->has('successmessage'))
                            <div class="container alert alert-success">
                            {{ session()->get('successmessage') }}
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    </div>
@endsection