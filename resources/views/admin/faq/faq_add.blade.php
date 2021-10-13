@extends('layouts.admin')
@section('content')		
@section('faq_select','active')	
<h5 class="text-left pl-3">Add FAQ</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('faq.index'))}}" class="btn btn-primary">Back</a></div>
</div> 
<div class="col-12">
	<form action="{{route('faq.store')}}" method="post">
		@csrf
		<div class="form-group">
			<label>Question</label>
			<input type="text" name="question" class="form-control" value="{{old('question')}}">
			@error('question')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Answer</label>
			<textarea  name="answer" class="form-control" rows="3">{{old('answer')}}</textarea>
			@error('answer')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add FAQ</button>
		</div>
	</form>	
</div>
@endsection
