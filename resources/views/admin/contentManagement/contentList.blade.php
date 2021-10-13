@extends('layouts.admin')
@section('content')
@section('content_management_select','active')  
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Content Management</h4>
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Title</th>
                    <th class="">Sub Title</th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif  
                </tr>
            </thead>
            <tbody>
                @if($content ?? '')
                    @foreach($content ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{$Data->title}}</span> </td>
                            <td class="rating-list">{{$Data->subtitle}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('content-management.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                </td>
                            @endif 
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection
