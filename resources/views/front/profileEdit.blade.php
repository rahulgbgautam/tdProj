@extends('layouts.master-dashboard')
@section('content') 
<div class="page-content">
	<div class="admin-head d-flex align-items-center justify-content-between mb-3">
		<h4 class="content-head">Edit Profile </h4>
	    <div class=""><a href="{{url('view-profile')}}" class="btn btn-primary">Back</a></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form action="{{url('profile/update',$user->id)}}" method="post" enctype="multipart/form-data">
				@csrf
				@method('put')
				<div class="form-group">
					<label> Name </label>
					<input type="text" name="name" class="form-control" value="{{$user->name}}">
					@error('name')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>	
				<div class="form-group">
					<label> Email </label>
					<input type="email" name="email" class="form-control" value="{{$user->email}}">
					@error('email')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					@if(session()->has('emailError'))
		                <span class="text-danger" role="alert">
		                    <strong> {{ session('emailError') }} </strong>
		                </span>
		            @endif 
				</div>	
				<div class="form-group">
					<label> Profile Image </label>
					<div class="pb-3">
						<td class="rating-list p-2">
							@if($user->profile_image)
								<img src="{{showImage($user->profile_image)}}" style="width: 130px;height: auto;">
							@else
								<img src="{{asset('img/default-icon.png')}}" style="width: 130px;height: auto;" />
							@endif
						</td>
					</div>
					<input type="file" name="profile_image" class="form-control-file" id="profile_image_file" onchange="preview_image(event)">
					<input type="hidden" name="old_profile_image" value="{{$user->profile_image}}">
					<img class="mt-3" id="output_image" style="width: 100px;height: auto;">
					@error('profile_image')
						<span class="text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-success"> Update Profile </button>
				</div>
			</form>			
		</div>
	</div>
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
