<?php

namespace App\Models;

use App\Models\Concerns\LogsCrudActivity;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use LogsCrudActivity;

    protected $table = 'tbl_majors';

    protected $fillable = [
        'name',
        'code',
        'is_active'
    ];

    public function classes() {
        return $this->hasMany(Classroom::class);
    }
}
