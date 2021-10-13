@extends('layouts.admin')
@section('content')
@section('domains_select','active') 
<h5 class="text-left pl-3">Edit Domains</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url('admin/domains')}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{url('admin/domain/update',$domain->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Domain Name</label>
			<input type="text" name="domain_name" class="form-control" value="{{old('domain_name',$domain->domain_name)}}" readonly>
			@error('domain_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		@if($industry_data ?? '')
			<div class="form-group">
				<label>Industry</label>
				<select class="form-control" name="industry">
					<option value=" ">Select Industry</option>
					@foreach($industry_data ?? ' ' as $data)
						<option value="{{$data->id}}" @if($data->id == $domain->industry) selected @endif>{{$data->industry_name}}</option>
					@endforeach	
				</select>
				@error('industry')
					<span class="text-danger" role="alert">
						<strong>{{$message}}</strong>
					</span>
				@enderror
			</div>
		@endif
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
</div>
@endsection

