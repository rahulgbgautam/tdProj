@extends('layouts.admin')
@section('content')
@section('banner_management_select','active')			
<h5 class="text-left pl-3"> Edit Banner </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('banner-management.index'))}}" class="btn btn-primary">Back</a></div>
</div> 
<div class="col-8">
	@if($Banner)
	<form action="{{route('banner-management.update',$Banner->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{$Banner->title}}">
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Subtitle</label>
			<input type="text" name="subtitle" class="form-control" value="{{$Banner->subtitle}}">
			@error('subtitle')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>		
		<div class="form-group">
			<label>Description</label>
			<textarea name="description" class="form-control" value="{{$Banner->discription }}">{{ $Banner->discription }}</textarea>
			@error('description')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($Banner->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive"  @if( $Banner->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group mb-0">
			<label>Previous Banner</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($Banner->banner_image)}}" style="width: 850px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select New Banner</label>
			<input type="file" name="banner_image" class="form-control-file">
			<input type="hidden" name="old_banner_image" value="{{$Banner->banner_image}}">
			@error('banner_image')
				{{ $message }}
			@enderror
			<label id="baner_selection_text" class="text-danger">Select Image (Banner Size: 1400PX, 400PX & Inside Banner Size: 500PX, 300PX) for Best Resolution</label>
		</div>
		<div class="form-group">
	    	<label> Banner Type </label>
	    	<select class="form-control" name="banner_type" id="baner_typess" required>
	    		<option value="main_banner" @if($Banner->banner_type == "main_banner") selected @endif>Main Banner</option>
	    		<option value="inside_banner" @if($Banner->banner_type == "inside_banner") selected @endif>Inside Banner</option>
	    	</select>
	    </div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update Banner</button>
		</div>
	</form>	
	@endif			
</div>
@endsection
