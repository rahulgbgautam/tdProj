@extends('layouts.master-dashboard')
@section('content')
            <div class="page-content">
                <!-- My Brands Section HTML -->
                <div class="my-brand-section overflow-hidden">
                    <!-- <div class="table-responsive"> -->
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th class="">Domains Name</th>
                                    <th class="">Average Rating</th>
                                    <th class="">Full Report</th>
                                    <th class="">Summary</th>
                                    <th class="">Scan Domain</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['domain_data'] as $domain_data)
                                <tr>
                                    <?php $average = getRating($domain_data->average_score);?>
                                    <?php $averagerating = getRatingValue($average);?>
                                    <td class="name"><span><img src="{{asset('/img/grey-company-icon.svg')}}" class="Company Icon"></span> <a href="domain-detail.html">{{$domain_data->domain_name}}</a></td>
                                    <td class="rating-list"><span class="badge badge-excellent">{{$average}}</span> {{$averagerating}}</td>
                                    <td><a href="{{url('domain-detail',$domain_data->id)}}" class="btn btn-outline-info">View Full Report</a></td>
                                    <td><a href="{{url('domain-summary',$domain_data->id)}}" class="btn btn-outline-info">View Summary</a></td>
                                    <td><a href="#"class="blue-link">Rescan</a> Last scan - 02/04/2021</td>
                                </tr>
                                @endforeach()
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.My Brands Section HTML -->
            </div>
            <!-- Main Content HTML End Here -->
        @endsection