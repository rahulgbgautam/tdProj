@extends('layouts.admin')
@section('content')
@section('content_management_select','active')	
<h5 class="text-left pl-3">Edit Content</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('content-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	@if($contentManagement)
	<form action="{{url(route('content-management.update',$contentManagement->id))}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{ $contentManagement->title }}">
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>		
		<div class="form-group">
			<label>Sub Title</label>
			<input type="text" name="subtitle" class="form-control" value="{{$contentManagement->subtitle}}">
			@error('subtitle')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>		
		<div class="form-group">
			<label>Description</label>
			<textarea name="description" class="form-control" id="ckEditor">{{$contentManagement->description}}</textarea>
			@error('description')
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
