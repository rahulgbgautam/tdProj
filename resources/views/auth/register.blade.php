@extends('layouts.master')
@section('content')
<div class="login-signup-box signup_section">
    <div class="container">
        <div class="form-box w-100">
            <form method="POST" action="{{ route('register') }}" class="mw-100">
                @csrf
                <div class="form-head">
                    <h2>{{ __('Create An Account') }}</h2>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="personal-information">
                        <div class="sub-head">
                            <h3>Personal Information</h3>
                        </div>
                        <div class="form-group">
                            <label for="inputName">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Enter name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputEmail">Email</label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Enter email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" autocomplete="new-password" placeholder="Password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputConfirmPassword">Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="new-password" placeholder=" Confirm Password">
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="domain-information">
                        <div class="sub-head">
                            <h3>Domain Information</h3>
                            <p>Please enter the domain you wish to scan from the portal. Trial users can only scan this domain from inside the portal.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="inputConfirmPassword">Industry</label>
                            <?php $data['industry_name'] = getIndustriesNew();?>                            
                            <div class="custom-dropdown">
                                <select class="form-control" name="industry" id="industry">
                                    <option value="">Select Industry</option>
                                    @foreach($data['industry_name'] as $key=>$value)
                                        <option value="{{$value->id}}" {{ ($value->id== old('industry', Session::get('industry_id') )) ? 'selected="selected"':'' }}
                                            >{{$value->industry_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('industry')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputConfirmPassword">Domain name</label>
                            <input id="domain_name" type="domain_name" class="form-control @error('domain_name') is-invalid @enderror" name="domain_name" value="{{ old('domain_name', Session::get('domain_name'))}}" autocomplete="new-password" placeholder="Enter Domain ">
                            @error('domain_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="checkbox-field">
                            <input type="checkbox" class="form-check-input @error('password_confirmation') is-invalid @enderror" id="inputCheck" name="accept_term_condition">
                            <label class="form-check-label" for="inputCheck">Accept <a href="{{route('term')}}"  target="_blank" class="light-green-text text-underline">T&C</a></label>
                            @error('accept_term_condition')
                                <br/><span class="text-danger">The T&C is required.</span>
                            @enderror
                        </div>
                        <div class="form-button text-center">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                            <!-- <p>Already have an account? <a href="{{ route('login') }}" class="pink-text">Sign In</a></p> -->
                        </div>
                    </div>
                    <div class="back-to-login">
                        <div class="sub-head">
                            <h3>Member Login</h3>
                        </div>
                        <div class="form-button text-center">
                            <p>Existing members sign in here.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">Member Sign In</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- <div class="form-image-box">
        <img src="{{ asset('images/signup-banner.png') }}" alt="Login Images" />
    </div> -->
</div>

<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
