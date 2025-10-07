<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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


public function roomFormattedArray()
{
    // Decode room images safely
    $images = is_string($this->image) ? json_decode($this->image, true) : ($this->image ?? []);
    $images = is_array($images) ? $images : [];
    $imageUrls = array_map(function ($img) {
        return get_all_image('room-images/' . $img);
    }, $images);

    $occupied_room = 0;
    $occupied_date = '';
    $appointments = \App\Models\Appointment::whereJsonContains('room_id', (string) $this->id)
        ->whereDate('to_date', '>=', now())
        ->orderBy('from_date', 'asc')
        ->get();

    if ($appointments->isNotEmpty()) {
        $occupied_room = 1;
        $active = $appointments->first();

        $fromDate = Carbon::parse($active->from_date);
        $toDate = Carbon::parse($active->to_date);
        $today = Carbon::now();

        $daysLeftFloat = $today->diffInHours($toDate, false) / 24;

        $daysLeft = ($daysLeftFloat - floor($daysLeftFloat)) >= 0.5
            ? ceil($daysLeftFloat)
            : floor($daysLeftFloat);

        $daysLeft = max(0, $daysLeft);

        $occupied_date = $fromDate->format('d-m-Y') . ' to ' . $toDate->format('d-m-Y');
        // if ($daysLeft > 0) {
        //     $occupied_date .= " ({$daysLeft} days left)";
        // }
    }

    return [
        'id'             => $this->id ?? 0,
        'title'          => $this->title ?? '',
        'person'         => $this->person ?? 0,
        'child'          => $this->child ?? 0,
        'price'          => $this->price ?? 0,
        'room_type'      => $this->room_type ?? '',
        'occupied_room'  => $occupied_room,
        'occupied_date'  => $occupied_date,
        'image'          => $imageUrls,
        'features'       => $this->features ? $this->features->map(function ($feature) {
            return [
                'id'    => $feature->id ?? 0,
                'name'  => $feature->name ?? '',
                'image' => $feature->image,
            ];
        })->toArray() : [],
    ];
}




}
