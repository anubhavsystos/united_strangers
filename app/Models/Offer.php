<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function sleep(){
        return $this->belongsTo(SleepListing::class, 'segment_id');
    }
    public function work(){
        return $this->belongsTo(WorkListing::class, 'segment_id');
    }

    public function play(){
        return $this->belongsTo(PlayListing::class, 'segment_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getSegmentAttribute(){
        switch ($this->segment_type) {
            case 'work':
                return $this->work;
            case 'sleep':
                return $this->sleep;
            case 'play':
                return $this->play;
            default:
                return null;
        }
    }

    public function offerformatted(){
        $name = "";
        $details_url = "";
        $type = $this->segment_type ?? ''; 
        if ($type == "sleep" && $this->sleep) {
            $name    = $this->sleep->title;           
        }

        if ($type == "work" && $this->work) {
            $name    = $this->work->title;
        }

        if ($type == "play" && $this->play) {
            $name    = $this->play->title;
        }
        if ($name) {
            $details_url = route('listing.details', [
                'type' => $type,
                'id'   => $this->segment_id ?? 0,
                'slug' => slugify($name)
            ]);
        }
        $images = json_decode($this->image, true)[0] ?? null;
        $fullDescription = $this->description ?? '';
        $shortDescription = strlen($fullDescription) > 20 ? substr($fullDescription, 0, 20) . '...' : $fullDescription; 
        return [            
            'id' => $this->id ?? '',            
            'image' => get_all_image('offer-images/' . $images ),
            'title' => $this->title ?? '',
            'offer_persent' => $this->offer_persent ?? '',
            'to_date' => !empty($this->to_date) ? date('M d, Y', strtotime($this->to_date)) : '',
            'from_date' => !empty($this->from_date) ? date('M d, Y', strtotime($this->from_date)) : '',
            'visibility' => $this->visibility ?? '',
            'description'  => $shortDescription,
            'full_desc'    => $fullDescription,
            'read_more'    => strlen($fullDescription) > 20,
            'name' => $name ?? '',
            'segment_type' => $type ?? '',
            'created_at'  => $this->created_at ?? null, 
            'details_url' =>  $details_url ?? '',
        ];
    }
}
