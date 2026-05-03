<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'tbl_assets';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'total_qty',
        'good_qty',
        'damaged_qty',
        'lost_qty',
        'borrowed_qty',
        'is_available',
        'image',
        'description'
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }
}
