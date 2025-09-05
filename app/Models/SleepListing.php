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

    public function rooms() {
        return $this->hasMany(Room::class, 'listing_id');
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
    
    // public function productFormattedArray()
    // {
    //     $images = json_decode($this->image ?? '[]');
    //     $country = $this->countryDetail->name ?? '';
    //     $city = $this->cityDetail->name ?? '';
    //     $claimed = $this->claimed ?? null;
    //     $is_verified = $claimed && ($claimed->status ?? 0) == 1;

    //     $rooms = $this->rooms ? $this->rooms->map(function ($room) {
    //         return [
    //             'id'         => $room->id ?? 0,
    //             'title'      => $room->title ?? '',
    //             'person'     => $room->person ?? 0,
    //             'child'      => $room->child ?? 0,
    //             'listing_id' => $room->listing_id ?? 0,
    //             'price'      => $room->price ?? 0,
    //             'feature'    => is_string($room->feature) ? json_decode($room->feature, true) : ($room->feature ?? []),
    //             'image'      => is_string($room->image) ? json_decode($room->image, true) : ($room->image ?? []),
    //             'user_id'    => $room->user_id ?? 0,
    //             'created_at' => $room->created_at ?? null,
    //             'updated_at' => $room->updated_at ?? null,

    //             'features'   => $room->features ? $room->features->map(function ($feature) {
    //                 return [
    //                     'id'         => $feature->id ?? 0,
    //                     'user_id'    => $feature->user_id ?? 0,
    //                     'name'       => $feature->name ?? '',
    //                     'icon'       => $feature->icon ?? '',
    //                     'type'       => $feature->type ?? '',
    //                     'identifier' => $feature->identifier ?? '',
    //                     'parent'     => $feature->parent ?? null,
    //                     'rating'     => $feature->rating ?? null,
    //                     'image'      => $feature->image ?? '',
    //                     'designation'=> $feature->designation ?? '',
    //                     'time'       => $feature->time ?? '',
    //                     'price'      => $feature->price ?? 0,
    //                     'created_at' => $feature->created_at ?? null,
    //                     'updated_at' => $feature->updated_at ?? null,
    //                 ];
    //             })->toArray() : [],
    //         ];
    //     })->toArray() : [];

    //     $reviews = $this->reviews
    //         ->where('user_id', '!=', ($this->user_id ?? 0))
    //         ->whereNull('reply_id');
        
    //     $review_count = $reviews ? $reviews->count() : 0;
    //     $average_rating = $review_count > 0 ? $reviews->sum('rating') / $review_count : 0;

    //     return [
    //         'id'             => $this->id ?? 0,
    //         'title'          => $this->title ?? '',
    //         'image_url'      => get_all_image('listing-images/' . ($images[0] ?? '')),
    //         'is_popular'     => $this->is_popular ?? 0,
    //         'is_verified'    => $is_verified,
    //         'country'        => $country,
    //         'city'           => $city,
    //         'review_count'   => $review_count,
    //         'rating'         => $average_rating,
    //         'features'       => [], // global features agar hain to add karna
    //         'price'          => $this->price ?? 0,
    //         'created_at'     => $this->created_at ?? null,
    //         'is_in_wishlist' => check_wishlist_status($this->id ?? 0, 'sleep'),
    //         'details_url'    => route('listing.details', [
    //             'type' => 'sleep',
    //             'id'   => $this->id ?? 0,
    //             'slug' => slugify($this->title ?? '')
    //         ]),
    //         'rooms'          => $rooms,
    //     ];
    // }



}
