<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ShipDivision;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id){

        if(Session::has('coupon')){
            Session::forget('coupon');
        }

        $product = Product::findOrFail($id);

        if($product->discount_price == NULL){

            Cart::add([
                'id'   => $id,
                'name' => $request->product_name,
                'qty'   => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options'=> [  //options
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size'  => $request->size,
                    'vendor' => $request->vendor,
                ],

                  ]);
                  return response()->json(['success' => 'Successfully Added on Your Cart']);

        }else {
                Cart::add([
                    'id' => $id,
                    'name' => $request->product_name,
                    'qty' => $request->quantity,
                    'price' => $product->discount_price,
                    'weight' => 1,
                    'options' => [
                        'image'=> $product->product_thambnail,
                        'color' => $request->color,
                        'size' => $request->size,
                        'vendor' => $request->vendor,
                    ],

                ]);

                return response()->json(['success' => 'Successfully Added On Your Cart' ]);
        }

    }//end method

    public function AddToCartDetails(Request $request, $id){

            if(Session::has('coupon')){
                Session::forget('coupon');
            }

        $product = Product::findOrFail($id);

        if ($product->discount_price == NULL) {

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }else{

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }

    }// End Method

    public function AddMiniCart(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal

        ));


    }//end method

    public function RemoveMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Product remove from cart!']);
    }//end method

    public function MyCart(){

        return view('frontend.mycart.view_mycart');

    }//end method

    public function GetCartProduct(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' =>$carts,
            'cartQty' => $cartQty,
            'cartTotal' => $cartTotal

        ));


    }//end method

    public function CartRemove($rowId){
        Cart::remove($rowId);

        if(Session::has('coupon')){
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();

            Session::put('coupon',[
                'coupon_name'     => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount'    => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100)

            ]);
        }

        return response()->json(['success' => 'Successfully Remove From Cart']);

    }//end method

    public function CartDecrement($rowId){
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty -1);

        if(Session::has('coupon')){
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();

            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]);
        }

        return response()->json('Decrement');

    }//end method

   public function CartIncrement($rowId){
      $row = Cart::get($rowId);
      Cart::update($rowId, $row->qty +1);

      if(Session::has('coupon')){
        $coupon_name = Session::get('coupon')['coupon_name'];
        $coupon = Coupon::where('coupon_name',$coupon_name)->first();

        Session::put('coupon',[
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
            'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100)

        ]);

      }

      return response()->json('Increment');

   }//end method

    public function CouponApply(Request $request){
        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();

        if($coupon){
            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]);

            return response()->json(array(
                'validity' => true,
                'success'  => 'Coupon Applied Successfully'
            ));

        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }


    }//end method for coupon apply front

    public function CouponCalculation(){//
        if(Session::has('coupon')){
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount'  => session()->get('coupon')['total_amount'],
            ));

        }else{
            return response()->json(array(
                'total' => Cart::total(),
            ));
        }

    }//end method

    public function CouponRemove(){
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);

    }//end method

    public function CheckoutCreate(){

        if(Auth::check()){

            if(Cart::total() > 0){
                $carts = Cart::content();
                $cartQty = Cart::count();
                $cartTotal = Cart::total();
                $divisions = ShipDivision::orderBy('division_name','ASC')->get();
            return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));
         }else {
            $notification = array(
                'message' => 'Shopping At list One Product',
                'alert-type' => 'error'
            );
            return redirect()->to('/')->with($notification);

         }
        }else {
            $notification = array(
                'message' => 'You need to login first',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }

    }//end method //https://packagist.org/packages/bumbummen99/shoppingcart


//Programmer Sohan
//performance,security,scalability,deployment,reliability
}
