@extends('layouts.app')

@section('title', 'My Sales')

@section('content')

    <div class="py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 class="mb-4">My Sales</h4>
                        <hr>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-body bg-primary text-white mb-3"  onclick="toggleDiv1()">
                                    <label for="">Total Sales</label>
                                    <h1>₱ {{ number_format($totalPrice, 2, '.', ',') }}</h1>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-success text-white mb-3"  onclick="toggleDiv2()">
                                    <label for="">Today Sales</label>
                                    <h1>₱ {{number_format($totalPriceToday, 2, '.', ',')}}</h1>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-warning text-white mb-3" onclick="toggleDiv3()">
                                    <label for="">This Month Sales</label>
                                    <h1>₱ {{number_format($totalPriceMonth, 2, '.', ',')}}</h1>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-body bg-danger text-white mb-3" onclick="toggleDiv4()">
                                    <label for="">Year Sales</label>
                                    <h1>₱ {{ number_format($totalPriceYear, 2, '.', ',')}}</h1>
                                </div>
                            </div>
                           
                        </div>
                        
                        <div id="myDiv1">
                            <table class="table table-borderd table-striped">
                                @php
                                    $totalPrice = 0; // Initialize the total price variable
                                @endphp
                                    
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Prices(5%)tax</th>
                                        <th>Date</th>
                                        {{-- <th>Status</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mysalesproduct as $productcheking)
                                    <tr>
                                        <td>{{ $productcheking->product->name }} 
                                            @if ($productcheking->productColor)
                                                @if ($productcheking->productColor->color)
                                                    <div class="color-info">Color: {{ $productcheking->productColor->color->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $productcheking->quantity }}</td>
                                        <td>₱{{ number_format($productcheking->price* 0.95, 2, '.', ',') }}</td>
                                        @php
                                            $totalPrice += $productcheking->quantity *  $productcheking->price; // Add 95% of the current product's price to the total price
                                        @endphp
                                    <td>{{$productcheking->created_at}}</td>
                                    </tr>
                                
                                    @empty
                                    <tr>
                                        <td colspan="7">No Products Available</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td  width="20%"  class="total-prize" >Total Prize: ₱{{ number_format($totalPrice * 0.95, 2, '.', ',') }}</td>
                                        <td width="9%"></td>

                                    </tr>
                                </tbody>
                                
                            </table>
                            
                            <div>
                                {{ $mysalesproduct->links() }}
                            </div>
                        </div>

                        <div id="myDiv2">
                            <table class="table table-borderd table-striped">
                                @php
                                    $totalPrice = 0; // Initialize the total price variable
                                @endphp
                                    
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Prices(5%)tax</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todaySales as $productcheking)
                                    <tr>
                                        <td>{{ $productcheking->product->name }} 
                                            @if ($productcheking->productColor)
                                                @if ($productcheking->productColor->color)
                                                    <div class="color-info">Color: {{ $productcheking->productColor->color->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $productcheking->quantity }}</td>
                                        <td>₱{{ number_format($productcheking->price* 0.95, 2, '.', ',') }}</td>
                                        @php
                                            $totalPrice += $productcheking->quantity *  $productcheking->price; // Add 95% of the current product's price to the total price
                                        @endphp
                                    <td>{{$productcheking->created_at}}</td>
                                    </tr>
                                
                                    @empty
                                    <tr>
                                        <td colspan="7">No Products Available</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td  width="20%"  class="total-prize" >Total Prize: ₱{{ number_format($totalPrice * 0.95, 2, '.', ',') }}</td>
                                        <td width="9%"></td>

                                    </tr>
                                </tbody>
                                
                            </table>
                            
                            <div>
                                {{ $todaySales->links() }}
                            </div>
                        </div>

                        <div id="myDiv3">
                            <table class="table table-borderd table-striped">
                                @php
                                    $totalPrice = 0; // Initialize the total price variable
                                @endphp
                                    
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Prices(5%)tax</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($monthSales as $productcheking)
                                    <tr>
                                        <td>{{ $productcheking->product->name }} 
                                            @if ($productcheking->productColor)
                                                @if ($productcheking->productColor->color)
                                                    <div class="color-info">Color: {{ $productcheking->productColor->color->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $productcheking->quantity }}</td>
                                        <td>₱{{ number_format($productcheking->price* 0.95, 2, '.', ',') }}</td>
                                        @php
                                            $totalPrice += $productcheking->quantity *  $productcheking->price; // Add 95% of the current product's price to the total price
                                        @endphp
                                    <td>{{$productcheking->created_at}}</td>
                                    </tr>
                                
                                    @empty
                                    <tr>
                                        <td colspan="7">No Products Available</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td  width="20%"  class="total-prize" >Total Prize: ₱{{ number_format($totalPrice * 0.95, 2, '.', ',') }}</td>
                                        <td width="9%"></td>

                                    </tr>
                                </tbody>
                                
                            </table>
                            
                            <div>
                                {{ $monthSales->links() }}
                            </div>
                        </div>
                        
                        <div id="myDiv4">
                            <table class="table table-borderd table-striped">
                                @php
                                    $totalPrice = 0; // Initialize the total price variable
                                @endphp
                                    
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Prices(5%)tax</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yearSales as $productcheking)
                                    <tr>
                                        <td>{{ $productcheking->product->name }} 
                                            @if ($productcheking->productColor)
                                                @if ($productcheking->productColor->color)
                                                    <div class="color-info">Color: {{ $productcheking->productColor->color->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $productcheking->quantity }}</td>
                                        <td>₱{{ number_format($productcheking->price* 0.95, 2, '.', ',') }}</td>
                                        @php
                                            $totalPrice += $productcheking->quantity *  $productcheking->price; // Add 95% of the current product's price to the total price
                                        @endphp
                                    <td>{{$productcheking->created_at}}</td>
                                    </tr>
                                
                                    @empty
                                    <tr>
                                        <td colspan="7">No Products Available</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td  width="20%"  class="total-prize" >Total Prize: ₱{{ number_format($totalPrice * 0.95, 2, '.', ',') }}</td>
                                        <td width="9%"></td>

                                    </tr>
                                </tbody>
                                
                            </table>
                            
                            <div>
                                {{ $yearSales->links() }}
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>

          
        </div>
    </div>
    <script>
        window.addEventListener("load", function() {
            var div2 = document.getElementById("myDiv2");
          var div3 = document.getElementById("myDiv3");
          var div4 = document.getElementById("myDiv4");
            div2.style.display = "none";
            div3.style.display = "none";
            div4.style.display = "none";
        });



        function toggleDiv1() {
          var div = document.getElementById("myDiv1");
          var div2 = document.getElementById("myDiv2");
          var div3 = document.getElementById("myDiv3");
          var div4 = document.getElementById("myDiv4");
          if (div.style.display === "none") {
            div.style.display = "block";
            div2.style.display = "none";
            div3.style.display = "none";
            div4.style.display = "none";
          } else {
            div.style.display = "block";
            div2.style.display = "none";
            div3.style.display = "none";
            div4.style.display = "none";
          }
        }

        function toggleDiv2() {
            var div = document.getElementById("myDiv1");
          var div2 = document.getElementById("myDiv2");
          var div3 = document.getElementById("myDiv3");
          var div4 = document.getElementById("myDiv4");
          if (div2.style.display === "none") {
            div.style.display = "none";
            div2.style.display = "block";
            div3.style.display = "none";
            div4.style.display = "none";
          } else {
            div.style.display = "none";
            div2.style.display = "block";
            div3.style.display = "none";
            div4.style.display = "none";
          }
        }

        function toggleDiv3() {
          var div = document.getElementById("myDiv1");
          var div2 = document.getElementById("myDiv2");
          var div3 = document.getElementById("myDiv3");
          var div4 = document.getElementById("myDiv4");
          if (div3.style.display === "none") {
            div.style.display = "none";
            div2.style.display = "none";
            div3.style.display = "block";
            div4.style.display = "none";
          } else {
            div.style.display = "none";
            div2.style.display = "none";
            div3.style.display = "block";
            div4.style.display = "none";
          }
          
        }

        function toggleDiv4() {
            var div = document.getElementById("myDiv1");
          var div2 = document.getElementById("myDiv2");
          var div3 = document.getElementById("myDiv3");
          var div4 = document.getElementById("myDiv4");
          if (div4.style.display === "none") {
            div.style.display = "none";
            div2.style.display = "none";
            div3.style.display = "none";
            div4.style.display = "block";
          } else {
            div.style.display = "none";
            div2.style.display = "none";
            div3.style.display = "none";
            div4.style.display = "block";
          }
        }
      </script>
@endsection
