@extends('layouts.admin')
@section('content')
@section('setting_select','active') 
<h5 class="text-left pl-3">Edit Settings</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('settings.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($data)
	<form action="{{route('settings.update',$data->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Title</label><br />
			<input type="text" name="title" class="form-control" value="{{ucwords(str_replace('_', ' ',$data->title))}}" readonly>
			@error('title')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Value</label>
			<input type="text" name="value" class="form-control" value="{{$data->value}}">
			@error('value')
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
