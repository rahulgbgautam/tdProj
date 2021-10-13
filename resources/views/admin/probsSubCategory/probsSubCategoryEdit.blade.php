@extends('layouts.admin')
@section('content')
@section('probs_sub_category_select','active') 
<h5 class="text-left pl-3"> Edit Sub Category </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-sub-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{route('probs-sub-category.update',$probsSubCategory->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Category List</label>
			<select class="form-control" name="category_id">
				@if($selectedCategory ?? '')
					@foreach($selectedCategory ?? '' as $Data)
						<option value="{{$Data->id}}" class="selected">{{$Data->category_name}}</option>
						{{ $selectedCategory = $Data->category_name }}
					@endforeach
				@endif
				@if($categoryList ?? '')                    
                    @foreach($categoryList ?? '' as $Data)        
                    	@if($Data->category_name != $selectedCategory)
                            <option value="{{$Data->id}}">{{$Data->category_name}}</option>
                        @endif                  
                    @endforeach                 
                @endif
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Name</label>
			<input type="text" name="sub_category_name" class="form-control" value="{{$probsSubCategory->sub_category_name}}">
			@error('sub_category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Display Name</label>
			<input type="text" name="sub_category_display_name" class="form-control" value="{{$probsSubCategory->sub_category_display_name}}">
			@error('sub_category_display_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Pass Message</label>
			<textarea name="pass_message" class="form-control">{{$probsSubCategory->pass_message}}</textarea>
			@error('pass_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Fail Message</label>
			<textarea name="fail_message" class="form-control">{{$probsSubCategory->fail_message}}</textarea>
			@error('fail_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Remediation Message</label>
			<textarea name="remediation_message" class="form-control">{{$probsSubCategory->remediation_message}}</textarea>
			@error('remediation_message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Pass Code</label>
			<textarea name="pass_code" class="form-control">{{$probsSubCategory->pass_code}}</textarea>
			@error('pass_code')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Fail Code</label>
			<textarea name="fail_code" class="form-control">{{$probsSubCategory->fail_code}}</textarea>
			@error('fail_code')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label> Max Score </label>
			<input type="text" name="max_score" class="form-control" value="{{$probsSubCategory->max_score}}">
			@error('max_score')
			<span class="text-danger" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label> Status </label>
			<select class="form-control" name="status">
				<option value="Active" @if($probsSubCategory->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($probsSubCategory->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Update Sub Category</button>
		</div>
	</form>	
</div>
@endsection