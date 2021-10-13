@extends('layouts.master')
@section('content')
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section inner-banner-section d-flex align-items-center text-center" style="background-image: url({{asset('images/banner-1.png')}})">
        <div class="container">
            <div class="content">
                <h2>Our Features</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- Feature Section HTML Start -->
    <section class="feature-section">
        <div class="container">
            <div class="sub-head text-center">
                <h3>Features</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div class="row">
                @foreach($data['features'] as $features)
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="box">
                            <span><img src="{{asset('uploads/')}}/{{$features['icon_image']}}" alt="Feature Image" /></span>
                            <h4>{{$features['title']}}</h4>
                            <p>{{$features['discription']}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Whitepaper Section HTML Start-->
@endsection