<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\Orderitem;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status', '0')->get();

        
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
    
    $trendingProducts = Product::whereIn('id', $sold->pluck('product_id'))
        ->latest()
        ->take(15)
        ->get();
    
    




        $newArrivalProducts = Product::latest()->where("status", '0')->take(14)->get();
        $featuredProducts = Product::where('featured', '1')->latest()->take(14)->get();
        return view ('frontend.index', compact('sliders', 'trendingProducts', 'newArrivalProducts', 'featuredProducts', 'sold'));
    }

    public function searchProducts(Request $request)
    {
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
        if($request->search)
        {
            $searchProducts = Product::where('name','Like','%'.$request->search.'%')->latest()->paginate(15);
            return view('frontend.pages.search', compact('searchProducts', 'sold'));
        }
        else
        {
            return redirect()->back()->with('message', 'Empty Search');
        }
    }

    public function newArrival()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on New Arrival';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
        $newArrivalProducts = Product::latest()->where("status", "0")->take(16)->get();
        return view ('frontend.pages.new-arrival', compact('newArrivalProducts', 'sold'));

    }
    public function featuredProducts()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on Featured Product';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
        $featuredProducts = Product::where('featured', '1')->latest()->get();
        return view ('frontend.pages.featured-products', compact('featuredProducts', 'sold'));

    }

    public function categories()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on Category';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
        $categories = Category::where('status','0')->get();
        return view('frontend.collections.category.index', compact('categories', 'sold'));
    }

    public function products($category_slug)
    {
        
        $category = Category::where('slug',$category_slug)->first();
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on category '. $category->name;
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $sold = OrderItem::groupBy('product_id')
        ->selectRaw('product_id, SUM(quantity) as total_quantity')
        ->orderByDesc('total_quantity')
        ->get();
        if($category){

            return view('frontend.collections.products.index' , compact('category', 'sold'));

        }else{
            return redirect()->back();
        }
    }

    public function productView(string $category_slug, string $product_slug)
    {
        $category = Category::where('slug',$category_slug)->first();

       
        if($category){

            $product = $category->products()->where('slug', $product_slug)->where('status', '0')->first();
            if($product)
            {
                if(auth()->user())
                {


                $userId = auth()->user()->id;
                $productId = $product->id;
        
                if (Auth::check()) {
                    $user = Auth::user();
                    $description = '' . $user->name . ' clicked on Product Id:'. $productId;
                    
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'description' => $description,
                    ]);
                }
                // return view ($productId );
                $comment = Orderitem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('status_message', 'completed')
                ->value('status_message');
                // return view ($comment );
                }
                else{
                    $productId = $product->id;
        
                    // return view ($productId );
                    $comment = Orderitem::where('user_id', 0)
                    ->where('product_id', $productId)
                    ->where('status_message', 'completed')
                    ->value('status_message');
                }
                return view('frontend.collections.products.view', compact('product', 'category', 'comment'));

            }else{
                return redirect()->back();
            }


        }else{
            return redirect()->back();
        }
    }

    public function thankyou()
    {
        return view('frontend.thank-you');
    }
}
