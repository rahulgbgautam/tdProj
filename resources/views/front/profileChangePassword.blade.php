@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
	<div class="admin-head d-flex align-items-center justify-content-between mb-3">
		<h4 class="content-head"> Change User Password </h4>
	    <div class=""><a href="{{url('view-profile')}}" class="btn btn-primary">Back</a></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form action="{{url('profile-change-password',$user->id)}}" method="post" enctype="multipart/form-data">
				@csrf
				@method('put')
				<div class="form-group">
					<label> Old Password </label>
					<input type="password" name="old_password" class="form-control" placeholder="Old Password">
					@error('old_password')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>	
				<div class="form-group">
					<label> New Password </label>
					<input type="password" name="new_password" class="form-control" placeholder="New Password">
					@error('new_password')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group">
					<label> New Confirm Password </label>
					<input type="password" name="new_confirm_password" class="form-control" placeholder=" New Confirm Password">
					@error('new_confirm_password')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>	
				<div class="text-left">
					<button type="submit" class="btn btn-success"> Change Password </button>
				</div>
			</form>			
		</div>
	</div>
</div>
@endsection
