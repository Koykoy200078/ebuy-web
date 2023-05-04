<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\Orderitem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        // dd($orders);
        return view('frontend.orders.index', compact('orders'));
    }


    public function show($orderId)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $orderId)->first();
        if ($order) {
            return view('frontend.orders.view', compact('order'));
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
            $test = Orderitem::whereIn('id', $product_status)->update(['status_message' => $request->order_status]);



            $order->update([
                'status_message' => $request->order_status
                
            ]);

            return redirect('orders/'.$orderId)->with('message', 'Order Status Updated');
        }
        else
        {
            return redirect('orders/'.$orderId)->with('message','Order Id not found');
        }

    }

    public function viewInvoice(int $orderId)
    {
        $order = Order::findOrfail($orderId);
        return view('invoice.generate-invoice', compact('order'));


    }


    public function generateInvoice(int $orderId)
    {
        $order = Order::findOrfail($orderId);
        $data = ['order' => $order];

        $pdf = Pdf::loadView('invoice.generate-invoice', $data);

        $todayDate = Carbon::now()->format('d-m-Y');
        return $pdf->download('invoice-'.$order->id.'-'.$todayDate.'.pdf');
    }

    public function mailInvoice(int $orderId)
    {

        $order = Order::findOrfail($orderId);
        Mail::to("$order->email")->send(new InvoiceOrderMailable($order));
        return redirect('orders/'.$orderId)->with('message', 'Invoice has been sent to '.$order->email);
        try{
            $order = Order::findOrfail($orderId);
            Mail::to("$order->email")->send(new InvoiceOrderMailable($order));
            return redirect('orders/'.$orderId)->with('message', 'Invoice has been sent to '.$order->email);

        }catch(\Exception $e){

            return redirect('orders/'.$orderId)->with('message', 'Something went wrong!'.$e);
        }
    }

}
