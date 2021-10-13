<div class="page-content">
    <!-- Buy Subscription Section HTML -->
    @if(session()->has('error'))
        <span class="text-danger" role="alert">
            {{ session('error') }}
        </span>
    @endif 
    @if(session()->has('success'))
        <span class="text-success" role="alert">
            {{ session('success') }}
        </span>
    @endif 
    <div class="buy-subscription-section overflow-hidden">
        @if(!$subscriptionInfoMember)   
            <div class="pb-3">
                <h4 class="content-head">{{$pricingPurchaseSubscription['title']}}</h4>
            </div>
            <form method="get" action="{{route('subscription-domains')}}">
                <input type="hidden" name="ptype" value="membership">
                <div class="subscription-feature-box d-flex align-items-end justify-content-between" style="background-image:url({{asset('/img/buy-subscription-banner.png')}}">
                    <div class="left-box">
                        <h5>Features</h5>
                        <p>{{$subscriptionMembership['subtitle']}}</p>
                        {!!$subscriptionMembership['description']!!}
                        
                    </div>
                    <div class="right-box">
                        <h3><span>{{($membership_credit_cost)}}</span> /year</h3>
                        <p>{{$subscriptionMonitor['subtitle']}}</p>
                        @if($subscriptionbtntext=='Yes')
                        <button type="submit" class="btn btn-primary">Renew Subscription</button>
                        @else
                            <button type="submit" class="btn btn-primary">Purchase Subscription</button>
                        @endif
                    </div>
                </div>
            </form>
        @endif
        <div class="mb-3">
            <h4 class="content-head">Credits</h4>
        </div>
        <div class="purchase-credit">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <form method="get" action="{{route('subscription-domains')}}">
                        <input type="hidden" name="ptype" value="month">
                        @if($domain_id > 0)
                            <input type="hidden" name="domain_id" value="{{$domain_id}}">
                        @endif
                        <div class="purchase-box">
                            <div class="d-flex align-items-center">
                                <div class="left-box">
                                    <h5>{{$subscriptionMonthly['subtitle']}}</h5>
                                    {!!$subscriptionMonthly['description']!!}
                                    
                                </div>
                                <!-- <div class="right-box">
                                    <h3><span>{{($month_credit_cost)}}</span> /mo</h3>
                                    <span>Per Domain</span>
                                    <div class="count-box">
                                        <p>Set Quantity</p>
                                        <div class="qty d-flex align-items-center">
                                            <span class="minus" onclick="minusCount('month');">-</span>
                                            <input type="text" class="count" id="month_qty" name="qty" value="1" readonly="true">
                                            <span class="plus" onclick="addCount('month');">+</span>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="right-box">
                                  <h3><span>{{($month_credit_cost)}}</span> /mo</h3>
                                  <span>Per Domain</span>
                                @guest
                                <input type="hidden" class="count" id="month_qty" name="qty" value="1" readonly="true">
                                @else
                                  <div class="count-box">
                                      <p>Set Quantity</p>
                                      <div class="qty d-flex align-items-center">
                                          <span class="minus" onclick="minusCount('month');">-</span>
                                          <input type="text" class="count" id="month_qty" name="qty" value="1" readonly="true">
                                          <span class="plus" onclick="addCount('month');">+</span>
                                      </div>
                                  </div>
                                @endguest
                                </div>
                            </div>
                            @guest
                                <div class="text-center pt-3">
                                    <button typ="submit" class="btn btn-outline-primary">Proceed</button>
                                </div>
                            @else
                                <div class="text-center pt-3">
                                    <button typ="submit" class="btn btn-outline-primary" @if(!$subscriptionInfo) disabled @endif>Proceed</button>
                                </div>
                            @endguest
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-sm-12">
                    <form method="get" action="{{route('subscription-domains')}}">
                        <input type="hidden" name="ptype" value="year">
                        @if($domain_id > 0)
                            <input type="hidden" name="domain_id" value="{{$domain_id}}">
                        @endif
                        <div class="purchase-box">
                            <div class="d-flex align-items-center">
                                <div class="left-box">
                                    <h5>{{$subscriptionYearly['subtitle']}}</h5>
                                    {!!$subscriptionYearly['description']!!}
                                    
                                </div>
                                <!-- <div class="right-box">
                                    <h3><span>{{($year_credit_cost)}} </span> /yr</h3>
                                    <span>Per Domain</span>
                                    <div class="count-box">
                                        <p>Set Quantity</p>
                                        <div class="qty d-flex align-items-center">
                                            <span class="minus" onclick="minusCount('year');">-</span>
                                            <input type="text" class="count" id="year_qty" name="qty" value="1" readonly="true">
                                            <span class="plus" onclick="addCount('year');">+</span>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="right-box">
                                <h3><span>{{($year_credit_cost)}} </span> /yr</h3>
                                <span>Per Domain</span>
                                @guest
                                <input type="hidden" class="count" id="year_qty" name="qty" value="1" readonly="true">
                                @else
                                <div class="count-box">
                                  <p>Set Quantity</p>
                                  <div class="qty d-flex align-items-center">
                                    <span class="minus" onclick="minusCount('year');">-</span>
                                    <input type="text" class="count" id="year_qty" name="qty" value="1" readonly="true">
                                    <span class="plus" onclick="addCount('year');">+</span>
                                  </div>
                                </div>
                                @endguest
                                </div>
                            </div>
                            @guest
                                <div class="text-center pt-3">
                                    <button typ="submit" class="btn btn-outline-primary">Proceed</button>
                                </div>
                            @else
                                <div class="text-center pt-3">
                                    <button typ="submit" class="btn btn-outline-primary" @if(!$subscriptionInfo) disabled @endif>Proceed</button>
                                </div>
                            @endguest
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Buy Subscription Section HTML -->
</div>
<script type="text/javascript">
    function addCount(type) {
        var current_num = $("#"+type+"_qty").val();
        if(current_num>=1){
            $("#"+type+"_qty").val(eval(current_num)+1);
        }else if(current_num=1){
            $("#"+type+"_qty").val(1);
        } 
    }
    function minusCount(type) {
        var current_num = $("#"+type+"_qty").val();
        if(current_num>1){
            $("#"+type+"_qty").val(eval(current_num)-1);
        }else if(current_num=1){
            $("#"+type+"_qty").val(1);
        }   
    } 
</script>