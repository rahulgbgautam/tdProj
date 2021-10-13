@extends('layouts.master')
@section('content')
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section inner-banner-section d-flex align-items-center text-center" style="background-image: url({{asset('images/banner-1.png')}})">
        <div class="container">
            <div class="content">
                <h2>{{$data['resource_data']['title']}}</h2>
                <p>{{$data['resource_data']['subtitle']}}</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- Whitepaper Section HTML Start-->
    <section class="inner-sub-section whitepaper-section">
        <div class="container d-flex">
            <div class="image-box order-1"><img src="{{asset('uploads/')}}/{{$data['resource_data']['image']}}" alt="Research Image" /></div>
            <div class="content">
                {!!$data['resource_data']['description']!!}
            </div>
        </div>
    </section>
    <!-- Whitepaper Section HTML Start-->
@endsection