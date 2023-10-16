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

    public function AllWishlist(){
        return view('frontend.wishlist.view_wishlist');

    }//end method

    public function GetWishlistProduct(){

        $wishlist = Wishlist::with('product')->where('user_id',Auth::id())->latest()->get();

        $wishQty = wishlist::count();
        return response()->json(['wishlist'=>$wishlist, 'wishQty'=>$wishQty]);

    }//end method

    public function WishlistRemove($id){

        Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success'=>'Successfully Product Remove']);

    }// end method

}
