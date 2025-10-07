<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyLocation extends Model
{
    use HasFactory;    
    protected $guarded = [];
   
    public function nearby_to_listing()
    {
        return $this->belongsTo(WorkListing::class,'listing_id','id');
    }

     public function nearbyLocationsArray()
    {
        return [
            'id'        => $this->id ?? 0,
            'name'      => $this->name ?? '',
            'type'      => $this->type ?? '',
            'latitude'  => $this->latitude ?? 0,
            'longitude' => $this->longitude ?? 0,
        ];
    }
}
