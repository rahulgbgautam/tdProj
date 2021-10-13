@extends('layouts.admin')
@section('content')
@section('manage_portal_users_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Portal Users</h4>
    <form class="form-inline head-form-box" method="get">
        <div class="row">
            <div class="col-md-6 ml-auto">
                <div class="form-group head_search">
                  <input type="text" class="form-control" name="search" placeholder="Search By Name/Email" id="myInput" value="{{old('search',$search)}}">
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
                    <th class="">Email</th>
                    <th class="">User Type</th>
                    <th class="">Last Login At</th>
                    <th class="">Status</th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tbodyMsg">
                @if($UserData ?? '')
                    @foreach($UserData ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{ucwords($Data->name)}}</span></td>
                            <td class="rating-list">{{$Data->email}}</td>
                            <td class="rating-list">
                                @if($Data->user_id)
                                    Paid
                                @else
                                    Free
                                @endif        
                            </td>
                            <td class="rating-list">{{time_elapsed_string($Data->last_login_at)}}</td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                            <td class="action-button" width="150px;">
                                <a href="{{url(route('user-management.edit',$Data->id))}}"  class="text-primary" data-toggle="tooltip"  title="Edit">
                                    <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                </a>
                                <a href="{{url('admin/user-management/free-access',$Data->id)}}"  class="text-primary" data-toggle="tooltip"  title="Provide Free Access" onclick=" return confirm('Are you sure want to give free access?');">
                                    <img src="{{asset('img/free-access-icon.svg')}}" alt="Provide Free Access" />
                                </a>    
                                <form action="{{url(route('user-management.destroy',$Data->id))}}" method="POST"> 
                                @csrf
                                @method('DELETE')
                                    <button type="submit" class="text-primary" onclick=" return confirm('Are you sure want to delete this record?');" data-toggle="tooltip"  title="Delete"><img src="{{asset('img/blue-delete-icon.svg')}}" alt="Delete Icon"/></button>
                                </form>
                                @if($Data->subscription_type)
                                    <a href="{{url(route('transaction-history.index','search='.$Data->email))}}" data-toggle="tooltip"  title="Transaction History" class="text-primary">
                                    <img src="{{asset('img/subscription-icon.svg')}}" alt="Transaction History" />
                                </a>      
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($UserData) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{$UserData->appends(Request::all())->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection
