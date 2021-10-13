@extends('layouts.adminAuth')
@section('content')
 <div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-center w-100">
            <div class="form-box">
                <form action="{{url('admin/reset-password-process',$id)}}" method="post">
                	@csrf
                    <div class="form-head">
                        <h1><img src="{{asset('images/logo.png')}}" alt="Logo" /></h1>
                        <h2>Reset Password</h2>
                    </div>                 
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" value="{{old('password')}}">
                        @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="inputPassword" placeholder="Password" value="{{old('confirm_password')}}">
                        @if(session()->has('errorMessage'))
                            <span class="text-danger" role="alert">
                                <strong>{{session('errorMessage')}}</strong>
                            </span>
                        @endif
                        @error('confirm_password')
                            <span class="text-danger" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary d-block w-100">Update</button>
                    </div>
                    <div class="text-center mt-3">
						<a href="{{url('admin')}}" class="text-dark">Already Have Account<b>Login</b></a>	
					</div>
                </form>
            </div>
        </div>
    </div>
@endsection





