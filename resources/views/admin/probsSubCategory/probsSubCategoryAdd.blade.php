@extends('layouts.admin')
@section('content')
@section('probs_sub_category_select','active') 
<h5 class="text-left pl-3">Add Sub Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-sub-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{route('probs-sub-category.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Category List</label>
			<select class="form-control" name="category_id">
				<option value=" ">Select Category</option>
				@if($categoryList ?? '')
					@foreach($categoryList ?? '' as $Data)
						<option value="{{$Data->id}}">{{$Data->category_name}}</option>
					@endforeach
				@endif
			</select>
			@error('category_id')
				<span class="text-danger" role="alert">
					<strong>The category is required</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Name</label>
			<input type="text" name="sub_category_name" class="form-control" value="{{old('sub_category_name')}}">
			@error('sub_category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Display Name</label>
			<input type="text" name="sub_category_display_name" class="form-control" value="{{old('sub_category_display_name')}}">
			@error('sub_category_display_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Pass Message</label>
			<textarea name="pass_message" class="form-control">{{old('pass_message')}}</textarea>
			@error('pass_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Fail Message</label>
			<textarea name="fail_message" class="form-control">{{old('fail_message')}}</textarea>
			@error('fail_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Remediation Message</label>
			<textarea name="remediation_message" class="form-control"> {{old('remediation_message')}}</textarea>
			@error('remediation_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Pass Code</label>
			<textarea name="pass_code" class="form-control">{{old('pass_code')}}</textarea>
			@error('pass_code')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Fail Code</label>
			<textarea name="fail_code" class="form-control">{{old('fail_code')}}</textarea>
			@error('fail_code')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Max Score</label>
			<input type="text" name="max_score" class="form-control" value="{{old('max_score')}}">
			@error('max_score')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Sub Category</button>
		</div>
	</form>	
</div>
@endsection