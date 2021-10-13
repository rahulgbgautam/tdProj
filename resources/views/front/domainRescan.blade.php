@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">            
    <div class="domain-detail-section overflow-hidden">
        <div class="report-box d-flex align-items-stretch justify-content-between">
            <div class="list-box domain-list">
                <a href="{{route('my-brands')}}" class="back-icon mb-4"><img src="{{asset('/img/back-icon.svg')}}" alt="Back Icon" /> Go Back</a>
                @if($domainInfo['expiry_date'] < date('Y-m-d'))
                    <div class="no-permission">This domain has been exipred. You can rescan this domain after add credit for this domain.</div>
                @elseif($domainInfo)
                    <div class="rescan">
                        <h3>Rescan is under process. Please wait...</h3>
                    </div>
                @else
                    <div class="no-permission">You are not authorised to rescan this domain.</div>
                @endif
            </div>
        </div>
    </div>
    <!-- /.Domain Detail Section HTML -->
</div>
@endsection