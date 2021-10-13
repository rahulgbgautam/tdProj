@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    <!-- Buy Subscription Section HTML -->
    <div class="buy-subscription-section overflow-hidden">
        <div class="mb-3">
        	@if($successMsg)
            <p>
            {!! ($successMsg) !!}
            </p>
            @endif
        </div>
    </div>
</div>
@endsection
