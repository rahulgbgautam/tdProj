<div class="wrapper">
    <!-- Header HTML Start -->
    <header class="header-section" id="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{asset('images/logo.png')}}" alt="Logo" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @guest                     
                    <p class="pink-text"></p>
                @endguest
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <!-- <a class="nav-link active" aria-current="page" href="{{ route('about') }}">Abouts</a> -->
                        </li>
                        @if(count($data['resource_dropdown'])>0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Resources</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach($data['resource_dropdown'] as $val)
                                    <li><a class="dropdown-item" href="{{ url('resource',$val->id) }}">{{$val['menu_name']}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{url('pricing')}}">Pricing</a>
                        </li>
                        @if(count($data['product_dropdown'])>0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Product</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach($data['product_dropdown'] as $val)
                                    <li><a class="dropdown-item" href="{{ url('product',$val->id) }}">{{$val['menu_name']}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
                @guest
                        <div class="d-flex">
                            <a href="{{ route('login') }}" class="btn btn-secondary" type="submit">Sign in</a>
                            <a  href="{{ route('register') }}" class="btn btn-primary" type="submit">Create an Account</a>
                        </div>
                    @else
                        <div class="d-flex">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary" type="submit">{{ __('Dashboard') }}</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-primary">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endguest
            </div>
        </nav>
    </header>
    <!-- Header HTML End -->