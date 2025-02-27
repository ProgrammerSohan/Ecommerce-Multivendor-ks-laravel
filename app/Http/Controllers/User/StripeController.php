<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

use Auth;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class StripeController extends Controller
{
     public function StripeOrder(Request $request){

            if(Session::has('coupon')){
                $total_amount = Session::get('coupon')['total_amount'];
            }else{
                $total_amount = round(Cart::total());
            }


            \Stripe\Stripe::setApiKey('sk_test_51OjENQD6fI3thGcmRSinbYZE0LMY6jitwaIGYrfwzT09K7PryxFlJ4MkCIB7hYjbjYOUnfAGfzvmYi4wl3B8Uqw6001NhJfi9s');

            $token = $_POST['stripeToken'];
            $charge = \Stripe\Charge::create([
            'amount' => $total_amount*100,
            'currency' => 'usd',
            'description' => 'Sohan Online Shop',
            'source' => $token,
            'metadata' => ['order_id' => uniqid()],
            ]);

            //dd($charge);
            $order_id = Order::insertGetId([
                'user_id' => Auth::id(),
                'division_id' => $request->division_id,
                'district_id' => $request->district_id,
                'state_id' => $request->state_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'post_code' => $request->post_code,
                'notes' => $request->notes,
                'payment_type' => $charge->payment_method,
                'payment_method' => 'Stripe',
                'transaction_id' => $charge->balance_transaction,
                'currency' => $charge->currency,
                'amount' => $total_amount,
                'order_number' => $charge->metadata->order_id,
                'invoice_no' => 'EOS'.mt_rand(10000000,99999999),
                'order_date' => Carbon::now()->format('d F Y'),
                'order_month' => Carbon::now()->format('F'),
                'order_year' => Carbon::now()->format('Y'),
                'status'     => 'pending',
                'created_at' => Carbon::now(),

            ]);

            //start send email
            $invoice = Order::findOrFail($order_id);

            $data = [
                'invoice_no' => $invoice->invoice_no,
                'amount' => $total_amount,
                'name'   => $invoice->name,
                'email'  => $invoice->email,

             ];

             Mail::to($request->email)->send(new OrderMail($data));

            //end send email
            $carts = Cart::content();
            foreach($carts as $cart){
                OrderItem::insert([
                    'order_id'   => $order_id,
                    'product_id' => $cart->id,
                    'vendor_id'  => $cart->options->vendor,
                    'color'      => $cart->options->color,
                    'size'       => $cart->options->size,
                    'qty'        => $cart->qty,
                    'price'      => $cart->price,
                    'created_at' => Carbon::now(),

                ]);
            }//end foreach
            if(Session::has('coupon')){
                Session::forget('coupon');
            }

            Cart::destroy();

            $notification = array(
                'message' => 'Your Order Place Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('dashboard')->with($notification);

     } //end method

     public function CashOrder(Request $request){

        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount'];
        }else{
            $total_amount = round(Cart::total());
        }


        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,

            'payment_type' => 'Cash On Delivery',
            'payment_method' => 'Cash On Delivery',

            'currency' => 'Usd',
            'amount' => $total_amount,


            'invoice_no' => 'EOS'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'status' => 'pending',
            'created_at' => Carbon::now(),

        ]);


                // Start Send Email

                $invoice = Order::findOrFail($order_id);

                $data = [

                    'invoice_no' => $invoice->invoice_no,
                    'amount' => $total_amount,
                    'name' => $invoice->name,
                    'email' => $invoice->email,

                ];

                Mail::to($request->email)->send(new OrderMail($data));

                // End Send Email


        $carts = Cart::content();
        foreach($carts as $cart){

            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->id,
                'vendor_id' => $cart->options->vendor,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' =>Carbon::now(),

            ]);

        } // End Foreach

        if (Session::has('coupon')) {
           Session::forget('coupon');
        }

        Cart::destroy();

        $notification = array(
            'message' => 'Your Order Place Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification);



    }// End Method



}
