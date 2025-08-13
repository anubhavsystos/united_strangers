<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SeoField extends Model
{
    public $timestamps = false;

    use HasFactory;    
    protected $guarded = [];

    /**
     * Get the column names of the table.
     *
     * @return array
     */
    public static function getColumnNames()
    {
        return Schema::getColumnListing((new self)->getTable());
    }
}
