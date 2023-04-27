<div id="footer">
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="footer-heading">{{ $appSetting->website_name ?? 'website name'}}</h4>
                    <div class="footer-underline"></div>
                    <p style="text-align: justify; text-justify: inter-word;">
                        Ebuy is an online platform that allows individuals to buy and sell various reusable and 
                        recyclable items. Whether you are looking to declutter your home, find a unique item, or contribute 
                        to a more sustainable future, Ebuy offers an easy and convenient solution. With a wide range of 
                        categories and products, Ebuy is a great place to find affordable and eco-friendly options. Join the 
                        Ebuy community today and start making a positive impact!
                    </p>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading">Quick Links</h4>
                    <div class="footer-underline"></div>
                    <div class="mb-2"><a href="{{ url('/') }}">Home</a></div>
                    <div class="mb-2"><a href="" >About Us</a></div>
                    <div class="mb-2"><a href="" >Contact Us</a></div>
                </div>
                <div class="col-md-2">
                    <h4 class="footer-heading">Shop Now</h4>
                    <div class="footer-underline"></div>
                    <div class="mb-2"><a href="{{ url('collections')}}" >Collections</a></div>
                    <div class="mb-2"><a href="{{ url('/')}}" >Trending Products</a></div>
                    <div class="mb-2"><a href="{{ url('new-arrivals')}}" >New Arrivals Products</a></div>
                    <div class="mb-2"><a href="{{ url('featured-products')}}" >Featured Products</a></div>
                    <div class="mb-2"><a href="{{ url('cart')}}" >Cart</a></div>
                </div>
                  
                <div class="col-md-3">
                    <h4 class="footer-heading">Reach Us</h4>
                    <div class="footer-underline"></div>
                    <div class="mb-2">
                        <p>
                            <i class="fa fa-map-marker"></i>
                            <strong> Address : </strong>
                            {{ $appSetting->address ?? ' 5171 W Campbell Ave undefined Kent, Utah 53127 United States' }}
                          </p>
                          
                    </div>
                    <div class="mb-2">
                        <a href="">
                            <strong> Phone : </strong>
                            <i class="fa fa-phone"></i> {{ $appSetting->phone1 ?? ' 098 9999 9988'}}
                        </a>
                    </div>
                    <div class="mb-2">
                        <a href="" >
                            <strong> Email : </strong>
                            <i class="fa fa-envelope"></i>{{ $appSetting->email1 ?? ' email_123@yahoo.com'}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <p class=""> &copy; 2023 </p>
                </div>
                <div class="col-md-4">
                    <div class="social-media">
                        Get Connected:
                        <a href="{{ $appSetting->email1 ?? 'email 1'}}" class="text-white" target="_blank"><i class="fa fa-email"></i></a>
                        <a href="{{ $appSetting->facebook ?? 'facebook 1'}}" class="text-white" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="{{ $appSetting->twitter ?? 'twitter 1'}}" class="text-white" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="{{ $appSetting->instagram ?? 'instagram 1'}}" class="text-white" target="_blank"><i class="fa fa-instagram"></i></a>
                        <a href="{{ $appSetting->youtube ?? 'youtube 1'}}" class="text-white" target="_blank"><i class="fa fa-youtube"></i></a>

                     
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>


@section('script')

<script>
  window.onload = function() {
    var footer = document.getElementById("footer");
    var footerHeight = footer.offsetHeight;
    var windowHeight = window.innerHeight;
    var bodyHeight = document.body.offsetHeight;
    if (bodyHeight < windowHeight) {
      footer.style.position = "fixed";
      footer.style.bottom = "0";
      footer.style.width = "100%";
    }
  };
    
</script>

@endsection

