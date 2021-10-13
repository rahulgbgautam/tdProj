@extends('layouts.admin')
@section('content')
@section('probs_category_select','active') 
<h5 class="text-left pl-3">Add Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('probs-category.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Category Name</label>
			<input type="text" name="category_name" class="form-control" value="{{old('category_name')}}">
			@error('category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade A</label>
			<textarea name="Grade_A" class="form-control">{{old('Grade_A')}}</textarea>
			@error('Grade_A')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade B</label>
			<textarea name="Grade_B" class="form-control">{{old('Grade_B')}}</textarea>
			@error('Grade_B')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade C</label>
			<textarea name="Grade_C" class="form-control">{{old('Grade_C')}}</textarea>
			@error('Grade_C')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade D</label>
			<textarea name="Grade_D" class="form-control">{{old('Grade_D')}}</textarea>
			@error('Grade_D')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade E</label>
			<textarea name="Grade_E" class="form-control">{{old('Grade_E')}}</textarea>
			@error('Grade_E')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Category</button>
		</div>
	</form>	
</div>
@endsection