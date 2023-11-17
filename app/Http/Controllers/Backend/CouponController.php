<?php

namespace App\Http\Controllers\Backend;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
     public function AllCoupon(){
            $coupon = Coupon::latest()->get();
             return view('backend.coupon.coupon_all',compact('coupon'));

     }//end method

     public function AddCoupon(){
          return view('backend.coupon.coupon_add');
     }//end method
    
     public function StoreCoupon(Request $request){

          Coupon::insert([
               'coupon_name' => strtoupper($request->coupon_name),
               'coupon_discount'=> $request->coupon_discount,
               'coupon_validity' => $request->coupon_validity,
               'created_at'=>Carbon::now(),

          ]);

          $notification =array(
               'message' => 'Coupon Inserted Successfully',
               'alert-type'=>'success'
               
          );

          return redirect()->route('all.coupon')->with($notification);

     }//end method

        public function EditCoupon($id){
           
           $coupon =Coupon::findOrFail($id);
           return view('backend.coupon.edit_coupon',compact('coupon'));


        }//end method


}
