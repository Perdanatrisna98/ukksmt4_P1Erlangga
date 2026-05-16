<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\Concerns\LogsCrudActivity;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use LogsCrudActivity;

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
