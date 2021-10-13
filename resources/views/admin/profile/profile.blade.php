@extends('layouts.admin')
@section('content')
<div class="profile-section overflow-hidden">
    <div class="report-box d-flex">
        <div class="profile-image">
            @if($admin->profile_image)
                <span>
                    <img src="{{showImage($admin->profile_image)}}">
                </span>
            @else
                <span>
                    <img src="{{asset('img/default-icon.png')}}" alt="Profile Image"/ width="100px;">
                </span>
            @endif 
        </div>
        <div class="profile-content ml-2">
            <h3>{{ucwords($admin->name)}}</h3>
            <p>{{$admin->email}}</p>
            <div class="profile-link">
                <a href="{{url('admin/profile/change-password',$admin->id)}}" class="blue-link text-underline password-icon">Change Password</a>
                <a href="{{url('admin/profile/edit',$admin->id)}}" class="blue-link text-underline edit-icon">Edit profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
