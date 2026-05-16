<?php

namespace App\Models;

use App\Models\Concerns\LogsCrudActivity;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use LogsCrudActivity;

    protected $table = 'tbl_students';

    protected $fillable = [
        'user_id',
        'classroom_id',
        'nisn',
        'phone_number',
        'gender',
        'address',
        'profile_picture'
    ];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }

    public function classroom () 
    {
        return $this->belongsTo(Classroom::class);
    }
}
