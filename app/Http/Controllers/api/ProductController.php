<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $sliders = Slider::where('status', '0')->get();
            $trendingProducts = Product::where('trending', '1')->latest()->take(15)->get();
            $newArrivalProducts = Product::latest()->take(14)->get();
            $featuredProducts = Product::where('featured', '1')->latest()->take(14)->get();

            $data = [
                'sliders' => [],
                'trending_products' => [],
                'new_arrival_products' => [],
                'featured_products' => []
            ];

            foreach ($sliders as $slider) {
                $sliderData = $slider->toArray();
                $sliderData['image_url'] = asset($slider->image);
                $data['sliders'][] = $sliderData;
            }

            foreach ($trendingProducts as $product) {
                $productData = $product->toArray();
                $productData['image_url'] = asset($product->productImages[0]->image);
                $data['trending_products'][] = $productData;
            }

            foreach ($newArrivalProducts as $product) {
                $productData = $product->toArray();
                $productData['image_url'] = asset($product->productImages[0]->image);
                $data['new_arrival_products'][] = $productData;
            }

            foreach ($featuredProducts as $product) {
                $productData = $product->toArray();
                $productData['image_url'] = asset($product->productImages[0]->image);
                $data['featured_products'][] = $productData;
            }

            if (empty($data['sliders']) && empty($data['trending_products']) && empty($data['new_arrival_products']) && empty($data['featured_products'])) {
                return response()->json([
                    'message' => 'No products or sliders found',
                ], 404);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductFormRequest $request)
    {
        try {
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

            if ($request->hasFile('image')) {
                $uploadPath = public_path('uploads/products/');

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, $mode = 0777, true, true);
                }

                $i = 1;
                foreach ($request->file('image') as $imageFile) {
                    $extention = $imageFile->getClientOriginalExtension();
                    $filename = time() . $i++ . '.' . $extention;
                    $imageFile->move($uploadPath, $filename);
                    $finalImagePathName = 'uploads/products/' . $filename;

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
                        'quantity' => $request->colorquantity[$key] ?? 0,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Product added successfully',
                'product' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
