@extends('layouts.admin')
@section('content')
@section('industry_select','active') 
<h5 class="text-left pl-3">Edit Industry</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('industry.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($industry)
	<form action="{{route('industry.update',$industry->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="industry_name" class="form-control" value="{{$industry->industry_name}}">
			<input type="hidden" name="industry_name_old" class="form-control" value="{{$industry->industry_name}}">
			@error('industry_name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($industry->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($industry->status == "Inactive") selected="selected" @endif>Inactive</option>
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
