<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\User;
use App\Models\Orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceOrderMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SellerInvoiceOrderMailable;
use App\Models\ActivityLog;


class ProductStatusrController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on Delivery ';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $orders = Order::where('product_user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        // dd($orders);
        return view('frontend.sellings.index', compact('orders'));


        //GEt only the item that you selling
    }


    public function show($orderId)
    {
        $order = Order::where('product_user_id', Auth::user()->id)->where('id', $orderId)->first();
        if ($order) {
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' clicked on Delivery Id:'.$order->id;
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return view('frontend.sellings.view', compact('order'));
        } else {
            return redirect()->back()->with('message', 'No Order Found');
        }
    }

    public function updateOrderStatus(int $orderId, Request $request)
    {
        $order = Order::where('id', $orderId)->first();

        // return view($orderId);

        // $product_count = OrderItem::all()->where("order_id", 8);
        // $product_status = Orderitem::all()->where('order_id', $orderId)->get('order_id', "9");
        // $product_status = Orderitem::where('order_id', $orderId)->get('id');
        // $test = Orderitem::whereIn('id', $product_status)->update(['price' => '4']);
        // // return view($product_count);

        // $test = 4;

        
        // $product_status->update([
        //     'quantity' => $test
            
        // ]);


        // testing phase

        if($order)
        {
            $product_status = Orderitem::where('order_id', $orderId)->get('id');
            $test = Orderitem::whereIn('id', $product_status)->update([
                'status_message' => $request->order_status == 'completed' ? 'Item Arrive' : ($request->order_status ?? $order->status_message)
            ]);
            

            $order->update([
                'status_message' => $request->order_status  ?? $order->status_message
                
            ]);
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' update on Delivery Id:'.$order->id. '.Delivery Status: '. $request->order_status  ?? $order->status_message;
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return redirect('product-status/'.$orderId)->with('message', 'Order Status Updated');
        }
        else
        {
            return redirect('product-status/'.$orderId)->with('message','Order Id not fsound');
        }

    }

    public function viewInvoice(int $orderId)
    {
        $order = Order::findOrfail($orderId);
        return view('frontend.sellerInvoice.generate-invoice', compact('order'));


    }


    public function generateInvoice(int $orderId)
    {
        $order = Order::findOrfail($orderId);
        $data = ['order' => $order];

        $pdf = Pdf::loadView('frontend.sellerInvoice.generate-invoice', $data);

        $todayDate = Carbon::now()->format('d-m-Y');
        return $pdf->download('frontend.sellerInvoice-'.$order->id.'-'.$todayDate.'.pdf');
    }

    public function mailInvoice(int $orderId)
    {

        $order = Order::findOrfail($orderId);
        Mail::to("$order->email")->send(new InvoiceOrderMailable($order));
        return redirect('product-status/'.$orderId)->with('message', 'Invoice has been sent to '.$order->email);
        try{
            $order = Order::findOrfail($orderId);
            Mail::to("$order->email")->send(new InvoiceOrderMailable($order));
            return redirect('product-status/'.$orderId)->with('message', 'Invoice has been sent to '.$order->email);

        }catch(\Exception $e){

            return redirect('product-status/'.$orderId)->with('message', 'Something went wrong!'.$e);
        }
    }

}
