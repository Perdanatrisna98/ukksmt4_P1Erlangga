<?php

namespace App\Models;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_categories';

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active'
    ];

    public function assets(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Asset::class);
}
}
