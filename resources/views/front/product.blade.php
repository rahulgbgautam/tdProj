@extends('layouts.master')
@section('content') 
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section inner-banner-section d-flex align-items-center text-center" style="background-image: url({{asset('images/banner-1.png')}})">
        <div class="container">
            <div class="content">
                <h2>{{$data['product_data']['title']}}</h2>
                <p>{{$data['product_data']['subtitle']}}</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- Whitepaper Section HTML Start-->
    <section class="inner-sub-section whitepaper-section">
        <div class="container d-flex">
            <div class="image-box"><img src="{{asset('uploads/')}}/{{$data['product_data']['image']}}" alt="Whitepaper Image" /></div>
            <div class="content">
                {!!$data['product_data']['description']!!}
            </div>
        </div>
    </section>
    <!-- Whitepaper Section HTML Start-->
@endsection