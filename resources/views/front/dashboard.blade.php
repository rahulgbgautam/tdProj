@extends('layouts.master-dashboard')
@section('content')
    <!-- Main Content HTML Start Here -->
    <div class="page-content">
        @foreach($dataBytype ?? '' as $key=>$value)
            <div class="dashboard-section overflow-hidden">
                <div>
                    <h4 class="content-head">{{$value['title']}}</h4>
                </div>
                <!-- Dashboard Section HTML --> 
                <div class="report-box d-flex align-items-stretch justify-content-between">
                    <div class="list-box domain-list">
                        <h4>Top Performing Domains</h4>
                        @if(count($value['domains']) > 0)
                            <ul class="m-0 p-0">
                                @foreach($value['domains'] ?? '' as $domainInfo)
                                    <li class="pl-0 bg-none"><a class="text-primary text-uppercase"  href="{{ url('/domain-detail',$domainInfo->id)}}">
                                     {{$domainInfo->domain_name}}</a>
                                     <span class="badge {{getRatingClass(getRating($domainInfo->average_score))}}">{{ getRating($domainInfo->average_score) }}</span>
                                @endforeach
                            </li>
                        @else
                            <div class="no-record">No record found.</div>
                        @endif
                        </ul>
                    </div>
                    <div class="list-box">
                        <h4>{{$value['subTitle']}}</h4>
                        @if($value['scores'] ?? '')
                            <ul class="m-0 p-0">
                                @foreach($value['scores'] ?? '' as $domainInfo)
                                    <li>{{$domainInfo->category_name}}
                                        <span class="badge {{($value['domainsRating']) ? 'badge-excellent' : getRatingClass(getRating($domainInfo->average_score))}}">{{ ($value['domainsRating']) ? $value['domainsRating'] : getRating($domainInfo->average_score) }}</span>
                                    </li> 
                                @endforeach
                            </ul>
                        @endif    
                    </div>
                    <div class="rating-box">
                        <h4 class="text-center">{{$value['chartTitle']}}</h4>
                        <div class="rating-detail text-center"> 
                            <?php 
                            $chart = $value['chart'];
                            ?>
                            @include('front.trandingChart')
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection