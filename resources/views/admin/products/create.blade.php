@extends('layouts.admin')

@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Add Products
                    <a href="{{ url('admin/products') }}" class="btn btn-danger btn-sm text-white float-end">
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

                <form action="{{ url('admin/products') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="submit-tab" data-bs-toggle="tab" data-bs-target="#submit-tab-pane" type="button" role="tab" aria-controls="submit-tab-pane" aria-selected="false">
                                Submit Tab
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <div class="mb-3">
                                <label>Select Category</label>
                                <select name="category_id" class="form-control">
                                    @foreach ($categories as $category)

                                    <option value="{{ $category ->id }}">{{ $category ->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Product Slug</label>
                                <input type="text" name="slug" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Material Category</label>
                                <select name="brand" class="form-control">
                                    
                                
                                    <option value="Recycle">Recycle</option>
                                    <option value="New">New</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Small Description (500)</label>
                                <textarea name="small_description" rows="4" class="form-control"> </textarea>
                            </div>

                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control"> </textarea>
                            </div>

                        </div>
                        <div class="tab-pane fade border p-3" id="seotag-tab-pane" role="tabpanel" aria-labelledby="seotag-tab" tabindex="0">

                            <div class="mb-3">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Meta Description</label>
                                <textarea name="meta_description" rows="4" class="form-control"> </textarea>
                            </div>

                            <div class="mb-3">
                                <label>Meta Keyword</label>
                                <textarea name="meta_keyword" rows="4" class="form-control"> </textarea>
                            </div>

                        </div>
                        <div class="tab-pane fade border p-3" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Original Price</label>
                                        <input type="number" name="original_price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Selling Price</label>
                                        <input type="number" name="selling_price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Trending</label>
                                        <input type="checkbox" name="trending" style="width: 50px; height: 50px;"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Featured</label>
                                        <input type="checkbox" name="featured" style="width: 50px; height: 50px;"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <input type="checkbox" name="status" style="width: 50px; height: 50px;"/>
                                        Checked=Hidden,Uncheked=Visible
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade border p-3" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">
                            <div class="mb-3">
                                <label>Upload Product Images</label>
                                <input type="file" name="image[]" multiple class="form-control">
                            </div>
                        </div>
                        <div class="tab-pane fade border p-3" id="color-tab-pane" role="tabpanel" aria-labelledby="color-tab" tabindex="0">
                            <div class="mb-3">

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
                        </div>
                        <div class="tab-pane fade border p-3" id="submit-tab-pane" role="tabpanel" aria-labelledby="submit-tab" tabindex="0">
                            <div class="mb-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Confirm submission</button>
                                
                                
                            </div>
                        </div>
                    </div>
                    <div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
