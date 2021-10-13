@extends('layouts.admin')

@section('content')

<section class="edit-user-form">

	<div class="container mt-5">
	
		<div class="col-6 m-auto">

			@if($user)


					<form action="{{route('user-management.update',$user->id)}}" method="post">

						@csrf
						@method('put')

						<div class="form-group">

							<label> Name </label>
							<input type="text" name="name" class="form-control" value="{{$user->name}}">

						</div>

						<div class="form-group">

							<label> Email </label>
							<input type="email" name="email" class="form-control" value="{{$user->email}}">

						</div>

						<div class="form-group">

							<label> Status </label>
							<div class="custom-dropdown">
								<select class="form-control" name="status">
									<option value="Active"> Active </option>
									<option value="Block"> Block </option>
								</select>
							</div>
						</div>

						<div class="form-group">

							<label> Password </label>
							<input type="password" name="password" class="form-control" value="{{$user->password}}">

						</div>

						<div>
							<button type="submit" class="btn btn-primary"> Update </button>
						</div>

					</form>	


			@endif			

		</div>
		
	</div>	
	
</section>

@endsection