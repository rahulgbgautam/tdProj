<!-- Footer Section HTML Start -->
<footer class="footer-section">
    <div class="container">
        <div class="footer-menu-list">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="menu-list">
                        <h4>Quick Links</h4>
                        <ul class="m-0 p-0">
                            <li><a href="{{ route('login')}}">Portal Login</a></li>
                            <li><a href="{{ route('register') }}">Create Account for Free Trial</a></li>
                            <li><a href="{{ url('/')}}">Domain Scan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="menu-list">
                        <h4>Support</h4>
                        <ul class="m-0 p-0">
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="javascript:void(0)"><strong>Email Us:</strong></a></li>
                            <li><a href="mailto:{{getGeneralSetting('email_us')}}">{{getGeneralSetting('email_us')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="menu-list submit-box">
                        <h4>News Letter Sign-Up</h4>
                        <div class="d-flex align-items-center justify-content-between">
                        <input name="newsletterEmail" id="newsletterEmail" class="form-control" type="email" placeholder="ENTER EMAIL" aria-label="Search">
                        <button class="btn btn-primary" id="newsletterSubmit">Subscribe</button>
                    </div>
                    <div id="newsletterMessage"></div>
                </div>
            </div>
                <div class="col-md-2 col-sm-6">
                    <div class="menu-list follow-us">
                        <h4>Follow Us</h4>
                        <ul class="m-0 p-0">
                            <li><a href="{{getGeneralSetting('Facebook')}}" target="_blank"><img src="{{asset('images/facebook-icon.svg')}}" alt="Facebook Icon" /></a></li>
                            <li><a href="{{getGeneralSetting('Linkedin')}}" target="_blank"><img src="{{asset('images/linkedin-icon.svg')}}" alt="Linkedin Icon" /></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="second-footer d-md-flex justify-content-between align-self-center">
            <p>{{getGeneralSetting('copyright')}}</p>
            <div class="footer-menu">
                <a href="{{route('cookies')}}">Cookies notification</a>
                <a href="{{route('privacy')}}">Privacy Policy</a>
                <a href="{{route('term')}}">Terms & Conditions</a>
                <!-- <a href="{{route('faq')}}">FAQ</a> -->
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section HTML End -->
<div class="back-to-top">
    <a href="#header" id="backToTop"><img src="{{asset('images/blue-top-arrow.svg')}}" alt="Top Arrow"></a>
</div>
</div>
    <script type="text/javascript">
           // function to get records
           $("#newsletterSubmit").click(function() {
               $('#newsletterMessage').html('');
               email = $('#newsletterEmail').val().trim();
               var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
               if(email == ''){
                   $('#newsletterMessage').html('<span class="text-danger">Please enter email.</span>');
                   return false;
               }
               else if(reg.test(email) == false)
               {
                   $('#newsletterMessage').html('<span class="text-danger">Please enter valid email.</span>');
                   return false;
               }
               $.ajax({
                   type: 'post',
                   url: '{{ URL("newsletterSubscribeAjax") }}',
                   data: {
                       '_token': '{{ csrf_token() }}',
                       'email': email,
                   },
                   success: function(data) {
                       console.log(data);
                       if (data.success) {
                           $('#newsletterEmail').val('');
                       }
                       $('#newsletterMessage').html(data.message);
                   },
               });
           });
    </script>