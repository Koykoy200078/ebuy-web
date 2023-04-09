@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">

        <div class="carousel-inner">

        @foreach ($sliders as $key => $sliderItem)
            <div class="carousel-item {{ $key == '0' ? 'active':''}} ">
                @if ($sliderItem->image)
                <img src="{{ asset("$sliderItem->image") }} " class="d-block w-100" alt="..."   width="500" height="600">
                @endif

                    <div class="carousel-caption d-none d-md-block">
                        <div class="custom-carousel-content">
                            <h1>
                                {!! $sliderItem->title !!}
                            </h1>
                            <p>{!! $sliderItem->description !!}</p>
                            <div>
                                <a href="#" class="btn btn-slider">
                                    Get Now
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
        @endforeach


    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>


  <div class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h4>Welcome to Ecommerce</h4>
                <div class="underline mx-auto"></div>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,

                </p>
            </div>
        </div>
    </div>
  </div>


  <div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Trending Products</h4>
                <div class="underline mb-4"></div>
            </div>
            @if ($trendingProducts)
            <div class="col-md-12">
                <div class="owl-carousel owl-theme four-carousel">
                    @foreach ($trendingProducts as $productsItem)
                        <div class="item">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <label class="stock bg-danger">New</label>


                                    @if ($productsItem->productImages->count() > 0)
                                    <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                        <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}">
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
                                        <span class="selling-price">₱{{ $productsItem->selling_price }}</span>
                                        <span class="original-price">₱{{ $productsItem->original_price }}</span>
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
                    <h4>No Products Available</h4>
                </div>
            </div>
            @endif
        </div>
    </div>
  </div>

  <div class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>New Arrivals
                    <a href="{{ url('new-arrivals') }}" class="btn btn-warning float-end">View more</a>
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
                                    <label class="stock bg-danger">New</label>


                                    @if ($productsItem->productImages->count() > 0)
                                    <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                        <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}">
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
                                        <span class="selling-price">₱{{ $productsItem->selling_price }}</span>
                                        <span class="original-price">₱{{ $productsItem->original_price }}</span>
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
                    <h4>No New Arrivals Available</h4>
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
                <h4>Featured Products
                    <a href="{{ url('featured-products') }}" class="btn btn-warning float-end">View more</a>
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
                                    <label class="stock bg-danger">New</label>


                                    @if ($productsItem->productImages->count() > 0)
                                    <a href="{{ url('/collections/'.$productsItem->category->slug.'/'.$productsItem->slug) }}">
                                        <img src="{{ asset($productsItem->productImages[0]->image) }}" alt="{{ $productsItem->name }}">
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
                                        <span class="selling-price">₱{{ $productsItem->selling_price }}</span>
                                        <span class="original-price">₱{{ $productsItem->original_price }}</span>
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

@endsection

@section('script')

<script>
    $('.four-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    });
</script>

@endsection
