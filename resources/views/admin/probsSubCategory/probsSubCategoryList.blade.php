@extends('layouts.admin')
@section('content')
@section('probs_sub_category_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Probs Sub Category</h4>
    <div class="head-form-box filter-form-group ml-auto mr-3 w-35">
        <div class="form-group mb-0 d-flex align-items-center justify-content-between">
            <span>Filter By Category</span>
            <select class="form-control" name="category_filter" onchange="categoryFilter(this.value);"> 
                <option value=" ">Select Category</option>
                @if($category ?? '')
                    @foreach($category ?? '' as $Data) 
                        <option value="{{$Data->id}}" @if($id == $Data->id) selected="selected" @endif>{{ucwords($Data->category_name)}}</option>
                     @endforeach
                @endif    
            </select>
        </div>
    </div>
    @if($action_display ?? ' ') 
        <div class=""><a href="{{url(route('probs-sub-category.create'))}}" class="btn btn-primary">Add Sub Category</a></div>
    @endif    
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Category Name</th>
                    <th class="">Sub Category Name</th>
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
                            <td class="rating-list"><span>{{ucwords($Data->category_name)}}</span> </td>
                            <td class="rating-list"><span>{{ucwords($Data->sub_category_name)}}</span></td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('probs-sub-category.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                    <form action="{{url(route('probs-sub-category.destroy',$Data->id))}}" method="POST"> 
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
        <div class="pagination">{{$content->appends(Request::all())->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection
