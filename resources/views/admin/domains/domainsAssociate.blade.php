@extends('layouts.admin')
@section('content')
@section('domains_select','active') 
<h5 class="text-left pl-3">Associate Domain</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url('admin/domains')}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{url('admin/domains/associate/store')}}" method="post">
		@csrf

		@if($domain_data ?? '')
			<div class="form-group">
				<label>Domain Name</label>
				<input type="text" name="domain_name" class="form-control" value="{{$domain_data[0]->domain_name}}" readonly>
				<input type="hidden" name="domain_id" class="form-control" value="{{$domain_id}}">
				@error('domain_name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
				@enderror
			</div>
		@endif
		@if($user_data ?? '')
			<div class="form-group">
				<label>User Name</label>
				<select class="form-control" name="user_name[]" multiple>
					<option value=" ">Select User</option>
						{{$i=0}}
						@foreach($user_data ?? ' ' as $data)
							<option value="{{$data->id}}" @if(old('user_name.'.$i)== $data->id) selected="selected" @endif>{{$data->name}} ({{$data->email}})</option>
						{{$i=$i+1}}
						@endforeach
				</select>
				@error('user_name')
					<span class="text-danger" role="alert">
						<strong>{{$message}}</strong>
					</span>
				@enderror
			</div>
		@endif
		<div class="form-group">
			<label>Type</label>
			<select class="form-control" name="type">
				<option value=" ">Select Type</option>
				<option value="1" @if(old('type')== "1") selected="selected" @endif>My Domain</option>
				<option value="2" @if(old('type')== "2") selected="selected" @endif>3rd Party Domain</option>
			</select>
			@error('type')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Associate Domain</button>
		</div>
	</form>	
</div>
@endsection