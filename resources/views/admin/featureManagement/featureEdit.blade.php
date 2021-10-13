@extends('layouts.admin')
@section('content')
@section('features_management_select','active')		
<h5 class="text-left pl-3"> Edit Feature </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('features-management.index'))}}" class="btn btn-primary">Back</a></div>
</div> 
<div class="col-6">
	@if($Feature)
	<form action="{{route('features-management.update',$Feature->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{$Feature->title}}">
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>Description</label>
			<textarea name="description" class="form-control">{{$Feature->discription}}</textarea>
			@error('description')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($Feature->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if( $Feature->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group mb-0">
			<label>Previous Icon</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($Feature->icon_image)}}" style="width: 100px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select New Icon</label>
			<input type="file" name="icon_image" class="form-control-file">
			<input type="hidden" name="old_icon_image" value="{{$Feature->icon_image}}">
		</div>
		@error('icon_image')
		{{ $message }}
		@enderror
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update Feature</button>
		</div>
	</form>	
	@endif			
</div>
@endsection