@extends('layouts.master-dashboard')
@section('content') 
        <!-- Deepweb Tool Box Section HTML Start-->
        <div class="page-content">
            @if(session()->has('successMsg'))
                <div class="container alert alert-success">
                    {{ session()->get('successMsg') }}
                </div>
            @endif
                <!-- Profile Section HTML -->
                <div class="profile-section overflow-hidden">
                    <div class="report-box d-flex">
                        <div class="profile-image">
                            @if($data->profile_image)
                                <span><img src="{{showImage($data->profile_image)}}" alt="Profile Image" /></span>
                            @else
                                <span><img src="{{asset('img/default-icon.png')}}" alt="Profile Image" /></span>
                            @endif
                        </div>
                        <div class="profile-content">
                            <p class="message-text blue-text">Your subscription to access all the sections of the portal will expire on <span>{{($data->date)}}</span></p>
                            <h3>{{$data->name}}</h3>
                            <p>{{$data->email}}</p>
                            <div class="profile-link">
                                <a href="{{route('change-password')}}" class="blue-link text-underline password-icon">Change Password</a>
                                <a href="{{route('profile-edit')}}" class="blue-link text-underline edit-icon">Edit profile</a>
                            </div>
                        </div>
                    </div>
                    @if($transaction_data ?? '')
                        <div>
                            <h4 class="content-head">My Transaction</h4>
                        </div>

                        <div class="report-box p-0">
                            <div class="table-responsive">
                                <table id="dataTableExample" class="table">
                                    <thead>
                                        <tr>
                                            <th class="">Subscriptions</th>
                                            <th class="">Purchased Date</th>
                                            <th class="">Expiry Date</th>
                                            <th class="">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction_data as $data)
                                            <tr>
                                                <td>
                                                    @if($data->subscription_type == "Membership")
                                                        Purchased Subscription
                                                    @elseif($data->subscription_type == "Yearly")
                                                        Purchased Yearly Credits
                                                    @else
                                                        Purchased Monthly Credits
                                                    @endif
                                                </td>
                                                <td>{{showDate($data->created_at)}}</td>
                                                <td>{{showDate($data->expire_date)}}</td>
                                                <td><a class="text-primary" title="Download PDF" href="{{ url('generate-invoice',encrypt($data->id)) }}" target="_blank"><img src="{{asset('img/pdf-icon.svg')}}" alt="Generate PDF" /></a></td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- /.Profile Section HTML -->
            </div>
        <!-- Whitepaper Section HTML Start-->        
    </div>
@endsection