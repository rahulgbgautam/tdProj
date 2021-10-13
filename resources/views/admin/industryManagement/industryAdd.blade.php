@extends('layouts.admin')
@section('content')		
@section('industry_select','active')	
<h5 class="text-left pl-3">Add Industry</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('industry.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('industry.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="industry_name" class="form-control" value="{{old('industry_name')}}">
			@error('industry_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Industry</button>
		</div>
	</form>	
</div>
@endsection