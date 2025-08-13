<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayListing extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function countryDetail(){
        return $this->belongsTo(Country::class, 'country');
    }

    public function cityDetail(){
        return $this->belongsTo(City::class, 'city');
    }

    public function categoryDetail(){
        return $this->belongsTo(Category::class, 'category');
    }

    public function claimed(){
        return $this->hasOne(ClaimedListing::class, 'listing_id')->where('listing_type', 'play');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'listing_id')->where('type', 'play')->whereNull('reply_id');
    }

    public function productFormattedArray(){
        $image = json_decode($this->image, true)[0] ?? null;

        return [
            'id' => !empty($this->id) ? $this->id : '',
            'title' => !empty($this->title) ? $this->title : '',
            'is_popular' => !empty($this->is_popular) ? $this->is_popular : '',
            'image' => isset($image) ? get_all_image('listing-images/' . $image) : '',
            'city' => $this->cityDetail->name ?? '',
            'country' => $this->countryDetail->name ?? '',
            'claimed' => !empty($this->claimed) && $this->claimed->status == 1,
            'reviews_count' => $this->reviews ? $this->reviews->where('user_id', '!=', $this->user_id)->count() : 0,
            'wishlist' => check_wishlist_status($this->id ?? 0, $this->type ?? ''),
            'details_url' => route('listing.details', [
                'type' => 'play',
                'id' => $this->id ?? 0,
                'slug' => slugify($this->title ?? '')
            ])
        ];
    }
  

}

