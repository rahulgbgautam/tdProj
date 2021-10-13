@extends('layouts.admin')
@section('content')
<h5 class="text-left pl-3"> Change Admin Password </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url('admin/profile')}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($admin)
	<form action="{{url('admin/profile/change-password-process',$admin->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Old Password</label>
			<input type="password" name="old_password" class="form-control" value="{{old('old_password')}}">
			@error('old_password')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>New Password</label>
			<input type="password" name="new_password" class="form-control" value="{{old('new_password')}}">
			@error('new_password')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>New Confirm Password</label>
			<input type="password" name="new_confirm_password" class="form-control" value="{{old('new_confirm_password')}}">
			@error('new_confirm_password')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="text-left">
			<button type="submit" class="btn btn-success">Change Password</button>
		</div>
	</form>	
	@endif			
</div>
@endsection
