@extends('layouts.admin')
@section('content')	
@section('features_management_select','active')		
<h5 class="text-left pl-3">Add Feature</h5>
@if(session()->has('Feature'))
<span class="text-danger" role="alert">
	<strong>{{session('Feature')}}</strong>
</span>
@endif
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('features-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>  
<div class="col-6">
	<form action="{{route('features-management.store')}}" method="post" enctype="multipart/form-data">
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
			<label>Description</label>
			<textarea  name="description" class="form-control">{{old('description')}}</textarea>
			@error('description')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form-group">
			<label>Select Icon</label>
			<input type="file" name="icon_image" class="form-control-file"  value="{{old('icon_image')}}">
			@error('icon_image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Feature</button>
		</div>
	</form>	
</div>
@endsection