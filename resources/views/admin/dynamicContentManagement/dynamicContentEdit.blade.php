@extends('layouts.admin')
@section('content')
@section('dynamic_content_select','active') 	
<h5 class="text-left pl-3">Edit Content</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('dynamic-content.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	@if($dynamicContent)
	<form action="{{url(route('dynamic-content.update',$dynamicContent->id))}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Menu Name</label>
			<input type="text" name="menu_name" class="form-control" value="{{ $dynamicContent->menu_name }}">
			@error('menu_name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
	    	<label>Menu Type</label>
	    	<select class="form-control" name="menu" required>
	    		<option value="products" @if($dynamicContent->menu == "Products") selected="selected" @endif>Products</option>
	    		<option value="resources"  @if($dynamicContent->menu == "Resources") selected="selected" @endif>Resources</option>
	    	</select>
	    	@error('menu')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
	    </div>
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{ $dynamicContent->title}}">
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>Sub Title</label>
			<input type="text" name="subtitle" class="form-control" value="{{ $dynamicContent->subtitle }}">
			@error('subtitle')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Description</label>
			<textarea name="description" class="form-control" id="ckEditor">{{$dynamicContent->description}}</textarea>
			@error('description')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group mb-0">
			<label>Previous Image</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($dynamicContent->image)}}" style="width: 100px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select Image</label>
			<input type="file" name="file" class="form-control-file">
			<input type="hidden" name="old_image" value="{{$dynamicContent->image}}">
			@error('file')
				{{ $message }}
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($dynamicContent->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($dynamicContent->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('status')
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
