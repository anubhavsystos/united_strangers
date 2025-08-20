<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function sleep(){
        return $this->belongsTo(SleepListing::class, 'listing_id')->with('categoryDetail');
    }
    public function work(){
        return $this->belongsTo(WorkListing::class, 'listing_id')->with('categoryDetail');
    }

    public function play(){
        return $this->belongsTo(PlayListing::class, 'listing_id')->with('categoryDetail');
    }
    public function user(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function appointmentFormatted($type){
        $title = '';
        $category = '';

        if ($type == "sleep" && $this->sleep) {
            $title    = $this->sleep->title;
            $category = $this->sleep->categoryDetail->name ?? $this->sleep->category ?? '';
        }

        if ($type == "work" && $this->work) {
            $title    = $this->work->title;
            $category = $this->work->categoryDetail->name ?? $this->work->category ?? '';
        }

        if ($type == "play" && $this->play) {
            $title    = $this->play->title;
            $category = $this->play->categoryDetail->name ?? $this->play->category ?? '';
        }

        return [
            'id'            => $this->id ?? '',
            'image' => isset($this->user->image) ? get_all_image('users/' . $this->user->image) : '',
            'customer_name' => $this->user->name ?? '',
            'customer_email' => $this->user->email ?? '',
            'customer_phone' => $this->user->phone ?? '',
            'title'         => $title ?? '',
            'category_name' => $category ?? '',
            'date'          => !empty($this->date) ? date('d M y', strtotime($this->date)) : '',
            'time'          => !empty($this->time) ? date('h:i A', strtotime($this->time)) : '',
            'message'       => $this->message ?? '',
            'status'        => $this->status ?? '',
        ];
    }
}
