@extends('layouts.admin')
@section('content')
@section('domains_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Domains</h4>
        <div class="row w-75">
            <div class="col-md-6 text-right">
                <a href="{{url('admin/export-csv',$search)}}" class="btn btn-primary">Export Domains</a>
                @if($action_display ?? ' ')
                    <a href="{{url('admin/domains/create')}}" class="btn btn-primary">Add Domains</a>
                @endif
            </div>
            <div class="col-md-6 ml-auto">
                <form class="form-inline head-form-box" id="myform" method="get">
                    <div class="form-group head_search">
                      <input type="text" class="form-control" name="search" placeholder="Search By Domain Name" id="myInputs" value="{{old('search',$search)}}">
                      <div class="input-group-append">
                        <button type=submit class="btn btn-success"><i class="fa fa-search"></i> </button>
                      </div>
                    </div>
                </form>
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
                    <th class="">Domain Name</th>
                    <th class="">Industries</th>
                    <th class="">Grade</th>
                    <th class="text-right" width="70px">Score (In %)</th>
                    <th class="text-center" width="100px">Latest Scan Date</th>
                    <th class="text-center">Status</th>
                    <th class="">Action</th>
                </tr>
            </thead>
            <tbody>
                @if($content ?? '')
                    @foreach($content ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{$Data->domain_name}}</span> </td>
                            <td class="rating-list"><span>{{$Data->industry_name}}</span> </td>
                            <td class="rating-list"><span>{{getRating($Data->average_score)}}</span> </td>
                            <td class="rating-list text-right">{{$Data->average_score}}</td>
                            <td class="rating-list text-center"><span>{{showDate($Data->last_scan_date)}}</span> </td>
                            <td class="rating-list text-center"><span>{{$Data->status}}</span> </td>
                            <td class="action-button" width="200px;">
                                <a class="text-primary" href="{{ url('admin\view-report-pdf',encrypt($Data->id)) }}" data-toggle="tooltip" title="Download Invoice" target="_blank"><img src="{{asset('img/pdf-icon.svg')}}" alt="Generate PDF" />
                                @if($action_display ?? ' ')    
                                    <a class="text-primary" href="{{ url('admin\add-user-domain',$Data->id)}}" data-toggle="tooltip" title="Associate Domain"><img src="{{asset('img/add-icon.svg')}}" alt="Associate User" />
                                    <a href="{{url('admin\domain\edit',$Data->id)}}" class="text-primary"  data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>     
                                    @if($Data->status=="Active")    
                                        <a class="text-primary" href="{{ url('admin\block-domain',$Data->id)}}"><img src="{{asset('img/block-icon.svg')}}" data-toggle="tooltip" title="Inactive Domain"/>
                                    @else
                                       <a class="text-primary" href="{{ url('admin\unblock-domain',$Data->id)}}"><img src="{{asset('img/unlock-icon.svg')}}" data-toggle="tooltip" title="Active Domain"/>
                                    @endif
                                    <a class="text-primary" target="_blank" href="{{ url('admin/domain-rescan/'.$Data->id)}}"><img src="{{asset('img/scan-icon.svg')}}" data-toggle="tooltip" title="Scan Domain"/>
                                @endif    
                            </td>
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
