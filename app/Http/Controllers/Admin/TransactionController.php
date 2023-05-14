<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\TransactionFormRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceOrderMailable;
use App\Mail\DcSellerInvoiceOrderMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::paginate(10);;

        return view('admin.transaction-doc.index', compact('transactions'));
    }

    public function create()
    {
        // $categories = Category::all();
        // $brands = Brand::all();
        // $colors = Color::where('status', '0')->get();
        return view('admin.transaction-doc.create');
    }

    public function store(TransactionFormRequest $request)
    {
       
        $validatedData = $request->validated();
        
        $trackingOrder = Order::where('tracking_no', $validatedData['tracking_number'])->first();
        $paymentOrder = Order::where('payment_id', $validatedData['payment_id'])->first();
        
        if (!$trackingOrder || !$paymentOrder || $trackingOrder->id !== $paymentOrder->id) {
            // Orders don't match or not found, so ignore the process
            return redirect()->back()->with('message', 'The tracking number and payment ID do not match.');
        
        }
        
        // Continue with the rest of your code to create the Transaction record
        // ...
        
        $orderid = Order::where('tracking_no', $validatedData['tracking_number'])->value('id');
        // return
        $order = Order::where('payment_id', $validatedData['payment_id'])->first();

        
        if($request->hasFile('image_pdf')){
                $file = $request->file('image_pdf');
                $ext = $file->getClientOriginalExtension();
                $filename = time().'.'.$ext;

                $file->move('uploads/transaction/', $filename);
                $validatedData['image_pdf'] = "uploads/transaction/$filename";
        }


        Transaction::create([
            'order_id' => $orderid,

            'tracking_number' => $validatedData['tracking_number'],
            'payment_id' => $validatedData['payment_id'],
            'image_pdf' => $validatedData['image_pdf'],
        ]);


        return redirect('admin/save-transaction')->with('message', 'Transaction Added Successfully');
    }

    public function destroy(Transaction $transaction)
    {
        if($transaction->count() > 0){

            $destination = $transaction->image;
            if(File::exists($transaction)){
                File::delete($transaction);
            }
    
            $transaction->delete();
            return redirect('admin/save-transaction')->with('message', 'transaction Deleted Successfully');
            }
    
            return redirect('admin/save-transaction')->with('message', 'Something Went Wrong');

    }
    public function edit(int $transaction)
    {
        $transaction = Transaction::findOrFail($transaction);

        return view('admin.transaction-doc.edit', compact('transaction'));
    }
    public function update(TransactionFormRequest $request, Transaction $transaction)
    {
 
            $validatedData = $request->validated();
            // return view($transaction)
            // if($transaction)
            // {
                if($request->hasFile('image_pdf')){

                        $destination = $transaction->image;
                        if(File::exists($destination)){
                            File::delete($destination);
                        }
                        $file = $request->file('image_pdf');
                        $ext = $file->getClientOriginalExtension();
                        $filename = time().'.'.$ext;

                        $file->move('uploads/transaction/', $filename);
                        $validatedData['image_pdf'] = "uploads/transaction/$filename";
                }



                Transaction::where('id',$transaction->id)->update([
            
                    'tracking_number' => $validatedData['tracking_number'],
                    'payment_id' => $validatedData['payment_id'],
                    'image_pdf' => $validatedData['image_pdf']?? $transaction->image,
                ]);


                return redirect('admin/save-transaction')->with('message', 'Slider Updated Successfully');
            // }
            // else
            // {
            // return redirect('admin/save-transaction')->with('message', 'Slider Updated Successfully');

            // }
    }
    
    public function mailInvoice(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transactioninfo = Transaction::findOrFail($transactionId);

        $orderId =Transaction::where('id',$transaction->id)->value('order_id');
        
        $transaction = Order::findOrfail($orderId);

        Mail::to("$transaction->seller_email")->send(new DcSellerInvoiceOrderMailable($transaction,$transactioninfo));
        return redirect('admin/save-transaction/'.$transactioninfo->id.'/edit')->with('message', 'Invoice has been sent to '.$transaction->seller_email);
        try{
            $transaction = Order::findOrfail($orderId);
            Mail::to("$transaction->seller_email")->send(new DcSellerInvoiceOrderMailable($transaction,$transactioninfo));
            return redirect('admin/save-transaction/'.$transactioninfo->id.'/edit')->with('message', 'Invoice has been sent to '.$transaction->seller_email);

        }catch(\Exception $e){

            return redirect('admin/save-transaction/'.$transactioninfo->id.'/edit')->with('message', 'Something went wrong!'.$e);
        }
    }
    public function viewInvoice(int $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        $orderId =Transaction::where('id',$transaction->id)->value('order_id');

        $order = Order::findOrfail($orderId);
        return view('admin.invoice.seller-generate-invoice', compact('order'));


    }


}
