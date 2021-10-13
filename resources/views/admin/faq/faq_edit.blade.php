@extends('layouts.admin')
@section('content')
@section('faq_select','active')	
<h5 class="text-left pl-3">Edit FAQ</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('faq.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	@if($faq)
	<form action="{{route('faq.update',$faq->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Question</label>
			<input type="text" name="question" class="form-control" value="{{$faq->question}}">
			@error('question')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Answer</label>
			<textarea  name="answer" class="form-control" value="" rows="3">{{$faq->answer}}</textarea>
			@error('answer')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($faq->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($faq->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update FAQ</button>
		</div>
	</form>	
	@endif			
</div>
@endsection