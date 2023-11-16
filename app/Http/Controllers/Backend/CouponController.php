<?php

namespace App\Http\Controllers\Backend;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
     public function AllCoupon(){
            $coupon = Coupon::latest()->get();
             return view('backend.coupon.coupon_all',compact('coupon'));

     }//end method
    
}
