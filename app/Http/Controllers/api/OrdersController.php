<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceOrderMailable;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Orderitem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $description = "{$user->name} clicked on My Order";
            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);

            $orders = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json(['orders' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show($orderId)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $orderId)->first();
        if ($order) {
            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' clicked on view order Id: ' . $order->id;

                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }
            return response()->json(['order' => $order]);
        } else {
            return response()->json(['message' => 'No Order Found'], 404);
        }
    }

    public function updateOrderStatus(int $orderId, Request $request)
    {
        $order = Order::where('id', $orderId)->first();

        if ($order) {
            $product_status = Orderitem::where('order_id', $orderId)->get('id');
            $test = Orderitem::whereIn('id', $product_status)->update(['status_message' => 'completed']);

            $order->update([
                'confirm' => 'Delivery Complete'
            ]);

            if (Auth::check()) {
                $user = Auth::user();
                $description = '' . $user->name . ' update on Delivery Id:' . $order->id . '.Delivery Status: Completed ';

                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => $description,
                ]);
            }

            return response()->json(['message' => 'Order Status Updated']);
        } else {
            return response()->json(['message' => 'Order Id not found'], 404);
        }
    }

    public function viewInvoice(int $orderId)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $orderId)->first();
        if (Auth::check()) {
            $user = Auth::user();
            $description = '' . $user->name . ' clicked on view invoice order Id: ' . $order->id;

            ActivityLog::create([
                'user_id' => $user->id,
                'description' => $description,
            ]);
        }
        $order = Order::findOrFail($orderId);
        return response()->json(['order' => $order]);
    }


    public function generateInvoice(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        $data = ['order' => $order];

        $pdf = Pdf::loadView('frontend.invoice.generate-invoice', $data);

        $todayDate = Carbon::now()->format('d-m-Y');
        return $pdf->download('invoice-' . $order->id . '-' . $todayDate . '.pdf');
    }

    public function mailInvoice(int $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            Mail::to("$order->seller_email")->send(new InvoiceOrderMailable($order));
            return response()->json(['message' => 'Invoice has been sent to ' . $order->seller_email]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!' . $e], 500);
        }
    }
}
