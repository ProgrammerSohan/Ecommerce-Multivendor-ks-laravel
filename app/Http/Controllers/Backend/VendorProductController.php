<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
        public function VendorAllProduct(){

            $id = Auth::user()->id;
            $products = Product::where('vendor_id',$id)->latest()->get();
            return view('vendor.backend.product.vendor_product_all',compact('products'));

        } //end method


}
