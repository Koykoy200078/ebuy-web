<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Orderitem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use App\Mail\InvoiceOrderMailable;
use Illuminate\Support\Facades\Mail;
use App\Models\ActivityLog;

class OrderController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on My Order';
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        // dd($orders);
        return view('frontend.orders.index', compact('orders'));
    }


    public function show($orderId)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $orderId)->first();
        if ($order) {
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' clicked on view order Id: '. $order->id ;
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
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
            $test = Orderitem::whereIn('id', $product_status)->update(['status_message' => 'completed']);



            $order->update([
                'confirm' => 'Delivery Complete'
                
            ]);
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' update on Delivery Id:'.$order->id. '.Delivery Status: Completed ';
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return redirect('orders/'.$orderId)->with('message', 'Order Status Updated');
        }
        else
        {
            return redirect('orders/'.$orderId)->with('message','Order Id not found');
        }

    }

    public function viewInvoice(int $orderId)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $orderId)->first();
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on view invoice order Id: '. $order->id ;
            
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $order = Order::findOrfail($orderId);
        return view('frontend.invoice.generate-invoice', compact('order'));


    }


    public function generateInvoice(int $orderId)
    {
        $order = Order::findOrfail($orderId);
        $data = ['order' => $order];

        $pdf = Pdf::loadView('frontend.invoice.generate-invoice', $data);

        $todayDate = Carbon::now()->format('d-m-Y');
        return $pdf->download('invoice-'.$order->id.'-'.$todayDate.'.pdf');
    }

    public function mailInvoice(int $orderId)
    {

        $order = Order::findOrfail($orderId);
        // Mail::to("$order->email")->send(new InvoiceOrderMailable($order));
        Mail::to("$order->seller_email")->send(new InvoiceOrderMailable($order));

        return redirect('orders/'.$orderId)->with('message', 'Invoice has been sent to '.$order->seller_email);
        try{
            $order = Order::findOrfail($orderId);
            Mail::to("$order->seller_email")->send(new InvoiceOrderMailable($order));
            return redirect('orders/'.$orderId)->with('message', 'Invoice has been sent to '.$order->seller_email);

        }catch(\Exception $e){

            return redirect('orders/'.$orderId)->with('message', 'Something went wrong!'.$e);
        }
    }

}
