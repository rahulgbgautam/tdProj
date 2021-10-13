@extends('layouts.admin')
@section('content')
@section('email_management_select','active')	
<h5 class="text-left pl-3">Edit Email Template</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('email-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	@if($editemailtemp)
	<form action="{{url(route('email-management.update',$editemailtemp->id))}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Label</label><br>{{$editemailtemp->label}}</div>		
		<div class="form-group">
			<label>Subject</label>
			<input type="text" name="subject" class="form-control"  value="{{$editemailtemp->title}}">
			@error('subject')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Message</label>
			<textarea name="message" style="height:250px" class="form-control">{{$editemailtemp->description}}</textarea>
			@error('message')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Variable</label><br>{!! str_replace(',', '<br>', $editemailtemp->variable) !!}</div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
	@endif			
</div>
@endsection
