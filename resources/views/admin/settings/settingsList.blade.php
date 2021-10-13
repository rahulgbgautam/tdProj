@extends('layouts.admin')
@section('content')
@section('setting_select','active') 
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Settings</h4>
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Title</th>
                    <th class="">Value</th>
                    @if($action_display ?? ' ')
                    <th class="">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($data ?? '')
                    @foreach($data ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{ucwords(str_replace('_', ' ',$Data->title))}}</span> </td>
                            <td class="rating-list">
                                @if($Data->id == 11 or $Data->id == 12 or $Data->id == 13)
                                    ${{number_format(floatval($Data->value),2)}}
                                @else
                                {{$Data->value}}
                                @endif
                            </td> 
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('settings.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
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