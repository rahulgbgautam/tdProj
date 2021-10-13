@extends('layouts.admin')
@section('content')
@section('probs_category_select','active') 
<h5 class="text-left pl-3">Edit Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('probs-category.update',$probsCategory->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Category Name</label>
			<input type="text" name="category_name" class="form-control" value="{{old('category_name',$probsCategory->category_name)}}">
			<input type="hidden" name="category_name_old" class="form-control" value="{{$probsCategory->category_name}}">
			@error('category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade A</label>
			<textarea name="Grade_A" class="form-control">{{$probsCategory->grade_a}}</textarea>
			@error('Grade_A')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade B</label>
			<textarea name="Grade_B" class="form-control">{{$probsCategory->grade_b}}</textarea>
			@error('Grade_B')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade C</label>
			<textarea name="Grade_C" class="form-control">{{$probsCategory->grade_c}}</textarea>
			@error('Grade_C')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade D</label>
			<textarea name="Grade_D" class="form-control">{{$probsCategory->grade_d}}</textarea>
			@error('Grade_D')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Grade E</label>
			<textarea name="Grade_E" class="form-control">{{$probsCategory->grade_e}}</textarea>
			@error('Grade_E')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($probsCategory->status == "Active") selected="selected" @endif> Active </option>
				<option value="Inactive" @if($probsCategory->status == "Inactive") selected="selected" @endif> Inactive </option>
			</select>
			@error('status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
</div>
@endsection

