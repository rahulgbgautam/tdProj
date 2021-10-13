@extends('layouts.master-dashboard')
@section('content')
    <div class="page-content">
        <!-- My Brands Section HTML -->
        @if(session()->has('error'))
            <span class="text-danger" role="alert">
                <strong> {{ session('error') }} </strong>
            </span>
        @endif 
        @if(session()->has('success'))
            <span class="text-success" role="alert">
                <strong> {{ session('success') }} </strong>
            </span>
        @endif 
        <div class="my-brand-section overflow-hidden">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th class="">Domain Name</th>
                            <th class="">Domain Rating</th>
                            <th class="">Full Report</th>
                            <th class="">Summary</th>
                            <th class="">You Scanned</th>
                            <th class="">Latest Scan</th>
                            <th class="">Expiry Date</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        @foreach($data['domain_data'] as $domain_data)
                        <tr style="@if($domain_data->expiry_date < date('Y-m-d')) background-color:#EEE @endif" >
                            <?php $getInfoByScore = getRatingInfoByScore($domain_data->average_score);?>
                            <td class="name text-uppercase">
                                @if($domain_data->firstdomain == 'yes')
                                    <a href="javascript:void(0);" onclick="firstdomainalart();">{{$domain_data->domain_name}}</a>
                                @else
                                    <a href="{{url('domain-detail',$domain_data->domain_id)}}">{{$domain_data->domain_name}}</a>
                                @endif

                                @if($domain_data->cdn_network == 'Yes')
                                    <img class="question-icon" src="{{asset('img/exclamation-icon.png')}}" title="{{getGeneralSetting('cdn_network_message')}}"
                                    style="cursor: pointer; max-width: 16px; max-height: 16px;" 
                                    >
                                @endif
                            </td>
                            <td class="rating-list"><span class="badge {{getRatingClass($getInfoByScore['grade'])}}">{{$getInfoByScore['grade']}}</span> {{$getInfoByScore['performance']}}</td>

                            <td><a alt="Full Report" title="Full Report" class="btn btn-outline-info"
                                    @if($domain_data->last_scan_date==null)
                                       href="javascript:void(0);" onclick="disablescanalart('report');"
                                    @elseif($domain_data->firstdomain == 'yes')
                                       href="javascript:void(0);" onclick="firstdomainalart();"
                                    @elseif($domain_data->expiry_date < date('Y-m-d'))
                                       href="javascript:void(0);" onclick="disablealart();"
                                    @else
                                       href="{{url('domain-detail',$domain_data->domain_id)}}"
                                    @endif
                                >View</a>
                            </td>
                            <td>
                                @if($domain_data->last_scan_date==null)
                                    <a alt="Summary" title="Summary" href="javascript:void(0);" class="btn btn-outline-info" onclick="disablescanalart('summary');">View</a>
                                @else
                                    <a alt="Summary" title="Summary" href="{{url('domain-summary',$domain_data->domain_id)}}" class="btn btn-outline-info">View</a>
                                @endif
                            </td>
                            <td style="{{(lastScanReminder($domain_data->scan_date)=='Yes')?'color: red;':''}} text-align: center;">
                                {{showDate($domain_data->scan_date)}} 
                            </td>
                            <td style="{{(lastScanReminder($domain_data->last_scan_date)=='Yes')?'color: red;':''}} text-align: center;">
                                {{showDate($domain_data->last_scan_date)}} 
                            </td>

                            <td style="{{(expiryDateReminder($domain_data->expiry_date)=='Yes')?'color: red;':''}} text-align: center;">
                                {{showDate($domain_data->expiry_date)}}
                            </td>
                            <td class="blue-link">
                                @if($domain_data->expiry_date < date('Y-m-d'))
                                    <a href="{{url('/subscription-plan',$domain_data->domain_id)}}">
                                        <img src="{{asset('img/subscription-icon.svg')}}" alt="View Icon" />
                                    </a>
                                @else                                
                                    @if($domain_data->last_scan_date==null)
                                         <a alt="Scan" title="Scan" href="javascript:void" onclick="scanDomain('{{$domain_data->domain_id}}', '{{$domain_data->domain_name}}')">
                                            <img src="{{asset('img/scan-icon.svg')}}" alt="Scan Icon" />
                                        </a>
                                    @else
                                        <a alt="Rescan" title="Rescan" href="javascript:void" onclick="scanDomain('{{$domain_data->domain_id}}', '{{$domain_data->domain_name}}')">
                                            <img src="{{asset('img/scan-icon.svg')}}" alt="Scan Icon" />
                                        </a>
                                    @endif
                                @endif
                                <a alt="Renewal" title="Renewal" href="{{url('domain-auto-renewal',$domain_data->domain_id)}}">
                                    @if($domain_data->auto_payment == 'Yes')
                                        <img src="{{asset('img/blue-checked-icon.svg')}}" alt="Auto Renewal On" />
                                    @else
                                        <img src="{{asset('img/block-icon.svg')}}" alt="Auto Renewal Off" />
                                    @endif
                                </a>
                            
                                <a alt="Generate PDF" title="Generate PDF" 
                                @if($domain_data->last_scan_date==null)
                                    href="javascript:void(0);" onclick="disablescanalart('report');"
                                @elseif($domain_data->firstdomain == 'yes')
                                    href="javascript:void(0);" onclick="firstdomainalart();"
                                @elseif($domain_data->expiry_date < date('Y-m-d'))
                                    href="javascript:void(0);" onclick="disablealart();"
                                @else
                                    href="{{ url('/view-report-pdf',encrypt($domain_data['domain_id'])) }}"
                                @endif
                                ><img src="{{asset('img/pdf-icon.svg')}}" alt="Generate PDF" /></a>
                            </td>
                        </tr>
                        @endforeach()
                    </tbody>
                </table>
                @if(count($data['domain_data']) < 1) 
                    <div class="no-record">No record found.</div>
                @endif
                <div class="pagination">{{ $data['domain_data']->links() }}</div>
            </div>
        </div>
    </div>
    </div>
        <!-- /.My Brands Section HTML -->
    </div>
    <!-- Modal -->
    @include('front.scanModel')
@endsection
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

maxcount = '{{$data["activeCatCount"]}}';
function scanDomain(domain_id, domain_name) {
    $('#myModal').modal('show');
    $('#domain_name').html(domain_name);

    // check scan status
    scount = 0;
    timer = setInterval(function() { 
        // alert("3 seconds are up"); 
        checkScanStatus(domain_id);
    }, 3000);

    $.ajax({
        type:'GET',
        url:'<?php echo url('/');?>/domain-rescan/'+domain_id+'?type=ajax',
        data: {_token: '{{csrf_token()}}' },
        success:function(response) {

        }
    });
}

// $('.close').click(function() {
//     // $('#myModal').modal('hide');
//     // $('#myModal').css('display','none');
// });
function closemodel(){
    $('#myModal').modal('hide');
    // $('#myModal').css('display','none');
}

function checkScanStatus(domain_id){
    if(scount < maxcount) {
        $.ajax({
            type:'GET',
            url:'<?php echo url('/');?>/domain-rescan-status/'+domain_id,
            data: {_token: '{{csrf_token()}}' },
            success:function(response) {
                if(response > scount) {
                    scount++;
                }
                // alert(domain_id+'=1='+scount);
                for (i = 1; i <= scount; i++) {
                    $('.statusbar .cat'+i).addClass('completed'); 
                } 

                if(scount == maxcount) {
                    $('#preloader').hide();
                    $('#scanProcessTxt').hide();
                    $('#scanCompleteTxt').show();

                    setTimeout(function() { 
                        location.reload();
                    }, 4000);
                }
            }
        }); 
    }
}

function disablealart(){
    var link = '<a href="<?php echo url('/');?>/subscription-plan">CLICK HERE</a>';
    Swal.fire({
        title: 'Alert!',
        html: 'The domains credit has been expired. You need to purchase more credits to view full report for this domain. '+link+' to purchase credits.',

        icon: 'alart',
        confirmButtonText: 'OK'
    })
}
function firstdomainalart(){
    var link = '<a href="<?php echo url('/');?>/subscription-plan">CLICK HERE</a>';
    Swal.fire({
        title: 'Alert!',
        html: 'You must purchase a subscription plan to view this report.'+link+'to purchase.',
        icon: 'alart',
        confirmButtonText: 'OK'
    })
}
function disablescanalart(type){
    if(type=='report'){
        $msg = 'This domain has not been scanned earlier. Please scan once to see the full report.';
    }else{
        $msg = 'This domain has not been scanned earlier. Please scan once to see the summary report.';
    }
    Swal.fire({
        title: 'Alert!',
        html: $msg,
        icon: 'alart',
        confirmButtonText: 'OK'
    })
}
</script>