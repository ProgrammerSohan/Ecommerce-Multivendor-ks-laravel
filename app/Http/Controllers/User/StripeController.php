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
                'district_id' => $request->division_id,
                'state_id' => $request->division_id,
                'name' => $request->division_id,
                'email' => $request->division_id,
                'phone' => $request->division_id,
                'address' => $request->division_id,
                'post_code' => $request->division_id,
                'notes' => $request->division_id,
                'payment_type' => $request->division_id,
                'payment_method' => $request->division_id,
                'transaction_id' => $request->division_id,
                'currency' => $request->division_id,
                'amount' => $request->division_id,
                'order_number' => $request->division_id,
                'invoice_no' => $request->division_id,
                'order_date' => $request->division_id,
                'order_month' => $request->division_id,
                'order_year' => $request->division_id,
                'confirmed_date' => $request->division_id,
                'processing_date' => $request->division_id,
                'picked_date' => $request->division_id,
                'shipped_date' => $request->division_id,
                'delivered_date' => $request->division_id,
                'cancel_date' => $request->division_id,
                'return_date' => $request->division_id,
                'return_reason' => $request->division_id,
                'status'      => $request->division_id,
                'created_at'      => $request->division_id

            ]);

     } //end method
}
