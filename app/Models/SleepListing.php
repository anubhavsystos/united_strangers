<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepListing extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function countryDetail() {
        return $this->belongsTo(Country::class, 'country');
    }
    public function cityDetail() {
        return $this->belongsTo(City::class, 'city');
    }
    public function claimed() {
        return $this->hasOne(ClaimedListing::class, 'listing_id')->where('listing_type', 'sleep');
    }
    public function reviews() {
        return $this->hasMany(Review::class, 'listing_id')->where('type', 'sleep')->whereNull('reply_id');
    }
    public function categoryDetail(){
        return $this->belongsTo(Category::class, 'category');
    }
    public function productFormattedArray(){
        $images = json_decode($this->image ?? '[]');
        $country = $this->countryDetail->name ?? '';
        $city = $this->cityDetail->name ?? '';
        $claimed = $this->claimed;
        $is_verified = $claimed && $claimed->status == 1;
        $features = [];

        if ($this->feature) {
            $featureIds = json_decode($this->feature);
            $features = App\Models\Amenities::whereIn('id', $featureIds)->pluck('name')->toArray();
        }

        $reviews = $this->reviews->where('user_id', '!=', $this->user_id)->whereNull('reply_id');
        $review_count = $reviews->count();
        $average_rating = $review_count > 0 ? $reviews->sum('rating') / $review_count : 0;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_url' => get_all_image('listing-images/' . ($images[0] ?? '')),
            'is_popular' => $this->is_popular,
            'is_verified' => $is_verified,
            'country' => $country,
            'city' => $city,
            'review_count' => $review_count,
            'rating' => $average_rating,
            'features' => $features,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'is_in_wishlist' => check_wishlist_status($this->id, 'sleep'),
            'details_url' => route('listing.details', [
                    'type' => 'sleep',
                    'id' => $this->id ?? 0,
                    'slug' => slugify($this->title ?? '')
            ]),
        ];
    }

}
