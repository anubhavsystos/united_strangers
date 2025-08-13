<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedListing extends Model
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
        switch ($this->type) {
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
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'user_phone' => $this->user_phone,
            'report_type' => $this->report_type,
            'title' => $this->title,
            'report' => $this->report,
            'listing_type' => $this->type,
            'listing_type_data' => $this->listing_data,
            'user' => $this->user
        ];
    }
}
