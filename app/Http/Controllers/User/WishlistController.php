<?php

namespace App\Http\Controllers\User;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    //
    public function AddToWishList(Request $request, $product_id){

        if(Auth::check()){
            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();

            if(!$exists){
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),

                ]);
                return response()->json(['success'=>'Successfully Added On Your Wishlist']);

            }else{
                return response()->json(['error' => 'This Product has already on your wishlist']);
            }
            
        }else{
            return response()->json(['error'=> 'At First Login Your Account']);
        }


    }//end method

}
