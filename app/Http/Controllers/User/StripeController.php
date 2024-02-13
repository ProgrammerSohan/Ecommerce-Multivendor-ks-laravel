<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
     public function StripeOrder(){

            \Stripe\Stripe::setApiKey('sk_test_51OjENQD6fI3thGcmRSinbYZE0LMY6jitwaIGYrfwzT09K7PryxFlJ4MkCIB7hYjbjYOUnfAGfzvmYi4wl3B8Uqw6001NhJfi9s');

            $token = $_POST['stripeToken'];
            $charge = \Stripe\Charge::create([
            'amount' => 999*100,
            'currency' => 'usd',
            'description' => 'Sohan Online Shop',
            'source' => $token,
            'metadata' => ['order_id' => '6735']
            ]);

            dd($charge);

     } //end method
}
