<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;    
    protected $guarded = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    

    public function country_to_city()
    {
        return $this->hasMany(City::class,'country','id');
    }
}
