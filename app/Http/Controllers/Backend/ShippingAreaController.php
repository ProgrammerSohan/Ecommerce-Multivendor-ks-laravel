<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShipDivision;
use Illuminate\Http\Request;

class ShippingAreaController extends Controller 
{
     public function AllDivision(){
        $division = ShipDivision::latest()->get();
        return view('backend.ship.division.division_all',compact('division'));

     } //end method

     public function AddDivision(){
        return view('backend.ship.division.division_add');
     }//end method

     public function StoreDivision(Request $request){
        ShipDivision::insert([
            'division_name'=>$request->division_name,
        ]);
        $notification = array(
            'message'=> 'ShipDivision Inserted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->route('all.division')->with($notification);

     }// end method


}
