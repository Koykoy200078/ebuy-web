@extends('layouts.app')

@section('content')

<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                    @if(session('message'))
                            <h4 class="alert alert-success mb-2">{{session('message')}}</h4>
                        @endif
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Products
                            <a href="{{ url('products') }}" class="btn btn-danger btn-sm text-white float-end">
                                Back
                            </a>
                        </h3>
                    </div>
                    <div class="card-body">


                        @if ($errors->any())
                        <div class="alert alert-warning">
                            @foreach ($errors->all() as $error)
                                <div>{{$error}}</div>
                            @endforeach
                        </div>

                        @endif

                        <form action="{{ url('products/'.$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                                        Home
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seotag-tab" data-bs-toggle="tab" data-bs-target="#seotag-tab-pane" type="button" role="tab" aria-controls="seotag-tab-pane" aria-selected="false">
                                        SEO Tags
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="false">
                                        Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-tab-pane" type="button" role="tab" aria-controls="image-tab-pane" aria-selected="false">
                                        Product Image
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="color-tab" data-bs-toggle="tab" data-bs-target="#color-tab-pane" type="button" role="tab" aria-controls="color-tab-pane" aria-selected="false">
                                        Product Color
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                    <div class="mb-3">
                                        <label>Select Category</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)

                                            <option value="{{ $category ->id }}" {{ $category ->id == $product->category_id ? 'selected':'' }}>
                                                {{ $category ->name }}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Product Name</label>
                                        <input type="text" name="name" value="{{ $product->name }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Product Slug</label>
                                        <input type="text" name="slug" value="{{ $product->slug }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Select Brand</label>
                                        <select name="brand" class="form-control">
                                            <option value="{{ $product ->brand }}">{{ $product ->brand }}</option>
                                            <option value="Reycle">Reycle</option>
                                            <option value="NotReycle">Not Reycle</option>

                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Small Description (500)</label>
                                        <textarea name="small_description" rows="4" class="form-control">{{ $product->small_description }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" rows="4" class="form-control">{{ $product->description }}</textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade border p-3" id="seotag-tab-pane" role="tabpanel" aria-labelledby="seotag-tab" tabindex="0">

                                    <div class="mb-3">
                                        <label>Meta Title</label>
                                        <input type="text" name="meta_title" value="{{ $product->meta_title }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Meta Description</label>
                                        <textarea name="meta_description" rows="4"  class="form-control">{{ $product->meta_description }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Meta Keyword</label>
                                        <textarea name="meta_keyword" rows="4"  class="form-control">{{ $product->meta_keyword }}</textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade border p-3" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label>Original Price</label>
                                                <input type="number" name="original_price" value="{{ $product->original_price }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label>Selling Price</label>
                                                <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade border p-3" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">
                                    <div class="mb-3">
                                        <label>Upload Product Images</label>
                                        <input type="file" name="image[]" multiple class="form-control">
                                    </div>
                                    <div>
                                        @if($product->productImages)
                                        <div class="row">
                                            @foreach ($product ->productImages as $image)
                                            <div class="col-md-2">
                                                <img src="{{ asset($image->image) }}" style="width: 80px;height:80px;"
                                                class="me-4 border" alt="Img" />
                                                <a href="{{ url('admin/product-image/'.$image->id.'/delete') }}" class="d-block">Remove</a>
                                            </div>
                                            @endforeach
                                        </div>



                                        @else
                                        <h5>No Image Added</h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade border p-3" id="color-tab-pane" role="tabpanel" aria-labelledby="color-tab" tabindex="0">
                                    <div class="mb-3">
                                        <h4>Add Product Color</h4>
                                        <label>Select Color</label>
                                        <hr/>
                                        <div class="row">
                                            @forelse ($colors as $coloritem)
                                                <div class="col md-3">

                                                    <div class="p-2 border mb-3">
                                                    Color: <input type="checkbox" name="colors[ {{ $coloritem->id }} ]" value="{{ $coloritem->id }}"/>
                                                    {{ $coloritem->name }}
                                                    <br/>
                                                    Quantity: <input type="number" name="colorquantity[ {{ $coloritem->id }} ]" style="withd:70px; border:1px solid"/>
                                                    </div>

                                                </div>
                                            @empty
                                            <div class="col-md-12">
                                                <h1>No colors found</h1>
                                            </div>
                                            @endforelse

                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Color Name</th>
                                                    <th>Quantity</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->productColors as $prodColor)
                                                <tr class='prod-color-tr'>
                                                    <td>
                                                        @if ($prodColor->color)
                                                        {{ $prodColor->color->name }}
                                                        @else
                                                        No Color Found
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-3" style="width:150px">
                                                            <input type="text" value="{{ $prodColor->quantity }}" class="productColorQuantity form-control form-control-sm">
                                                            <button type="button" value="{{ $prodColor->id }}" class="updateProductColorBtn btn btn-primary btn-sm text-white">Update</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" value="{{ $prodColor->id }}" class="deleteProductColorBtn btn btn-danger btn-sm text-white">Delete</button>

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection


@section('scripts')
<script>
    $(document).ready(function (){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.updateProductColorBtn', function () {

            var product_id = "{{ $product->id }}";
            var prod_color_id = $(this).val();
            var qty = $(this).closest('.prod-color-tr').find('.productColorQuantity').val();
            // alert(prod_color_id);

            if(qty <= 0){
                alert('Quantity is required');
                return false;
            }

            var data = {
                'product_id' : product_id,
                'qty' : qty
            };

            $.ajax({
                type: "POST",
                url: "/admin/product-color/"+prod_color_id,
                data: data,
                success: function (response) {
                    alert(response.message)
                }
            });

        });

        $(document).on('click', '.deleteProductColorBtn', function () {

            var prod_color_id = $(this).val();
            var thisClick = $(this);

            $.ajax({
                type: "GET",
                url: "/admin/product-color/"+prod_color_id+"/delete",
                success: function (response) {
                    thisClick.closest('.prod-color-tr').remove();
                    alert(response.message);
                }
            });
        });


    });
</script>

@endsection


