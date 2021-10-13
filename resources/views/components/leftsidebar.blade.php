<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{url('/admin/dashboard')}}" class="navbar-brand"><img src="{{asset('img/logo.png')}}"></a>
    </div>
        <?php
            $id = session('admin')['id'];     
            $menu_read = menuPermissionByType($id,"read");
        ?>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item @yield('dashboard_select')">
                <a href="{{url('/admin/dashboard')}}" class="nav-link">
                    <i class="link-icon dashboard-icon" data-feather=""></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if(in_array("admin-users",$menu_read))
            <li class="nav-item @yield('admin_users_select')">
                <a href="{{url('admin/admin-management')}}" class="nav-link">
                    <i class="link-icon side-user-icon" data-feather=""></i>
                    <span class="link-title">Manage Admin Users</span>
                </a>
            </li>
            @endif
            @if(in_array("portal-users",$menu_read))
            <li class="nav-item @yield('manage_portal_users_select')">
                <a href="{{route('user-management.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Manage Portal Users</span>
                </a>
            </li>
            @endif
            @if(in_array("transaction-history",$menu_read))
            <li class="nav-item @yield('transaction_history_select')">
                <a href="{{route('transaction-history.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Transaction History</span>
                </a>
            </li>
            @endif
            @if(in_array("domains",$menu_read))
            <li class="nav-item @yield('domains_select')">
                <a href="{{url('admin/domains')}}" class="nav-link">
                    <i class="link-icon side-domain-icon" data-feather=""></i>
                    <span class="link-title">Domains</span>
                </a>
            </li>
            @endif
            @if(in_array("probs-category",$menu_read))
            <li class="nav-item @yield('probs_category_select')">
                <a href="{{url('admin/probs-category')}}" class="nav-link">
                    <i class="link-icon deepweb-tool-box-icon" data-feather=""></i>
                    <span class="link-title">Probs Category</span>
                </a>
            </li>
            @endif
            @if(in_array("probs-sub-category",$menu_read))
            <li class="nav-item @yield('probs_sub_category_select')">
                <a href="{{url('admin/probs-sub-category')}}" class="nav-link">
                    <i class="link-icon deepweb-tool-box-icon" data-feather=""></i>
                    <span class="link-title">Probs Sub Category</span>
                </a>
            </li>
            @endif
            @if(in_array("email-management",$menu_read))
            <li class="nav-item @yield('email_management_select')">
                <a href="{{route('email-management.index')}}" class="nav-link">
                    <i class="link-icon side-email-icon" data-feather=""></i>
                    <span class="link-title">Email Management</span>
                </a>
            </li>
            @endif
            @if(in_array("content-management",$menu_read))
            <li class="nav-item @yield('content_management_select')">
                <a href="{{route('content-management.index')}}" class="nav-link">
                    <i class="link-icon side-content-icon" data-feather=""></i>
                    <span class="link-title">Content Management</span>
                </a>
            </li>
            @endif
            @if(in_array("dynamic-content",$menu_read))
            <li class="nav-item @yield('dynamic_content_select')">
                <a href="{{url('admin/dynamic-content')}}" class="nav-link">
                    <i class="link-icon side-dynamic-icon" data-feather=""></i>
                    <span class="link-title">Dynamic Content</span>
                </a>
            </li>
            @endif
            @if(in_array("banner-management",$menu_read))
            <li class="nav-item @yield('banner_management_select')">
                <a href="{{url('admin/banner-management')}}" class="nav-link">
                     <i class="link-icon side-banner-icon" data-feather=""></i>
                    <span class="link-title">Banner Management</span>
                </a>
            </li>
            @endif
            @if(in_array("features-management",$menu_read))
            <li class="nav-item @yield('features_management_select')">
                <a href="{{url('admin/features-management')}}" class="nav-link">
                    <i class="link-icon side-feature-icon" data-feather=""></i>
                    <span class="link-title">Features Management</span>
                </a>
            </li>
            @endif
            @if(in_array("faq",$menu_read))
            <li class="nav-item @yield('faq_select')">
                <a href="{{url('admin/faq')}}" class="nav-link">
                    <i class="link-icon side-faq-icon" data-feather=""></i>
                    <span class="link-title">FAQ</span>
                </a>
            </li>
            @endif
            @if(in_array("manage-industry",$menu_read))
            <li class="nav-item @yield('industry_select')">
                <a href="{{route('industry.index')}}" class="nav-link">
                    <i class="link-icon side-industry-icon" data-feather=""></i>
                    <span class="link-title">Manage Industry</span>
                </a>
            </li>
            @endif
            @if(in_array("manage-avg-rating-text",$menu_read))
            <li class="nav-item @yield('overall_rating_select')">
                <a href="{{url('admin/overall-rating')}}" class="nav-link">
                    <i class="link-icon side-rating-icon" data-feather=""></i>
                    <span class="link-title">Manage AVG Rating Text</span>
                </a>
            </li>
            @endif
            @if(in_array("news-letter",$menu_read))
            <li class="nav-item @yield('news_letter_select')">
                <a href="{{url('admin/news-letter')}}" class="nav-link">
                    <i class="link-icon side-newslatter-icon" data-feather=""></i>
                    <span class="link-title">News Letter</span>
                </a>
            </li>
            @endif
            @if(in_array("promo-code",$menu_read))
            <li class="nav-item @yield('promo_code_select')">
                <a href="{{url('admin/promo-code')}}" class="nav-link">
                    <i class="link-icon side-promocode-icon" data-feather=""></i>
                    <span class="link-title">Promo Code</span>
                </a>
            </li>
            @endif
            @if(in_array("general-settings",$menu_read))
            <li class="nav-item @yield('setting_select')">
                <a href="{{url('admin/settings')}}" class="nav-link">
                    <i class="link-icon side-setting-icon" data-feather=""></i>
                    <span class="link-title">General Settings</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>













