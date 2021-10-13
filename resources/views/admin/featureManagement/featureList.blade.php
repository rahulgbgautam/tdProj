@extends('layouts.admin')
@section('content')
@section('features_management_select','active')     
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Features Management</h4>
    @if($action_display ?? ' ')
        <div class=""><a href="{{url(route('features-management.create'))}}" class="btn btn-primary">Add Feature</a></div>
    @endif
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Icon </th>
                    <th class="">Title</th>
                    <th class="">Status </th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($FeatureData ?? '')
                    @foreach($FeatureData ?? '' as $Data) 
                        <tr>
                            <td class="rating-list p-2"> <img src="{{showImage($Data->icon_image)}}" style="width: 50px;height: auto;"></td>
                            <td class="rating-list"><span>{{$Data->title}}</span> </td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('features-management.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                    <form action="{{url(route('features-management.destroy',$Data->id))}}" method="POST"> 
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
        @if(count($FeatureData) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{ $FeatureData->links() }}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection