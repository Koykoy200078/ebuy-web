<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog;

class CommentProductController extends Controller
{
   
    public function store(Request $request)
    {
        if(Auth::check())
        {
            $validator = Validator::make($request->all(), [
                'comment_body' => 'string|max:30',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('message', 'Commant area is mandentory');
            }
            $product = Product::where('id', $request->id)->where('status', 0)->first();
            
            if($product)
            {
                if (Auth::check()) {
                    $user = Auth::user();
                    $description = '' . $user->name . ' commenting on Product Id '. $product->id. 'Comment:'.$request->comment_body;
                    
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'description' => $description,
                    ]);
                }
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
        if (Auth::check()) {
            $user = Auth::user();
            $comment = ProductComment::where('id', $request->comment_id)
                ->where('user_id', $user->id)
                ->first();
    
            if ($comment) {
    
                $description = '' . $user->name . ' deleting comment. Comment Id: ' . $comment->id;
    
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
                $comment->delete();
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Comment Deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Delete this comment'
            ]);
        }
    }
    
    
}
