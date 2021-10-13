<nav class="sidebar">
    <!-- <div class="sidebar-header">
        <a href="#" class="sidebar-brand"><img src="assets/img/logo.png"></a>
        <a href="#" class="sidebar-brand small-logo"><img src="assets/img/small-logo.png"></a>
        <div class="sidebar-toggler not-active"><img src="assets/img/collapse-icon.svg"></div>
    </div> --> 
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item @yield('dashboard_select')">
                <a href="{{route('dashboard')}}" class="nav-link">
                    <i class="link-icon dashboard-icon" data-feather="#"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item @yield('mybrands_select')">
                <a href="{{route('my-brands')}}" class="nav-link">
                    <i class="link-icon my-domain-icon" data-feather=""></i>
                    <span class="link-title">My Domains - {{getDomainCountByType(1)}}</span>
                </a>
            </li>
            <li class="nav-item @yield('brandportfolio_select')">
                <a href="{{route('my-portfolio')}}" class="nav-link">
                    <i class="link-icon brand-portfolio-icon" data-feather=""></i>
                    <span class="link-title">Vendor Domains- {{getDomainCountByType(2)}}</span>
                </a>
            </li>
            <li class="nav-item">
                <p>Tool Box</p>
            </li>
            <li class="nav-item @yield('profile_select')">
                <a href="{{route('email-breach')}}" class="nav-link">
                    <i class="link-icon emailbreach-icon" data-feather=""></i>
                    <span class="link-title">Email Breach</span>
                </a>
            </li>
            <li class="nav-item @yield('deepwebtool_select')">
                @if(session('subscription') == 'yes')
                <a href="{{route('deep-web-tool')}}" class="nav-link">
                @else
                <a href="javascript:void(0);" onclick="return disableWebTool()" class="nav-link">
                @endif
                    <i class="link-icon deepweb-tool-box-icon" data-feather=""></i>
                    <span class="link-title">Deepweb Search</span>
                </a>
            </li>
            <li class="nav-item">
                <p>Coming Soon</p>
            </li>
            <li class="nav-item @yield('profile_select')">
                <a href="#" class="nav-link">
                    <i class="link-icon compliance-icon" data-feather=""></i>
                    <span class="link-title">Compliance Auditor</span>
                </a>
            </li><li class="nav-item @yield('profile_select')">
                <a href="#" class="nav-link">
                    <i class="link-icon policy-icon" data-feather=""></i>
                    <span class="link-title">Policy Library</span>
                </a>
            </li>
            </li><li class="nav-item @yield('profile_select')">
                <a href="#" class="nav-link">
                    <i class="link-icon cyber-icon" data-feather=""></i>
                    <span class="link-title">Cyber Awareness</span>
                </a>
            </li>
            <li class="nav-item @yield('profile_select')">
                <a href="{{route('view-profile')}}" class="nav-link">
                    <i class="link-icon profile-icon" data-feather=""></i>
                    <span class="link-title">Profile</span>
                </a>
            </li>
            <li class="nav-item nav-item-profile">
                <div class="sidebar-profile">
                    <span><img src="{{getProfile(auth::id())}}" alt="Profile Image" /></span>
                    <p>{{getProfile(auth::id(),"name")}}</p>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function disableWebTool(){
    var link = '<a href="<?php echo url('/');?>/subscription-plan">CLICK HERE</a>';
    Swal.fire({
        title: 'Alert!',
        html: 'To use the Deepweb Tool Box, you need to purchase the subscription first. '+link+' to purchase credits.',

        icon: 'alart',
        confirmButtonText: 'OK'
    })
}
</script>