@extends('layouts.master-dashboard')
@section('content')
    <!-- Main Content HTML Start Here -->
    <div class="page-content">
        <div>
            <h4 class="content-head">My Domains</h4>
        </div>
        <!-- Dashboard Section HTML -->
        <div class="dashboard-section overflow-hidden">
            <div class="report-box d-flex align-items-stretch justify-content-between">
                <div class="list-box domain-list">
                    <h4>Top 6 Domains</h4>
                    @if(count($domains1) > 0)
                        <ul class="m-0 p-0">
                            @foreach($domains1 ?? '' as $data)
                                <li class="pl-0 bg-none"><a class="text-primary text-uppercase"  href="{{ url('/domain-detail',$data->id)}}">
                                 {{$data->domain_name}}</a>
                                 <span class="badge {{getRatingClass(getRating($data->average_score))}}">{{ getRating($data->average_score) }}</span>
                            @endforeach
                        </li>
                    @else
                        <div class="no-record">No record found.</div>
                    @endif
                    </ul>
                </div>
                <div class="list-box">
                    <h4>Avg Risk Zones of All Domains</h4>
                    @if($scores1 ?? '')
                        <ul class="m-0 p-0">
                            @foreach($scores1 ?? '' as $data)
                                <li>{{$data->category_name}}
                                    <span class="badge {{($domainsRating1) ? 'badge-excellent' : getRatingClass(getRating($data->average_score))}}">{{ ($domainsRating1) ? $domainsRating1 : getRating($data->average_score) }}</span>
                                </li> 
                            @endforeach
                        </ul>
                    @endif    
                </div>
                <div class="rating-box">
                    <h4 class="text-center">Average Rating of All Domains</h4>
                    <div class="rating-detail text-center">
                        {!! $trandingChart1 !!}
                        <!-- <img src="{{asset('/img/graph-img01.png')}}" alt="" /> -->
                    </div>
                </div>
            </div>
            <div>
                <h4 class="content-head">3rd Party Domains</h4>
            </div>
            <div class="report-box d-flex align-items-stretch justify-content-between">
                <div class="list-box domain-list">
                    <h4> Top 6 Domains</h4>
                    @if(count($domains2) > 0)
                        <ul class="m-0 p-0">
                            @foreach($domains2 ?? '' as $data)
                                <li class="pl-0 bg-none"><a class="text-primary text-uppercase"  href="{{ url('/domain-detail',$data->id)}}">{{$data->domain_name}}</a>
                                    <span class="badge {{getRatingClass(getRating($data->average_score))}}">{{ getRating($data->average_score) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="no-record">No record found.</div>
                    @endif
                </div>
                <div class="list-box">
                    <h4> Avg Risk Zones of All Domains</h4>
                    @if($scores2 ?? '')
                        <ul class="m-0 p-0">
                            @foreach($scores2 ?? '' as $data)
                                <li>{{$data->category_name}}
                                    <span class="badge {{($domainsRating2) ? 'badge-excellent' : getRatingClass(getRating($data->average_score))}}">{{ ($domainsRating2) ? $domainsRating2 : getRating($data->average_score) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif 
                </div>
                <div class="rating-box">
                    <h4 class="text-center">Average Rating of All Domains</h4>
                    <div class="rating-detail text-center">                        
                        {!! $trandingChart1 !!}
                        <!-- <img src="{{asset('/img/graph-img01.png')}}" alt="" /> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection