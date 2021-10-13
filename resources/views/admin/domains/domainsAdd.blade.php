@extends('layouts.admin')
@section('content')	
@section('domains_select','active')		
<h5 class="text-left pl-3">Add Domains</h5>
@if(session()->has('Feature'))
<span class="text-danger" role="alert">
	<strong>{{session('Feature')}}</strong>
</span>
@endif
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url('admin/domains')}}" class="btn btn-primary">Back</a></div>
</div>  
<div class="col-6">
	<form action="{{url('admin/domains/store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Domains</label>
			<textarea  name="domains" class="form-control" style="height: 250px;">{{old('domains')}}</textarea>
			@error('domains')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	        @if($domain_not_exists = Session::get('domain_not_exists'))
			  <p class="text-danger">Domains does not exists</p>
			  @foreach($domain_not_exists as $domain)
			  		<p class="text-danger">{{$domain}}</p>
			  @endforeach
			@endif
			@if($domain_presents = Session::get('domain_presents'))	
			  @foreach($domain_presents as $domain)
			  		<p class="text-danger">{{$domain}}</p>
			  @endforeach
			  <p class="text-danger">Domain Already Presents</p>
			@endif
	        <label class="text-danger">Please enter one domain per row</label>
	        @if($industry_data ?? '')
				<div class="form-group">
					<label>Industry</label>
					<select class="form-control" name="industry">
						<option value=" ">Select Industry</option>
						@foreach($industry_data ?? ' ' as $data)
							<option value="{{$data->id}}">{{$data->industry_name}}</option>
						@endforeach	
					</select>
					@error('industry')
						<span class="text-danger" role="alert">
							<strong>{{$message}}</strong>
						</span>
					@enderror
				</div>
			@endif
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Domains</button>
		</div>
	</form>	
</div>
@endsection