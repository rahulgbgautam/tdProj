@extends('layouts.admin')
@section('content')
@section('transactions_history_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Transaction History</h4>
    <form class="form-inline head-form-box" method="get">
        <div class="row">
            <div class="col-md-6 ml-auto">
                <div class="form-group">
                    <select class="form-control" name="transaction_type"> 
                        <option value=" ">Select Transaction Type</option> 
                        <option value="Membership" @if($transaction_type == "Membership") selected="selected" @endif>Purchased Subscription</option> 
                        <option value="Yearly" @if($transaction_type == "Yearly") selected="selected" @endif>Purchased Yearly Credits</option> 
                        <option value="Monthly" @if($transaction_type == "Monthly") selected="selected" @endif>Purchased Monthly Credits</option> 
                    </select>
                </div>
            </div>
            <div class="col-md-6 ml-auto">
                <div class="form-group head_search">
                  <input type="text" class="form-control" name="search" placeholder="Search By Name/Email" value="{{old('search',$search ?? ' ')}}">
                  <div class="input-group-append">
                    <button type=submit class="btn btn-success"><i class="fa fa-search"></i> </button>
                  </div>
                </div>    
            </div>
        </div>
    </form>     
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Name</th>
                    <th class="">Transaction Type</th>
                    <th class="text-right">Transaction Number</th>
                    <th class="text-right">Paid Amount</th>
                    <th class="text-center">Purchased Date</th>
                    <th class="text-center" width="150px;">Expiry Date</th>   
                    <th class="text-center" width="150px;">Invoice</th>   
                </tr>
            </thead>
            <tbody>
                @if($subscriptionData ?? '')
                    @foreach($subscriptionData ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{ucWords($Data->name)}}</span></td>
                            <td class="rating-list">
                                @if($Data->subscription_type == "Membership")
                                    Purchased Subscription
                                @elseif($Data->subscription_type == "Yearly")
                                    Purchased Yearly Credits
                                @else
                                    Purchased Monthly Credits
                                @endif
                            </td>
                            <td class="rating-list text-right"><span>{{ucWords($Data->transaction_number)}}</span></td>
                            <td class="rating-list text-right"><span>${{number_format($Data->paid_amount,2)}}</span></td>
                            <td class="rating-list text-center"><span>{{showDate($Data->created_at)}}</span></td>
                            <td class="rating-list text-center"><span>{{showDate($Data->expire_date)}}</span></td>
                            <td class="rating-list text-center">
                                <a class="text-primary" href="{{ url('generate-invoice',encrypt($Data->id)) }}" data-toggle="tooltip" title="Download Invoice" target="_blank"><img src="{{asset('img/pdf-icon.svg')}}" alt="Generate PDF" /></a>
                            </td>
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($subscriptionData) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{$subscriptionData->appends(Request::all())->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection 
