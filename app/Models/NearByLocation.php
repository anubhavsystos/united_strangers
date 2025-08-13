<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyLocation extends Model
{
    use HasFactory;    
    protected $guarded = [];

    // protected $table = 'nearby_location';    
    public function nearby_to_listing()
    {
        return $this->belongsTo(WorkListing::class,'listing_id','id');
    }
}
