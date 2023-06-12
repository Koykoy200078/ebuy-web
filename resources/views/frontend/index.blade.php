@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<div style=" background-color: rgba(199, 255, 160, 0.5);">
   
    <div class="container">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
    
            
        </div>
        <div class="background background-left"></div>
        <div class="background background-right"></div>
        <div class="container">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">

                <div class="carousel-inner" style="padding-top: 24px;"">

                    @if (session('message'))
                        <h6 class=" alert alert-warning mb-3">{{ session('message') }}</h6>
                    @endif
                    @if (session('message2'))
                    <h6 class="alert alert-warning mb-3" style="background-color: darkgoldenrod; color: black;">{!! session('message2') !!}</h6>
                    @endif
                    @foreach ($sliders as $key => $sliderItem)
                    <div class="carousel-item {{ $key == '0' ? 'active':''}} ">
                        @if ($sliderItem->image)
                        <img src="{{ asset("$sliderItem->image") }} " class="d-block w-100" alt="..." style="border-radius: 10px;  " height="600">
                        @endif

                        <div class="carousel-caption d-none d-md-block">
                            <div class="custom-carousel-content">
                                {{-- <h1>
                                            {!! $sliderItem->title !!}
                                        </h1>
                                        <p>{!! $sliderItem->description !!}</p>
                                        <div>
                                            <a href="#" class="btn btn-slider">
                                                Get Now
                                            </a>
                                        </div> --}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
                </button> --}}
                <button class="carousel-button carousel-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <button class="carousel-button carousel-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <i class="fa fa-arrow-right"></i>
                </button>


            </div>
        </div>

        <div class="py-5 ">
            <div class="container">
                <div class="row justify-content-center">
                    <div style="background-color: #91ff80; padding: 20px;">
                        <h2 style="font-size: 32px; text-align: center;"><strong>Welcome to {{ $appSetting->website_name ?? 'website name'}}</strong></h2>
                        <p style="font-size: 20px; text-align: center;">{{ $appSetting->website_name ?? 'website name'}}, is your local eCommerce platform where you can buy and sell reusable and recyclable items.</p>
                        <p style="font-size: 20px; text-align: center;">Join our community of environmentally-conscious buyers and sellers today!</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4><strong>Most Sold </strong></h4>
                        <div class="underline mb-4"></div>
                    </div>
                    @if ($trendingProducts)
                    <div class="col-md-12">
                        <div class="owl-carousel owl-theme four-carousel">
                            @foreach ($trendingProducts as $productsItem)
                            <div class="item">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <label class="stock" style="background-color: rgb(224, 10, 57)">Hot</label>


                                        @if ($productsItem->productImages->count() > 0)
                                        <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                            <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}" width="500" height="300">
                                        </a>

                                        @endif

                                    </div>
                                    <div class="product-card-body">
                                        <p class="product-brand">{{ $productsItem->brand }}</p>
                                        <h5 class="product-name">
                                            <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}" width="500" height="300">
                                                {{ $productsItem->name }}
                                            </a>
                                        </h5>
                                        <div>
                                            <span class="selling-price">₱{{  number_format($productsItem->selling_price, 2, '.', ',') }}</span>
                                            <span class="original-price">₱{{ number_format($productsItem->original_price, 2, '.', ',') }}</span>
                                            {{-- <p>Quantity: {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity }}</p> --}}
                                            <span> &nbsp;( {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity ?? '0' }} ) </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="p-2">
                            <h4>No Most Sale Available</h4>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="py-5 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4><strong> Recent Posted Item </strong>
                            <a href="{{ url('new-arrivals') }}" class="btn float-end custom-bg rounded-pill">View more</a>
                        </h4>
                        <div class="underline mb-4"></div>
                    </div>
                    @if ($newArrivalProducts)
                    <div class="col-md-12">
                        <div class="owl-carousel owl-theme four-carousel">
                            @foreach ($newArrivalProducts as $productsItem)
                            <div class="item">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <label class="stock bg-success">New</label>


                                        @if ($productsItem->productImages->count() > 0)
                                        <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                            <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}" width="500" height="300">
                                        </a>

                                        @endif

                                    </div>
                                    <div class="product-card-body">
                                        <p class="product-brand">{{ $productsItem->brand }}</p>
                                        <h5 class="product-name">
                                            <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                                {{ $productsItem->name }}
                                            </a>
                                        </h5>
                                        <div>
                                            <span class="selling-price">₱{{  number_format($productsItem->selling_price, 2, '.', ',') }}</span>
                                            <span class="original-price">₱{{ number_format($productsItem->original_price, 2, '.', ',') }}</span>
                                            <span > &nbsp;( {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity ?? '0' }} ) </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="p-2">
                            <h4>No Recent Posted Item Available</h4>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4><strong>Featured Products</strong>

                            <a href="{{ url('featured-products') }}" class="btn float-end custom-bg rounded-pill">View more</a>
                        </h4>
                        <div class="underline mb-4"></div>
                    </div>
                    @if ($featuredProducts)
                    <div class="col-md-12">
                        <div class="owl-carousel owl-theme four-carousel">
                            @foreach ($featuredProducts as $productsItem)
                            <div class="item">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <label class="stock" style="background-color: #FFD700">Featured</label>


                                        @if ($productsItem->productImages->count() > 0)
                                        <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                            <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}width=" 500" height="300">
                                        </a>

                                        @endif

                                    </div>
                                    <div class="product-card-body">
                                        <p class="product-brand">{{ $productsItem->brand }}</p>
                                        <h5 class="product-name">
                                            <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                                {{ $productsItem->name }}
                                            </a>
                                        </h5>
                                        <div>
                                            <span class="selling-price">₱{{  number_format($productsItem->selling_price, 2, '.', ',') }}</span>
                                            <span class="original-price">₱{{ number_format($productsItem->original_price, 2, '.', ',') }}</span>
                                            <span > &nbsp;( {{ $sold->where('product_id', $productsItem->id)->first()->total_quantity ?? '0' }} ) </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="p-2">
                            <h4>No Featured Products Available</h4>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script>
    $('.four-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });

    $(document).ready(function() {
    $('.carousel').hover(
        function() {
            $('.carousel-button').show();
        },
        function() {
            $('.carousel-button').hide();
        }
    );
});

// $(document).ready(function(){
//     $(window).scroll(function(){
//       if($(this).scrollTop() > 50){
//         $('.navbar').addClass('fixed-top');
//       }
//       else{
//         $('.navbar').removeClass('fixed-top');
//       }
//     });
//   });
//   window.addEventListener('scroll', function() {
//   var navbar = document.querySelector('.navbar');
//   if (window.scrollY > 0) {
//     navbar.classList.add('scrolled');
//   } else {
//     navbar.classList.remove('scrolled');
//   }
// });

    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('fixed-top');
            } else {
                $('.navbar').removeClass('fixed-top');
            }
        });
    });
    window.addEventListener('scroll', function() {
        var navbar = document.querySelector('.navbar');
        if (window.scrollY > 0) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

@endsection