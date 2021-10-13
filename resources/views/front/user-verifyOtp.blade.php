@extends('layouts.app')
@section('content')
<div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-between">
            <div class="form-box">
                <form method="POST" action="{{ url('verify-otp-submit') }}">
                    @csrf
                    <div class="form-head">
                        <h1><a href="{{url('/')}}"><img src="{{ asset('images/logo.png') }}" alt="Logo" /></a></h1>
                        <h3>OTP Verification</h3>
                        @if(session()->has('otpError'))
                            <span class="text-danger" role="alert">
                                <strong> {{ session('otpError') }} </strong>
                            </span>
                        @endif 
                        @if(session()->has('otpSuccess'))
                            <span class="text-success" role="alert">
                                <strong> {{ session('otpSuccess') }} </strong>
                            </span>
                        @endif 
                    </div>


                    <!-- <div class="form-group">
                        <label for="inputOtp">OTP</label>
                        <input type="text" name="otp" class="form-control">
                        @error('otp')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div> -->

                    <div class="form-group">
                        <label for="email">OTP<span class="mandatory">*</span></label>
                        <input type="hidden" name="code" value="{{$code}}">
                        <input name="otp" type="text" autocomplete="off" class="form-control" id="otp" aria-describedby="" placeholder="Enter OTP" maxlength="4" value="">
                        <span class="text-danger">
                         {{ $errors->first('otp') }}
                     </span>
                    </div>
                     <div class="col-md-12 col-sm-12">
                        <div class="form-button text-center">
                            <button type="submit" class="btn btn-primary">Verify OTP</button>
                            <p><i><a href="{{ url('resend-otp/'.$code) }}">Resend OTP</a></i></p>
                        </div>
                    </div>   
                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    </div>
@endsection


