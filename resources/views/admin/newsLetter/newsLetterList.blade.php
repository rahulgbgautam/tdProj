@extends('layouts.admin')
@section('content')
@section('news_letter_select','active')           
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">News Letter</h4>
    <form class="form-inline head-form-box" method="get">
        <div class="row">
            <div class="col-md-6 ml-auto">
                <div class="form-group head_search">
                  <input type="text" class="form-control" name="search" placeholder="Search By Email" id="myInput" value="{{old('search',$search)}}">
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
                    <th class="">Email</th>
                    <th class="">Status </th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($newsLetter ?? '')
                    @foreach($newsLetter ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{$Data->email}}</span> </td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <form action="{{url(route('news-letter.destroy',$Data->id))}}" method="POST"> 
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
        @if(count($newsLetter) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{$newsLetter->appends(Request::all())->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection
