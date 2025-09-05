<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
   use HasFactory;    
    protected $guarded = [];

    protected $casts = [
        'feature' => 'array', 
    ];

    protected $appends = ['features'];

   public function getFeaturesAttribute()
    {
        if (empty($this->feature)) {
            return [];
        }
        return Amenities::whereIn('id', $this->feature)->get();
    }

    public function roomFormattedArray() {
        $images = is_string($this->image) ? json_decode($this->image, true) : ($this->image ?? []);
        $images = is_array($images) ? $images : [];
        $imageUrls = array_map(function ($img) {
            return get_all_image('room-images/' . $img);
        }, $images);

        return [
            'id'       => $this->id ?? 0,
            'title'    => $this->title ?? '',
            'person'   => $this->person ?? 0,
            'child'    => $this->child ?? 0,
            'price'    => $this->price ?? 0,
            'image'    => $imageUrls, 
            'features' => $this->features ? $this->features->map(function ($feature) {
                return [
                    'id'    => $feature->id ?? 0,
                    'name'  => $feature->name ?? '',
                    'image' => $feature->image,
                ];
            })->toArray() : [],
        ];
    }



}
