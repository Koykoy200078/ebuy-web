<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductImage;
use App\Models\ProductColor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Color;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $verifiedOnly = $request->input('verifiedOnly', false);
    
        if ($verifiedOnly) {
            $products = Product::where('status', 1)->get();
        } else {
            $products = Product::all();
        }
    
        return view('admin.products.index', compact('products', 'verifiedOnly'));
    }
    
    

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::where('status', '0')->get();
        return view('admin.products.create', compact('categories', 'brands', 'colors'));
    }

    public function store(ProductFormRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->colors == null) {
            return redirect('/admin/products/create3')->with('message', 'The Product color field is required.');
        }

        $category = Category::findOrFail($validatedData['category_id']);
        $product = $category ->products()->create([
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
            'trending' => $request ->trending == true ? '1':'0',
            'featured' => $request ->featured == true ? '1':'0',
            'status' => $request ->status == true ? '1':'0',
            'meta_title' => $validatedData['meta_title'],
            'meta_description' => $validatedData['meta_description'],
            'meta_keyword' => $validatedData['meta_keyword'],
        ]);

        if($request->hasFile('image')){
            $uploadPath = 'uploads/products/';

            $i = 1;
            foreach($request->file('image') as $imageFile){
                $extention = $imageFile->getClientOriginalExtension();
                $filename = time().$i++.'.'.$extention;
                $imageFile->move($uploadPath,$filename);
                $finalImagePathName = $uploadPath.$filename;

                $product->productImages()->create([
                    'product_id' => $product->id,
                    'image' => $finalImagePathName,

                ]);
            }
        }

        if($request->colors){
            foreach($request->colors as $key => $color){
                $product->productColors()->create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'quantity' => $request->colorquantity[$key] ?? 0
                ]);
            }
        }
        return redirect('/admin/products')->with('message', 'Product Added Succesfully');
    }

    public function edit(int $product_id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($product_id);

        $product_colors = $product->productColors->pluck('color_id')->toArray();
        $colors = Color::whereNotIn('id', $product_colors)->get();

        return view('admin.products.edit', compact('categories', 'brands', 'product', 'colors'));
    }

    public function update(Request $request, int $product_id)
    {
        $countInDBImange = ProductImage::where('product_id', $product_id)->count();

        $countUploadImage = 0;
        if($request->hasFile('image')){
            foreach($request->file('image') as $imageFile){
                $countUploadImage++;
            }
        }
        $totalImageProduct = $countUploadImage+$countInDBImange;
        if($totalImageProduct <= 5)
        {

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
                    'max:5'
                ],
                'image.*' => [
                    'image',
                    'mimes:png,jpg,jpeg'
                    
                ],
            ]);

            $product = Category::findOrFail($validatedData['category_id'])
                            ->products()->where('id', $product_id)->first();
            if($product)
            {
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
                    'trending' => $request ->trending == true ? '1':'0',
                    'featured' => $request ->featured == true ? '1':'0',
                    'status' => $request ->status == true ? '1':'0',
                    'meta_title' => $validatedData['meta_title'],
                    'meta_description' => $validatedData['meta_description'],
                    'meta_keyword' => $validatedData['meta_keyword'],
                ]);

                if($request->hasFile('image')){
                    $uploadPath = 'uploads/products/';

                    $i = 1;
                    foreach($request->file('image') as $imageFile){
                        $extention = $imageFile->getClientOriginalExtension();
                        $filename = time().$i++.'.'.$extention;
                        $imageFile->move($uploadPath,$filename);
                        $finalImagePathName = $uploadPath.$filename;

                        $product->productImages()->create([
                            'product_id' => $product->id,
                            'image' => $finalImagePathName,

                        ]);
                    }
                }

                if($request->colors){
                    foreach($request->colors as $key => $color){
                        $product->productColors()->create([
                            'product_id' => $product->id,
                            'color_id' => $color,
                            'quantity' => $request->colorquantity[$key] ?? 0
                        ]);
                    }
                }

                return redirect('/admin/products')->with('message', 'Product Updated Succesfully');

            }
            else
            {
                return redirect('admin/products')->with('message', 'No Such Product Id Found');
            }
        }
        else 
        {
            return redirect('admin/products/'.$product_id.'/edit')->with('messageError', 'You cant upload more than 5 images');
        }
    }

    public function destroyImage(int $product_image_id)
    {
        $productImage = ProductImage::findOrFail($product_image_id);
        if(File::exists($productImage->image)){
            File::delete($productImage->image);
        }
        $productImage->delete();
        return redirect()->back()->with('message', 'Product Image Deleted');

    }

    public function destroy(int $product_id)
    {
        $product = Product::findOrFail($product_id);
       if($product->productImages){
            foreach($product->productImages as $image){
                if(File::exists($image->image)){
                    File::delete($image->image);
                }
            }
       }
       $product->delete();
       return redirect()->back()->with('message', 'Product Deleted with  all its Image');

    }


    public function updateProdColorQty(Request $request, $prod_color_id)
    {
        $productColorData = Product::findOrFail($request->product_id)
                                    ->productColors()->where('id', $prod_color_id)->first();
        $productColorData->update([
            'quantity' => $request->qty
        ]);
        return response()->json(['message'=>'Product Color Qty updated']);
    }

    public function deleteProdColor($prod_color_id)
    {
        $prodColor = ProductColor::findOrFail($prod_color_id);
        $prodColor->delete();
        return response()->json(['message'=>'Product Color Deleted']);

    }

}
