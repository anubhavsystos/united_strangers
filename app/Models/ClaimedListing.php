<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimedListing extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function sleep(){
        return $this->belongsTo('App\Models\SleepListing','listing_id','id');
    }
    public function play(){
        return $this->belongsTo('App\Models\PlayListing','listing_id','id');
    }
    public function work(){
        return $this->belongsTo('App\Models\WorkListing','listing_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

     public function getListingDataAttribute()
    {
        switch ($this->listing_type) {
            case 'sleep':
                return $this->sleep;
            case 'play':
                return $this->play;
            case 'work':
                return $this->work;
            default:
                return null;
        }
    }

    public function toFormattedArray()
    {
        return [
            'id' => $this->id,
            'additional_info' => $this->additional_info,
            'status' => $this->status,
            'listing_type' => $this->listing_type,
            'listing_data' => $this->listing_data,
            'user' => $this->user
        ];
    }
}
