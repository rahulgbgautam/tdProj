@extends('layouts.app')
@section('content')
<div class="main-wrapper">
        <div class="login-signup-box thankyou-page d-flex justify-content-between">
            <div class="form-box">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-head">
                        <h1><a href="{{url('/')}}"><img src="{{ asset('images/logo.png') }}" alt="Logo" /></a></h1>
                        <div class="cotnent">
                        	<img src="{{ asset('img/blue-checked-icon.svg')}}" alt="Checked Icon">
                        	<p>You can now login into your account as your account is already verified.</p>
                        </div>
                        @if(session()->has('successmessage'))
                            <div class="container alert alert-success">
                            {!! session()->get('successmessage') !!}
                            </div>
                        @endif
                        <!-- <h2>Login</h2> -->
                    </div>
                    <div class="form-button text-center">
                        <a href="{{ route('login') }}" class="btn btn-primary">Sign In?</a>
                    </div>
                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    </div>
@endsection
