<div class="main-navbar shadow-sm">
    <div class="top-navbar" >
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-2 my-auto d-none d-sm-none d-md-block d-lg-block" >
                    <h5 class="brand-name">{{ $appSetting->website_name ?? 'website name'}}</h5>
                </div>
                <div class="col-md-5 my-auto">
                    <form action="{{ url('search') }}" method="GET" role="search">
                        <div class="input-group">
                            <input type="search" name="search" value="{{ Request::get('search') }}" placeholder="Search your product" class="form-control " />
                            <button class="btn" style="background-color: rgba(255,255,255,0.5);" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 my-auto">
                    <ul class="nav justify-content-end">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('cart') }}">
                                <i class="fa fa-shopping-cart"></i> Cart (<livewire:frontend.cart.cart-count/>)
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('products') }}">
                                <i class="fa fa-cart-plus"></i> Sell (<livewire:frontend.product.product-count/>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('product-status') }}">
                                <i class="fa fa-cart-plus"></i>  Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('wishlist') }}">
                                <i class="fa fa-heart"></i> Wishlist (<livewire:frontend.wishlist-count/>)
                            </a>
                        </li>
                        @guest
                    @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                            <li class="nav-item">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addBrandModal" class="nav-link">Add Brands</a>
                            </li> 

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('githublogin') }}">{{ __('Github Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('googlelogin') }}">{{ __('Goggle Login') }}</a>
                            </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user" style="color:  #202020;"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('/profile')}}"><i class="fa fa-user" style="color:  #202020;"></i> Profile</a></li>
                            @if(Auth::user()->role_as != 0)
                            <li><a class="dropdown-item" href="{{ url('/admin/dashboard')}}"><i class="fa fa-user" style="color:  #202020;"></i> Admin</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ url('/orders')}}"><i class="fa fa-list" style="color:  #202020;"></i> My Orders</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fa fa-list" style="color:  #202020;"></i> My Store</a></li>
                            <li><a class="dropdown-item" href="{{ url('wishlist') }}"><i class="fa fa-heart" style="color:  #202020;"></i> My Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cart')}}"><i class="fa fa-shopping-cart" style="color:  #202020;"></i> My Cart</a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" style="color:  #202020;"></i> Logout</a> {{ __('') }}
                                </a>
                        
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                            </ul>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand d-block d-sm-block d-md-none d-lg-none" href="#">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/collections') }}">All Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/new-arrivals') }}">New Arrivals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/featured-products') }}">Featured Products</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Electronics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Fashions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accessories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Appliances</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
</div>
