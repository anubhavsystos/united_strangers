<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;    
    protected $guarded = [];

    public function blogformatted()
    {
        $shortDescription = strlen($this->description) > 20 
            ? substr($this->description, 0, 20) . '...' 
            : $this->description;

        return [            
            'id'         => $this->id ?? '',
            'image'      => isset($this->image) ? get_all_image('blog-images/' . $this->image) : '',            
            'title'      => $this->title ?? '',
            'category'   => $this->category ?? '',
            'user_id'    => $this->user_id ?? '',
            'status'     => $this->status ?? '',
            'date'       => !empty($this->created_at) ? date('M d, Y', strtotime($this->created_at)) : '',
            'visibility' => $this->visibility ?? '',
            'description'=> $shortDescription,           
            'created_at' => $this->created_at ?? null,
            'details_url'=> route('blog.details', [
                'id'   => $this->id,
                'slug' => slugify($this->title),
            ]),
        ];
    }

}
