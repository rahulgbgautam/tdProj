@extends('layouts.master')
@section('content')
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section inner-banner-section d-flex align-items-center text-center" style="background-image: url({{asset('images/banner-1.png')}})">
        <div class="container">
            <div class="content">
                <h2>Frequently asked questions</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- FAQ Section HTML Start -->
    <section class="faq-section">
        <div class="container">
            <div class="accordion-box">
                <div class="accordion" id="accordionExample">
                    @foreach($data['faq'] as $key => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{$key}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                {{$faq['question']}}
                            </button>
                        </h2>
                        <div id="collapse{{$key}}" class="accordion-collapse collapse " aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{$faq['answer']}}
                        </div>
                    </div>
                @endforeach
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Whitepaper Section HTML Start-->
@endsection