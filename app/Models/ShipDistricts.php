<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShipDivision;

class ShipDistricts extends Model
{
    use HasFactory;
    protected $guarded = []; 
    public function division(){
        return $this->belongsTo(ShipDivision::class,'division_id','id');

    }
}
