@extends('layouts.master')
@section('content')   
    <!-- Search Banner HTML Start -->
    <section class="search-banner-section d-flex align-items-center text-center">
        <div class="container">
            <div class="content">
                <h2 class="mb-4">What's Your Domain's Cybersecurity Rating</h2>
                <div class="search-box d-flex">
                    <!-- <form class="d-flex"> -->
                        <input class="form-control" id="searchdomain" type="search" placeholder="www." aria-label="Search">
                        <span class="custom-dropdown">
                            <select class="form-control" name="industry" id="industry">
                                <option value="">Select Industry</option>
                               @foreach($data['industry_name'] as $key=>$value)
                               <option value="{{$value->id}}">{{$value->industry_name}}</option>
                               @endforeach
                            </select>
                        </span>
                        <button class="btn btn-primary"  id="searchdomainbtn" >Scan</button>
                    <!-- </form> -->
                </div>
                <p>2 free scans a day</p>
            </div>
        </div>
    </section>
    <!-- Search Banner HTML End -->
    <!-- User Slider HTML Start -->
    <section class="user-slider-section">
        <div class="slider-list owl-carousel owl-theme">
            @foreach($data['main_banner'] as $main_banner)
            <div class="item">
                <div class="container">
                    <div class="box">
                        <div class="mw-100">
                            <span class="banner-image mw-100"><img src="{{asset('uploads')}}/{{$main_banner['banner_image']}}" alt="Screen Image" /></span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    <!-- User Slider HTML End -->
    <!-- Domain Report HTML Start -->
    <section class="domain-report-section" id="domains-report" style="display: none">
        <div class="container">
            <div class="sub-head text-center">
                <h3>Your Domain Report</h3>
            </div>
            <div class="report-box d-flex align-items-stretch justify-content-between">
                <div class="list-box text-uppercase" >
                    <h4 class="text-center"> Risk Zone</h4>
                    <ul class="m-0 p-0">
                        @if($categoryList ?? '')
                            @foreach($categoryList ?? '' as $cat)
                                <li><img src="{{asset('img/'.$cat->icon)}}" alt="Icon" class="home-icon" />{{$cat->category_name}}<span class="badge badge-excellent">?</span></li>
                            @endforeach
                        @endif 
                    </ul>
                </div>
                <div class="rating-box">
                    <h4 class="text-center pb-3">CYBERSECURITY RATING</h4>
                    <div class="rating-detail ">
                        <img id="speedMeterImage" src="{{speedMeterImage(0)}}" alt="" />
                        <h3 id="domainname" class="pb-3 text-uppercase"></h3>
                        <p id="rating-message"></p>
                    </div>
                </div>
                <div class="signup-free-box">
                    <span><img src="{{asset('images/batch-icon.svg')}}" alt="Batch Icon" /></span>
                    <!-- <h4>Sign up today as a trial user and unlock your domain Risk Zone Ratings. Sample the portal and access a sample appraisal report.</h4> -->
                    <h4>Sign up as a <span class="text-yellow text-uppercase">trial user</span> and <span class="text-yellow text-uppercase">unlock</span> your domain <span class="text-yellow text-uppercase">Risk Zone</span> rating's.</h4>
                    <h4>Access the portal and sample our cyber appraisal report today!</h4>
                    <h4 class="text-center">...Its <strong>FREE!</strong></h4>
                    <a class="btn btn-outline-primary" href="{{route('register')}}">Sign up Now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Domain Report HTML End -->
    <!-- Dashboard Slider HTML Start -->
    <section class="dashboard-section">
        <div class="container">
            <div class="sub-head text-center">
                <h3 class="">{{$data['subscription_purchase']['title']}}</h3>
                <p>{{$data['subscription_purchase']['subtitle']}}</p>
            </div>
            <div class="dashboard-slider owl-carousel owl-theme">
                @foreach($data['inside_banner'] as $inside_banner)
                    <div class="item d-flex justify-content-between">
                        <div class="content-box">
                            <span><img src="{{asset('images/domain-icon.svg')}}" class="Domain Icon"></span>
                            <h3>{{$inside_banner['title']}}</h3>
                            <p>{{$inside_banner['discription']}}</p>
                            <a href="{{ route('register') }}" class="btn btn-primary">CREATE A FREE TRIAL ACCOUNT</a>
                        </div>
                        <div class="image-box">
                            <span><img src="{{asset('uploads')}}/{{$inside_banner['banner_image']}}" alt="Dashboard Image" /></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Dashboard Slider HTML End -->
    <!-- Feature Section HTML Start -->
    <section class="feature-section">
        <div class="container">
            <div class="sub-head text-center">
                <h3 class="">{{$data['inside_dashboard']['title']}}</h3>
                <p>{{$data['inside_dashboard']['subtitle']}}</p>
            </div>
            <div class="row">
                @foreach($data['features'] as $features)
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="box">
                            <span><img src="{{asset('uploads/')}}/{{$features['icon_image']}}" alt="Feature Image" /></span>
                            <h4>{{$features['title']}}</h4>
                            <p>{{$features['discription']}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Feature Section HTML End -->
    <!-- FAQ Section HTML Start -->
    <section class="faq-section">
        <div class="container">
            <div class="sub-head text-center">
                <!-- <h3>Frequently asked questions (FAQ)</h3> -->
                <h3>{{$data['faq_content']['title']}}</h3>
                <p>{{$data['faq_content']['subtitle']}}</p>
            </div>
            <div class="accordion-box">
                <div class="accordion" id="accordionExample">
                @foreach($data['faq'] as $key => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{$key}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                {{$faq['question']}}
                            </button>
                        </h2>
                        <div id="collapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{$faq['answer']}}
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="text-center">
                    <a href="{{route('register')}}" class="btn btn-primary">CREATE A FREE TRIAL ACCOUNT</a>
                </div>
            </div>
        </div>
    </section>
    @include('front.scanModel')
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

var interval;
$("#searchdomainbtn").click(function(){
    var search = $("#searchdomain").val().trim();
    var pattern = /^[a-z0-9-\.]+\.[a-z]{2,4}/;
    var industry = $( "#industry option:selected" ).val();
    if (industry == '')
    {
        Swal.fire({
          title: 'Error!',
          text: 'Please select Industry.',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        return false;
    }
    else if(search == ''){
        Swal.fire({
          title: 'Error!',
          text: 'Please enter domain.',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        return false;
    }
    else if(pattern.test(search) == false)
    {
        Swal.fire({
          title: 'Error!',
          text: 'Please enter a valid domain.',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        return false;
    }

    // code to reset the values
    $('.statusbar span').removeClass( "completed" );
    $('#preloader').show();
    $('#scanProcessTxt').show();
    $('#scanCompleteTxt').hide();
    $("#domains-report").hide();

    $('#domain_name').html(search);
    scount = 0;
    showModal = true;

    clearInterval(interval); // stop the interval

    checkScanStatus(search);
    interval = setInterval(function() { 
        // alert("3 seconds are up"); 
        // console.log('checkScanStatus=Start='+search);
        checkScanStatus(search);
    }, 3000);

    $.ajax({
        type:'GET',
        url:'<?php echo url('/');?>/search-domain?search='+search+'&industry='+industry,
        data: {_token: '{{csrf_token()}}' },
        success:function(response) {
            // console.log(response);
            var obj = JSON.parse(response);
            if(obj.status=='error'){
                Swal.fire({
                  title: 'Error!',
                  text: obj.message,
                  icon: 'error',
                  confirmButtonText: 'OK'
                })
            }else{                        
                $('#domainname').text('');
                $('#domainname').text(search);
                $('#speedMeterImage').attr('src', obj.speedMeterImage);
                $('#rating-message').text(obj.domainRatingMessage);
                $("#domains-report").show();
            }
        }
    });
});

function closemodel(){
    $('#myModal').modal('hide');
}

maxcount = '{{$data["activeCatCount"]}}';
console.log('maxcount===' + maxcount);
function checkScanStatus(domain_name){
    if(scount < maxcount) {
        console.log('maxcount=' + maxcount + 'scount' + scount);
        $.ajax({
            type:'GET',
            url:'<?php echo url('/');?>/domain-rescan-status-name/'+domain_name,
            data: {_token: '{{csrf_token()}}' },
            success:function(response) {
                console.log(response);
                var obj = JSON.parse(response);
                if(obj.domain_id > 0) {
                    if(showModal) {
                        showModal = false;
                        $('#myModal').modal('show');
                    }
                    if(obj.categoryCount > scount) {
                        scount++;
                    }

                    // console.log(scount);
                    // alert(domain_id+'=1='+scount);
                    for (i = 1; i <= scount; i++) {
                        $('.statusbar .cat'+i).addClass('completed'); 
                    } 
                    if(scount == maxcount) {
                        $('#preloader').hide();
                        $('#scanProcessTxt').hide();
                        $('#scanCompleteTxt').show();

                        setTimeout(function() { 
                            document.getElementById('domains-report').scrollIntoView();
                        }, 3000);

                        setTimeout(function() { 
                            $('#myModal').modal('hide');
                        }, 4000);
                    }
                }
            }
        }); 
    }
}
</script>
@endsection