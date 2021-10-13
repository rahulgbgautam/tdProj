@extends('layouts.admin')
@section('content')
@section('admin_users_select','active')	
<h5 class="text-left pl-3"> Edit Admin </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('admin-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($admin)
	<form action="{{route('admin-management.update',$admin->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{$admin->name}}">
			@error('name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Email</label>
			<input type="email" name="email" class="form-control" value="{{$admin->email}}" readonly>
			@error('email')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($admin->status == "Active") selected="selected" @endif> Active </option>
				<option value="Inactive" @if($admin->status == "Inactive") selected="selected" @endif> Inactive </option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
	@endif			
</div>
@endsection
