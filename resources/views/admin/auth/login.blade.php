<!-- @extends('layouts.adminAuth') -->
@section('content')
 <div class="main-wrapper">
        <div class="login-signup-box d-flex justify-content-center w-100">
            <div class="form-box">
                <form action="{{url('/admin/login')}}" method="post">
                	@csrf
                    <div class="form-head">
                        <h1><img src="{{asset('images/logo.png')}}" alt="Logo" /></h1>
                        <h2>Login</h2>
						@if(session()->has('errorMessage'))
                            <span class="text-danger" role="alert">
                                <strong>{{session('errorMessage')}} </strong>
                            </span>
                        @endif  
                        @error('errorMessage')
                            <span class="text-danger" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @endif        
                    </div>                 
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="email" name="email" id="email"class="form-control" id="inputEmail" aria-describedby="emailHelp" placeholder="Enter Email" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
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
                    <div class="text-right">
						<a href="{{url('admin/forgot-password')}}" class="text-dark"> Forgot Password </a>
					</div>

 <!--                    <div class="g-recaptcha" data-sitekey="6Lc2j0EbAAAAACvnatwRVn3Du9O5rj865-8fDDew">

                    @error('g-recaptcha')
                        <span class="text-danger" role="alert">
                            <strong>Recaptch Is Required</strong>
                        </span>
                    @enderror
                    </div>

 -->
                    <div class="form-button text-center">
                        <button type="submit" class="btn btn-primary d-block w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection





