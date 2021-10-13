@extends('layouts.adminAuth')
@section('content')

 <div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-center w-100">
            <div class="form-box">
                <form action="{{url('admin/forgot-password')}}" method="post">
                	@csrf
                    <div class="form-head">
                        <h1><img src="{{asset('images/logo.png')}}" alt="Forgot Password" /></h1>
                        <h2>Forgot Password</h2>
                    </div>                 
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="email" name="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" placeholder="Enter email" value="{{old('email')}}">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @if(session()->has('errorMessage'))
                            <span class="text-danger" role="alert">
                                <strong> {{ session('errorMessage') }} </strong>
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
						<a href="{{url('admin')}}" class="text-dark"> Login</a>	
					</div>

                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary d-block w-100">Send Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection





