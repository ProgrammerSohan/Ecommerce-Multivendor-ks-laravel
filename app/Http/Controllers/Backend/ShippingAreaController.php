<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ShipDistricts;
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

     public function EditDivision($id){
        $division = ShipDivision::findOrFail($id);
        return view('backend.ship.division.division_edit',compact('division'));
     }

     public function UpdateDivision(Request $request){
        $division_id = $request->id;

        ShipDivision::findOrFail($division_id)->update([
            'division_name' => $request->division_name,
        ]);

        $notification = array(
            'message'=> 'ShipDivision Updated Successfully',
            'alert-type'=> 'success'

        );
        return redirect()->route('all.division')->with($notification);

     }//end method

     public function DeleteDivision($id){
        ShipDivision::findOrFail($id)->delete();

        $notification = array(
            'message'=>'ShipDivision Deleted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);

     }//end method

     /***********District CRUD******** */
     public function AllDistrict(){
        $district = ShipDistricts::latest()->get();
        return view('backend.ship.district.district_all',compact('district'));

     }//end method

     public function AddDistrict(){
          $division = ShipDivision::orderBy('division_name','ASC')->get();
          return view('backend.ship.district.district_add',compact('division'));

     }//end method

}
