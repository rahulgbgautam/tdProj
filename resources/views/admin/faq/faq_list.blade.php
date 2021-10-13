@extends('layouts.admin')
@section('content')
@section('faq_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">FAQ</h4>
    @if($action_display ?? ' ')
        <div class=""><a href="{{url(route('faq.create'))}}" class="btn btn-primary">Add FAQ</a></div>
    @endif    
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Question</th>
                    <th class="">Status </th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($Faq ?? '')
                    @foreach($Faq ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{$Data->question}}</span></td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('faq.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                    <form action="{{url(route('faq.destroy',$Data->id))}}" method="POST"> 
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="text-primary" onclick=" return confirm('Are you sure want to delete this record?');" data-toggle="tooltip"  title="Delete"><img src="{{asset('img/blue-delete-icon.svg')}}" alt="Delete Icon" /></button>
                                    </form>  
                                </td>
                            @endif
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($Faq) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{ $Faq->links() }}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection


