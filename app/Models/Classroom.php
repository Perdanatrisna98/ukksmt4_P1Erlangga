<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'tbl_classrooms';

    protected $fillable = [
        'major_id',
        'name',
        'level',
        'is_active'
    ];

    public function major () 
    {
        return $this->belongsTo(Major::class);
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Student::class,'classroom_id','id');
    }
}
