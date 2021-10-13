@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    <!-- Domain Report Section HTML -->
    <div class="domain-report-section overflow-hidden">
        <?php $i=0;
        if(count($data)>0){?>
        @foreach($data['domainsubcategories'] as $key => $value)
            <?php $average = getRating($data['domaincategorydata'][$i]['average_score']);?>
            <div class="admin-head d-flex align-items-center justify-content-between pb-3">
                <h4 class="content-head"><span class="mr-2">
                    <img src="{{asset('img/'.$data['domaincategorydata'][$i]['icon'])}}" alt="Icon" class="home-icon" />
                </span> {{$key}} <span class="badge {{getRatingClass($average)}} ml-3">{{$average}}</span></h4>
                @if($i==0)

                <div class="">
                   <strong class="pr-3"> @if(count($data['domaincategorydata'])>0) 
                    {{strtoupper($data['domaincategorydata'][0]['domain_name'])}}
                    @endif</strong>
                    <a class="btn btn-primary" target="_blank" href="{{ url('/view-report-pdf',encrypt($data['domain_id'])) }}"> Generate Full Report</a></div>
                @endif
            </div>
            <div class="table-responsive">
                @if(count($value)>0)
                    <table  id="dataTableExample" class="table brands">
                        <thead>
                            <tr>
                                <th class="">Risk Zone Probe</th>
                                <th class="">Result</th>
                                <th class="">Result Message</th>
                                <th class="">Remediation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($value as $subcategory)
                                <tr>
                                    <td>{{$subcategory['sub_category_display_name']}}</td>
                                    <td>{{$subcategory['status']}}</td>
                                    <td><p>
                                        @if(($subcategory['status']) == 'Pass')
                                            {{$subcategory['pass_message']}}
                                        @else
                                            {{$subcategory['fail_message']}}
                                        @endif
                                    </p></td> 
                                    <td><p>
                                        @if(($subcategory['status']) == 'Pass')
                                            N/A
                                        @else
                                            {{$subcategory['remediation_message']}}
                                            @if($subcategory['message'])
                                                {!! '<br><strong>'.$subcategory['message'].'</strong>' !!}
                                            @endif
                                        @endif
                                    </p></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-record">No record found.</div>
                    <br><br>
                @endif
            </div>
            <?php $i++; ?>
        @endforeach
    <?php } ?>
    </div>
    <!-- /.Domain Report Section HTML -->
</div>
@endsection