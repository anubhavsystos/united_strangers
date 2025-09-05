<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkListing extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function countryDetail(){
        return $this->belongsTo(Country::class, 'country');
    }

    public function cityDetail(){
        return $this->belongsTo(City::class, 'city');
    }
    public function claimed(){
        return $this->hasOne(ClaimedListing::class, 'listing_id')->where('listing_type', 'play');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'listing_id')->where('type', 'play')->whereNull('reply_id');
    }

    public function categoryDetail(){
        return $this->belongsTo(Category::class, 'category');
    }

    public function productFormattedArray()
    {
        $imageArray = json_decode($this->image) ?? [];
        $image = isset($imageArray[0]) ? $imageArray[0] : null;

        return [
            'id'            => $this->id ?? '',
            'slug'          => slugify($this->title ?? ''),
            'title'         => $this->title ?? '',
            'description'   => Str::limit(strip_tags($this->description ?? ''), 100),
            'image'         => get_all_image('listing-images/' . ($image ?? '')),
            'price'         => $this->price ?? '',
            'discount'      => $this->discount ?? '',
            'bed'           => $this->bed ?? '',
            'bath'          => $this->bath ?? '',
            'size'          => $this->size ?? '',
            'country'       => optional($this->countryDetail)->name ?? '',
            'city'          => optional($this->cityDetail)->name ?? '',
            'status'        => $this->status ?? '',
            'is_verified'   => optional($this->claimed)->status == 1,
            'is_in_wishlist'=> check_wishlist_status($this->id ?? 0, $this->type ?? ''),
            'created_at'    => $this->created_at ?? '',
            'details_url'   => route('listing.details', [
                'type'  => $this->type ?? 'work',
                'id'    => $this->id ?? 0,
                'slug'  => slugify($this->title ?? '')
            ]),
        ];

    }
}
