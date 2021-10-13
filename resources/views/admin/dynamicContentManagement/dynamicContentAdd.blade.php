@extends('layouts.admin')
@section('content')
@section('dynamic_content_select','active') 
<h5 class="text-left pl-3">Add Content</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('dynamic-content.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{route('dynamic-content.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		 <div class="form-group">
			<label>Menu Name</label>
			<input type="text" name="menu_name" class="form-control" value="{{old('menu_name')}}">
			@error('menu_name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
	    	<label>Menu Type</label>
	    	<select class="form-control" name="menu" required>
	    		<option value="products" @if(old('menu')== "products") selected="selected" @endif>Products</option>
	    		<option value="resources" @if(old('menu')== "resources") selected="selected" @endif>Resources</option>
	    	</select>
	    </div>
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control"  value="{{old('title')}}">
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Title</label>
			<input type="text" name="subtitle" class="form-control" value="{{old('subtitle')}}">
			@error('subtitle')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Description</label>
			<textarea name="description" class="form-control" id="ckEditor">{{old('description')}}</textarea>
			@error('description')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Select Image</label>
			<input type="file" name="file" class="form-control-file"  value="{{old('file')}}">
			@error('file')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Add Content</button>
		</div>
	</form>	
</div>
@endsection
