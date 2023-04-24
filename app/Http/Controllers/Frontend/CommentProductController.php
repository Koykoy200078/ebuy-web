<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CommentProductController extends Controller
{
   
    public function store(Request $request)
    {
        if(Auth::check())
        {
            $validator = Validator::make($request->all(), [
                'comment_body' => 'string',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('message', 'Commant area is mandentory');
            }
            $product = Product::where('id', $request->id)->where('status', 0)->first();
            
            if($product)
            {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'comment_body' => $request->comment_body
                ]);
                return redirect()->back()->with('message', 'Comment Added');

            }
            else
            {
                return redirect()->back()->with('message', 'No Such Product Found');

            }
        }
        else
        {
            return redirect('/')->with('message', 'Login first to comment');
        }
    }


    public function destroy(Request $request)
    {
        if(Auth::check())
        {   
            $cooment = ProductComment::where('id', $request->comment_id)
                ->where('user_id',Auth::user()->id)
                ->first();

            if($comment)
            {   
                $cooment->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Comment Deleted Succesfully'
                ]);
    

            }
            else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ]);
            }
          
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Delete this comment'
            ]);

        }
    }
    
}
