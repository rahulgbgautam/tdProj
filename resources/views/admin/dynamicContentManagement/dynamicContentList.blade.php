@extends('layouts.admin')
@section('content')
@section('dynamic_content_select','active')  
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Dynamic Content Management</h4>
    @if($action_display ?? ' ')
        <div class=""><a href="{{url(route('dynamic-content.create'))}}" class="btn btn-primary">Add Content</a></div>
    @endif    
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Menu Type</th>
                    <th class="">Menu Name</th>
                    <th class="">Image</th>
                    <th class="">Status </th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif  
                </tr>
            </thead>
            <tbody>
                @if($content ?? '')
                    @foreach($content ?? '' as $Data) 
                        <tr>
                            <td class="rating-list">{{$Data->menu}}</td>
                             <td class="rating-list">{{$Data->menu_name}}</td>
                             <td class="rating-list p-2">  <img src="{{showImage($Data->image)}}" style="width: 100px;height: auto;"> </td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('dynamic-content.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>
                                    <form action="{{url(route('dynamic-content.destroy',$Data->id))}}" method="POST"> 
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
        @if(count($content) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{ $content->links() }}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection
