@extends('layouts.admin')
@section('content')
@section('overall_rating_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Manage Avg Rating Text</h4>
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Grade</th>
                    <th class="">Min Score</th>
                    <th class="">Performance</th>
                    <th class="">Message</th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($overallRating ?? '')
                    @foreach($overallRating ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{ucwords($Data->grade)}}</span> </td>
                            <td class="rating-list">{{$Data->min_score}}</td>
                            <td class="rating-list">{{$Data->performance}}</td>
                            <td class="rating-list">{{$Data->message}} </td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('overall-rating.edit',$Data->id))}}" class="text-primary"  data-toggle="tooltip"  title="Edit">
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