@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">            
    <div class="domain-detail-section overflow-hidden">
        <div class="report-box d-flex align-items-stretch justify-content-between">
            <div class="list-box domain-list">
                <a href="{{route('my-brands')}}" class="back-icon mb-4"><img src="{{asset('/img/back-icon.svg')}}" alt="Back Icon" /> 
                </a>
                <strong>{{strtoupper($domaindata->domain_name)}}</strong>
                <ul class="m-0 p-0">
                    @foreach($domaincategorydata as $value)
                        <?php 
                        $average = getRating($value->average_score);
                        $gradeMessage = categoryMgsByGrade($value->category_name,$average);
                        ?>
                            <li class="d-flex justify-content-between pr-0 pl-0 bg-none">
                                @if($domaindata->expiry_date < date('Y-m-d'))
                                    <p class="first-category-text w-100">{{$value->category_name}}<span class="badge badge-excellent">?</span></p> 
                                @else
                                    <p class="first-category-text">{{$value->category_name}}<span class="badge {{getRatingClass($average)}}">{{$average}}</span></p> 
                                    <p>{{$gradeMessage}}</p>
                                @endif
                            </li>
                    @endforeach()
                </ul>
            </div>
            <div class="rating-box">
                <h4 class="text-center">Domain Rating</h4>
                {!! $trandingChart !!}
            </div>
        </div>
    </div>
    <!-- /.Domain Detail Section HTML -->
</div>
@endsection