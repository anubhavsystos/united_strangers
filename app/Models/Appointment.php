<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;    
    protected $guarded = [];

    protected $casts = [
        'menu_id' => 'array', 
    ];

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
   
    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }
    
    public function menus(){
        $ids = is_string($this->menu_id) ? json_decode($this->menu_id, true) : $this->menu_id;
        return Menu::whereIn('id', $ids ?? [])->get();
    }

    public function appointmentFormatted($type){
            $title      = '';
            $category   = '';
            $room_name  = '';
            $menu_name  = '';

            if ($type == "sleep" && $this->sleep) {
                $title      = $this->sleep->title;
                $category   = $this->sleep->categoryDetail->name ?? $this->sleep->category ?? '';
                $room_name  = $this->room->title ?? '';
            }

            if ($type == "work" && $this->work) {
                $title      = $this->work->title;
                $category   = $this->work->categoryDetail->name ?? $this->work->category ?? '';
                $room_name  = $this->room->title ?? '';
            }

            if ($type == "play" && $this->play) {
                $title      = $this->play->title;
                $category   = $this->play->categoryDetail->name ?? $this->play->category ?? '';
                $menu_name  = $this->menus()->pluck('title')->implode(', ');
            }

            return [
                'id'                => $this->id ?? '',
                'image'             => isset($this->user->image) ? get_all_image('users/' . $this->user->image) : '',
                'customer_name'     => $this->user->name ?? '',
                'customer_email'    => $this->user->email ?? '',
                'customer_phone'    => $this->user->phone ?? '',
                'name'              => $this->name ?? '',
                'phone'             => $this->phone ?? '',
                'adults'            => $this->adults ?? '',
                'child'             => $this->child ?? '',                
                'total_price'       => currency($this->total_price) ?? '',                
                'menu_summary' => !empty($this->menu_summary) ? explode(',', $this->menu_summary) : [],              
                'title'             => $title,
                'category_name'     => $category,
                'room_name'         => $room_name,
                'menu_name'         => $menu_name, 
                'date'              => !empty($this->date) ? date('d M Y', strtotime($this->date)) : '',
                'in_time'           => !empty($this->in_time) ? date('h:i A', strtotime($this->in_time)) : '',
                'out_time'          => !empty($this->out_time) ? date('h:i A', strtotime($this->out_time)) : '',
                'message'           => $this->message ?? '',
                'status'            => $this->status ?? '',
            ];
    }

}
