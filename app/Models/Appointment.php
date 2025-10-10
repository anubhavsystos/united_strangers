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
   
    public function rooms(){
        $ids = $this->room_id;

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        if (!is_array($ids)) {
            $ids = [];
        }

        return Room::whereIn('id', $ids)->get();
    }

    public function menus()
    {
        $ids = $this->menu_id;

        if (is_string($ids)) {
            $ids = json_decode($ids, true);
        }

        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        if (!is_array($ids)) {
            $ids = [];
        }

        return Menu::whereIn('id', $ids)->get();
    }

    public function appointmentFormatted($type){
            $title      = '';
            $category   = '';
            $room_name  = '';
            $menu_name  = '';

            if ($type == "sleep" && $this->sleep) {
                $title      = $this->sleep->title;
                $category   = $this->sleep->categoryDetail->name ?? $this->sleep->category ?? '';
               $room_name = $this->rooms()->pluck('title')->implode(', ');
            }

            if ($type == "work" && $this->work) {
                $title      = $this->work->title;
                $category   = $this->work->categoryDetail->name ?? $this->work->category ?? '';
               $room_name = $this->rooms()->pluck('title')->implode(', ');
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

    public function appointmentCustomerFormatted(){
        $type       = $this->listing_type ?? '';
        $title      = '';
        $category   = '';
        $room_name  = '';
        $menu_name  = '';

        if ($type === "sleep" && $this->sleep) {
            $title      = $this->sleep->title;
            $category   = $this->sleep->categoryDetail->name ?? $this->sleep->category ?? '';
            $room_name  = $this->rooms()->pluck('title')->implode(', ');
        }

        if ($type === "work" && $this->work) {
            $title      = $this->work->title;
            $category   = $this->work->categoryDetail->name ?? $this->work->category ?? '';
            $room_name  = $this->rooms()->pluck('title')->implode(', ');
        }

        if ($type === "play" && $this->play) {
            $title      = $this->play->title;
            $category   = $this->play->categoryDetail->name ?? $this->play->category ?? '';
            $menu_name  = $this->menus()->pluck('title')->implode(', ');
        }

        return [
            'id'                => $this->id ?? '',
            'title'             => $this->title ?? '',
            'qr_code'           => $this->qr_code ?? '',
            'image'             => isset($this->user->image) ? get_all_image('users/' . $this->user->image) : '',
            'customer_name'     => $this->user->name ?? '',
            'customer_email'    => $this->user->email ?? '',
            'customer_phone'    => $this->user->phone ?? '',
            'name'              => $this->name ?? '',
            'phone'             => $this->phone ?? '',
            'listing_type'      => $this->listing_type ?? '',
            'adults'            => $this->adults ?? '',
            'child'             => $this->child ?? '',                
            'total_price'       => currency(number_format($this->total_price, 0)) ?? '',             
            'menu_summary'      => !empty($this->menu_summary) ? explode(',', $this->menu_summary) : [],              
            'title'             => $title,
            'category_name'     => $category,
            'room_name'         => $room_name,                
            'menu_name'         => $menu_name, 
            'date'              => !empty($this->date) ? date('d M Y', strtotime($this->date)) : '',
            'from_date'         => !empty($this->from_date) ? date('d M Y', strtotime($this->from_date)) : '',
            'to_date'           => !empty($this->to_date) ? date('d M Y', strtotime($this->to_date)) : '',
            'in_time'           => !empty($this->in_time) ? date('h:i A', strtotime($this->in_time)) : '',
            'out_time'          => !empty($this->out_time) ? date('h:i A', strtotime($this->out_time)) : '',
            'message'           => $this->message ?? '',
            'status'            => $this->status ?? '',
        ];
    }


}
