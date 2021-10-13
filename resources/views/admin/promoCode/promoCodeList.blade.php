@extends('layouts.admin')
@section('content')
@section('promo_code_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Promo Code</h4>
    @if($action_display ?? ' ')
        <div class=""><a href="{{url(route('promo-code.create'))}}" class="btn btn-primary">Add Promo Code</a></div>
    @endif    
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Promo Code</th>
                    <th class="text-right">Available Quantity</th>
                    <th class="text-right">Used</th>
                    <th class="text-right">Discount (In Percent)</th>
                    <th class="text-center">Expiry date</th>
                    <th class="">Status</th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($promoCode ?? '')
                    @foreach($promoCode ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{$Data->promo_code}}</span></td>
                            <td class="rating-list text-right">{{$Data->available}}</td>
                            <td class="rating-list text-right">{{$Data->used}}</td>
                            <td class="rating-list text-right">{{$Data->discount}}</td>
                            <td class="rating-list text-center">{{showDate($Data->expire_date)}}</td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                            <td class="action-button" width="150px;">
                                <a href="{{url(route('promo-code.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                    <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                </a>
                                <form action="{{url(route('promo-code.destroy',$Data->id))}}" method="POST"> 
                                @csrf
                                @method('DELETE')
                                    <button type="submit" class="text-primary" onclick=" return confirm('Are you sure want to delete this record?');" data-toggle="tooltip" title="Delete"><img src="{{asset('img/blue-delete-icon.svg')}}" alt="Delete Icon" /></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($promoCode) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{ $promoCode->links() }}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection 