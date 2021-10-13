@extends('layouts.admin')
@section('content')		
@section('admin_users_select','active')	
<h5 class="text-left pl-3"> Add Admin </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('admin-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('admin-management.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{old('name')}}">
			@error('name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Email</label>
			<input type="email" name="email" class="form-control" value="{{old('email')}}">
			@error('email')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form-group">
			<label>Password</label>
			<input type="password" name="password" class="form-control">
			@error('password')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form-group">
			<label>Confirm Password</label>
			<input type="password" name="confirm_password" class="form-control">
			@error('confirm_password')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Admin</button>
		</div>
	</form>	
</div>
@endsection