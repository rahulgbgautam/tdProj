@extends('layouts.master')
@section('content')
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section inner-banner-section d-flex align-items-center text-center" style="background-image: url({{asset('images/banner-1.png')}})">
        <div class="container">
            <div class="content">
                <h2>{{$data['privacy']['title']}}</h2>
                <p>{{$data['privacy']['subtitle']}}</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- Cyber Rating Section HTML Start-->
    <section class="inner-sub-section whitepaper-section">
        <div class="container d-flex">
            <div class="content w-100">
                {!!$data['privacy']['description']!!}                
            </div>
        </div>
    </section>
    <!-- Whitepaper Section HTML Start-->
@endsection