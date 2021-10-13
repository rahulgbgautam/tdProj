@extends('layouts.admin')
@section('content')
<h5 class="text-left pl-3"> Edit Profile </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url('admin/profile')}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($admin)
	<form action="{{url('admin/profile/update',$admin->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{$admin->name}}">
			@error('name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>	
		<div class="form-group">
			<label>Email</label>
			<input type="email" name="email" class="form-control" value="{{$admin->email}}">
			@error('email')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
			@if(session()->has('emailError'))
                <span class="text-danger" role="alert">
                    <strong>{{session('emailError')}}</strong>
                </span>
            @endif 
		</div>
		@if($admin->profile_image)
            <div class="form-group mb-0">
				<label>Previous Profile Picture</label>
			</div>
			<div class="form-group">
				<img src="{{showImage($admin->profile_image)}}" style="width: 100px;height: auto;">
			</div>
        @else
        	<div class="form-group mb-0">
				<label>Previous Profile</label>
			</div>
			<div class="form-group">
				<img src="{{asset('img/default-icon.png')}}" alt="Profile Image"/ width="100px;">
			</div>
        @endif 
		<div class="form-group">
			<label>Select New Profile Picture</label>
			<input type="file" name="profile_image" class="form-control-file" id="profile_image_file" onchange="preview_image(event)">
			<input type="hidden" name="old_profile_image" value="{{$admin->profile_image}}">
			<img class="mt-3" id="output_image" style="width: 100px;height: auto;">
			@error('profile_image')
				{{$message}}
			@enderror
		</div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update Profile</button>
		</div>
	</form>	
	@endif			
</div>
<script>
function preview_image(event) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById('output_image');
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection
