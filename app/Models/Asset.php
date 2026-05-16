<?php

namespace App\Models;

use App\Models\Concerns\LogsCrudActivity;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use LogsCrudActivity;

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
        'description',
        'purchase_price',
        'procurement_year',
        'funding_source',
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }
    
    public function tickets () 
    {
        return $this->hasMany(Ticket::class);
    }

    public function AssetReturn () 
    {
        return $this->hasMany(AssetReturn::class);
    }
}
