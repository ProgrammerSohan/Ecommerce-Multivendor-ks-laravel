<?php

namespace App\Http\Controllers\Backend;


use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
class OrderController extends Controller
{
     public function PendingOrder(){
        $orders = Order::where('status','pending')->orderBy('id','DESC')->get();
        return view('backend.orders.pending_orders',compact('orders'));

     }//end method

     public function AdminOrderDetails($order_id){
        $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
        $orderItem= OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

        return view('backend.orders.admin_order_details',compact('order','orderItem'));

     }//end method

     public function AdminConfirmedOrder(){
         $orders = Order::where('status','confirm')->orderBy('id','DESC')->get();
         return view('backend.orders.confirmed_orders',compact('orders'));

     }//end method

     public function AdminProcessingOrder(){
         $orders = Order::where('status','processing')->orderBy('id','DESC')->get();
         return view('backend.orders.processing_orders',compact('orders'));

     }//end method

     public function AdminDeliveredOrder(){
         $orders = Order::where('status','delivered')->orderBy('id','DESC')->get();
         return view('backend.orders.delivered_orders',compact('orders'));

     }//end method

     public function PendingToConfirm($order_id){
        Order::findOrFail($order_id)->update(['status' => 'confirm']);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.confirmed.order')->with($notification);

     }//end method

     public function ConfirmToProcess($order_id){
        Order::findOrFail($order_id)->update(['status' => 'processing']);

        $notification = array(
            'message' => 'Order Processing Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.processing.order')->with($notification);

     }//end method

     public function ProcessToDelivered($order_id){
        Order::findOrFail($order_id)->update(['status' => 'delivered']);

        $notification = array(
            'message' => 'Order Delivered Successfully',
            'alert-type'=> 'success'

        );
        return redirect()->route('admin.delivered.order')->with($notification);

     }//end method

        public function AdminInvoiceDownload($order_id){

            $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
            $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

            $pdf = Pdf::loadView('backend.orders.admin_order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
                    'tempDir' => public_path(),
                    'chroot' => public_path(),
            ]);
            return $pdf->download('invoice.pdf');

        }// End Method


}
