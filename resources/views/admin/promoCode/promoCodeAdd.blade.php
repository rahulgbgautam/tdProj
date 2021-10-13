@extends('layouts.admin')
@section('content')		
@section('promo_code_select','active')	
<h5 class="text-left pl-3"> Add Promo Code </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('promo-code.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('promo-code.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Promo Code</label>
			<input type="text" name="promo_code" class="form-control" value="{{old('promo_code')}}">
			<label class="text-danger">Please enter promo code without space</label>
			@error('promo_code')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Available Quantity</label>
			<input type="text" name="available_quantity" class="form-control" value="{{old('available_quantity')}}">
			@error('available_quantity')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Expiry Date</label>
			<input type="date" name="expiry_date" class="form-control" value="{{old('expiry_date')}}" id="expiryDate">
			@error('expiry_date')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Discount (In Percent)</label>
			<input type="text" name="discount" class="form-control" value="{{old('discount')}}">
			@error('discount')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Promo Code</button>
		</div>
	</form>	
</div>
@endsection