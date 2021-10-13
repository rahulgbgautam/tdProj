@extends('layouts.admin')
@section('content')
@section('banner_management_select','active')			
<h5 class="text-left pl-3"> Add Banner   </h5>
@if(session()->has('password_not_match'))
<span class="text-danger" role="alert">
	<strong>{{session('password_not_match')}}</strong>
</span>
@endif
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('banner-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>  
<div class="col-8">
	<form action="{{route('banner-management.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{old('title')}}">
			@error('title')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Subtitle</label>
			<input type="text" name="subtitle" class="form-control" value="{{old('subtitle')}}">
			@error('subtitle')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Description</label>
			<textarea  name="description" class="form-control">{{old('description')}}</textarea>
			@error('description')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	    </div>
		<div class="form-group">
			<label>Select Banner</label>
			<input type="file" name="banner_image" class="form-control-file"  value="{{old('banner_image')}}">
			@error('banner_image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	        <label id="baner_selection_text" class="text-danger">Select Image (Banner Size: 1400PX, 400PX & Inside Banner Size: 500PX, 300PX) for Best Resolution</label>
		</div>
		<div class="form-group">
	    	<label>Banner Type</label>
	    	<select class="form-control" name="banner_type" id="baner_types" required>
	    		<option value="main_banner">Main Banner</option>
	    		<option value="inside_banner">Inside Banner</option>
	    	</select>
	    </div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Banner</button>
		</div>
	</form>	
</div>
@endsection