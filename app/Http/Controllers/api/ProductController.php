<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\Slider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function productCount()
    {
        if (Auth::check()) {
            $productCount = Product::where('product_user_id', auth()->user()->id)->count();
        } else {
            $productCount = 0;
        }

        return response()->json([
            'productCount' => $productCount
        ]);
    }

    public function getSliders(): JsonResponse
    {
        $sliders = Slider::where('status', 0)
            ->selectRaw('id, title, image, created_at, updated_at, ' .
                'CONCAT("' . asset('') . '", image) AS image_url')
            ->get();

        return $sliders->isEmpty() ? response()->json([
            "status" => 0,
            "data" => null
        ], 200) : response()->json([
            "status" => 1,
            "data" => $sliders
        ], 200);
    }

    public function getTrendingProducts(): JsonResponse // Most sold products
    {
        $sold = OrderItem::groupBy('product_id')
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->orderByDesc('total_quantity')
            ->take(15)
            ->get();

        $trendingProducts = Product::whereIn('id', $sold->pluck('product_id'))
            ->latest('updated_at')
            ->with('productImages', 'category')
            ->get()
            ->map(function ($product) use ($sold) {
                $productData = $product->toArray();
                $productData['image_url'] = asset($product->productImages[0]->image);
                $soldQuantity = $sold->where('product_id', $product->id)->first();
                $productData['sold_quantity'] = $soldQuantity ? $soldQuantity->total_quantity : 0;
                $productData['category_slug'] = $product->category->slug;

                if ($product->quantity == 0) {
                    $productData['quantity_status'] = 'Out of stock';
                } else {
                    $productData['quantity_status'] = 'In stock';
                }

                return $productData;
            });

        return $trendingProducts->isEmpty() ? response()->json([
            "status" => 0,
            "data" => null
        ], 200) : response()->json([
            "status" => 1,
            "data" => $trendingProducts
        ], 200);
    }

    public function getNewArrivalProducts(): JsonResponse
    {
        $newArrivalProducts = Product::where('status', 0)
            ->latest('updated_at')
            ->with('productImages', 'category')
            ->take(14)
            ->get()
            ->map(function ($product) {
                $productData = $product->toArray();
                $productData['image_url'] = asset($product->productImages[0]->image);
                $productData['category_slug'] = $product->category->slug;

                if ($product->quantity == 0) {
                    $productData['quantity_status'] = 'Out of stock';
                } else {
                    $productData['quantity_status'] = 'In stock';
                }
                return $productData;
            });




        return $newArrivalProducts->isEmpty() ? response()->json([
            "status" => 0,
            "data" => null
        ], 200) : response()->json([
            "status" => 1,
            "data" => $newArrivalProducts
        ], 200);
    }

    public function productView(string $category_slug, string $product_slug)
    {
        try {
            $category = Category::where('slug', $category_slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $product = $category->products()
            ->with(['productColors' => function ($query) {
                $query->where('quantity', '>', 0)->with('color');
            }])
            ->where('slug', $product_slug)
            ->where('status', '0')
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $image_url = url($product->productImages[0]->image);

        $product_colors = $product->productColors->map(function ($item) {
            $status = 'out of stock';
            if ($item->quantity > 0) {
                $status = 'in stock';
            }
            return [
                'product_color_id' => $item->id,
                'color_name' => $item->color->name,
                'color_code' => $item->color->code,
                'quantity' => $item->quantity,
                'status' => $status,
            ];
        });

        return response()->json([
            'product' => $product->toArray(),
            'category' => $category->name,
            'image_url' => $image_url,
            'product_colors' => $product_colors,
        ], 200);
    }



    public function newArrival()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on New Arrival via Mobile App';

            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }

        $newArrivalProducts = Product::latest()->where("status", "0")->take(16)->get();

        $data = $newArrivalProducts->map(function ($product) {
            $image_url = url($product->productImages[0]->image);
            $product->image_url = $image_url; // add the image_url to the product object
            return $product;
        });

        return response()->json([
            'message' => 'Success', 'data' => $data
        ], 200);
    }

    public function featuredProducts()
    {
        $featuredProducts = Product::where('featured', '1')->latest()->get();
        $data = $featuredProducts->map(function ($product) {
            $image_url = url($product->productImages[0]->image);
            $product->image_url = $image_url; // add the image_url to the product object
            return $product;
        });
        return response()->json(['message' => 'Success', 'data' => $data], 200);
    }

    public function view()
    {
        try {
            $categories = Category::all();
            $brands = Brand::all();
            $colors = Color::where('status', '0')->get();

            if ($categories->isNotEmpty() || $brands->isNotEmpty() || $colors->isNotEmpty()) {
                return response()->json([
                    'categories' => $categories,
                    'brands' => $brands,
                    'colors' => $colors,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No categories, brands, or colors found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(ProductFormRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::findOrFail($validatedData['category_id']);
        $product = $category->products()->create([
            'category_id' => $validatedData['category_id'],
            'product_user_id' => auth()->user()->id,
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['slug']),
            'brand' => $validatedData['brand'],
            'small_description' => $validatedData['small_description'],
            'description' => $validatedData['description'],
            'original_price' => $validatedData['original_price'],
            'selling_price' => $validatedData['selling_price'],
            'quantity' => $validatedData['quantity'],
            'trending' => $request->trending == true ? '1' : '0',
            'featured' => $request->featured == true ? '1' : '0',
            'status' => $request->status == true ? '1' : '0',
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
            'meta_keyword' => $validatedData['meta_keyword'],
        ]);

        // Check if image is present in request
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            // Validate image file
            $validatedData = $request->validate([
                'image' => 'required|image|max:2048',
            ]);

            $uploadPath = 'uploads/products/';
            $extension = $imageFile->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $imageFile->move($uploadPath, $filename);
            $finalImagePathName = $uploadPath . $filename;

            $product->productImages()->create([
                'product_id' => $product->id,
                'image' => $finalImagePathName,
            ]);
        }

        if ($request->colors) {
            foreach ($request->colors as $key => $color) {
                $product->productColors()->create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'quantity' => $request->colorquantity[$key] ?? 0,
                ]);
            }
        }

        return response()->json(['message' => 'Product added successfully'], 201);
    }

    public function showProduct(int $product_id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($product_id);

        $product_colors = $product->productColors->pluck('color_id')->toArray();
        $colors = Color::whereNotIn('id', $product_colors)->get();


        $data = [
            'categories' => $categories,
            'brands' => $brands,
            'product' => $product,
            'colors' => $colors,
        ];

        $headers = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()->json($data, 200, $headers);
    }

    public function update(Request $request, int $product_id)
    {
        // validate input data

        $countInDBImange = ProductImage::where('product_id', 11)->count('product_id');
        $countUploadImage = 0;
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $countUploadImage++;
            }
        }
        $totalImageProduct = $countUploadImage + $countInDBImange;
        if ($totalImageProduct <= 3) {
            $validatedData = $request->validate([
                'category_id' => [
                    'required',
                    'integer'
                ],
                'name' => [
                    'required',
                    'string'
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'brand' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'small_description' => [
                    'required',
                    'string'
                ],
                'description' => [
                    'required',
                    'string'
                ],
                'original_price' => [
                    'required',
                    'integer'
                ],
                'selling_price' => [
                    'required',
                    'integer'
                ],
                'quantity' => [
                    'required',
                    'integer'
                ],
                'trending' => [
                    'nullable',
                ],
                'status' => [
                    'nullable',
                ],
                'meta_title' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'meta_description' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'meta_keyword' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'image' => [
                    'nullable',
                    'max:10'

                ],
                'image.*' => [
                    'image',
                    'mimes:png,jpg,jpeg'
                ],
            ]);

            $product = Category::findOrFail($validatedData['category_id'])
                ->products()->where('id', $product_id)->first();
            if ($product) {
                $product->update([
                    'category_id' => $validatedData['category_id'],
                    'name' => $validatedData['name'],
                    'slug' => Str::slug($validatedData['slug']),
                    'brand' => $validatedData['brand'],
                    'small_description' => $validatedData['small_description'],
                    'description' => $validatedData['description'],
                    'original_price' => $validatedData['original_price'],
                    'selling_price' => $validatedData['selling_price'],
                    'quantity' => $validatedData['quantity'],
                    'trending' => $request->trending == true ? '1' : '0',
                    'featured' => $request->featured == true ? '1' : '0',
                    'status' => $request->status == true ? '1' : '0',
                    'meta_title' => $validatedData['meta_title'],
                    'meta_description' => $validatedData['meta_description'],
                    'meta_keyword' => $validatedData['meta_keyword'],
                ]);

                if ($request->hasFile('image')) {
                    $uploadPath = 'uploads/products/';

                    $i = 1;
                    foreach ($request->file('image') as $imageFile) {
                        $extention = $imageFile->getClientOriginalExtension();
                        $filename = time() . $i++ . '.' . $extention;
                        $imageFile->move($uploadPath, $filename);
                        $finalImagePathName = $uploadPath . $filename;

                        $product->productImages()->create([
                            'product_id' => $product->id,
                            'image' => $finalImagePathName,

                        ]);
                    }
                }

                if ($request->colors) {
                    foreach ($request->colors as $key => $color) {
                        $product->productColors()->create([
                            'product_id' => $product->id,
                            'color_id' => $color,
                            'quantity' => $request->colorquantity[$key] ?? 0
                        ]);
                    }
                }

                return response()->json(['message' => 'Product Updated Successfully']);
            } else {
                return response()->json(['error' => 'No Such Product Id Found'], 404);
            }
        } else {
            return response()->json(['error' => 'You cant upload more than 3 images'], 400);
        }
    }




    public function destroyImage(Request $request, int $product_image_id)
    {
        $productImage = ProductImage::findOrFail($product_image_id);

        if (File::exists($productImage->image)) {
            File::delete($productImage->image);
        }

        $productImage->delete();

        return response()->json([
            'message' => 'Product image deleted successfully',
        ], 200, [
            'Content-Type' => 'application/json',
            'X-Product-Image-ID' => $product_image_id,
        ]);
    }

    public function destroy(Request $request, int $product_id)
    {
        $product = Product::findOrFail($product_id);

        if ($product->productImages) {
            foreach ($product->productImages as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
            }
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully with all its images',
        ], 200, [
            'Content-Type' => 'application/json',
            'X-Product-ID' => $product_id,
        ]);
    }

    public function updateProdColorQty(Request $request, $prod_color_id)
    {
        $productColorData = Product::findOrFail($request->product_id)
            ->productColors()->where('id', $prod_color_id)->first();

        $productColorData->update([
            'quantity' => $request->qty
        ]);

        return response()->json([
            'message' => 'Product color quantity updated successfully',
        ], 200, [
            'Content-Type' => 'application/json',
            'X-Product-Color-ID' => $prod_color_id,
        ]);
    }

    public function deleteProdColor($prod_color_id)
    {
        $prodColor = ProductColor::findOrFail($prod_color_id);
        $prodColor->delete();

        return response()->json([
            'message' => 'Product color deleted successfully',
        ], 200, [
            'Content-Type' => 'application/json',
            'X-Product-Color-ID' => $prod_color_id,
        ]);
    }
}
