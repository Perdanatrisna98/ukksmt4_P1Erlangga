<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tbl_tickets';

    protected $fillable = [
        'user_id',
        'asset_id',
        'ticket_number',
        'qty',
        'booked_at',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function AssetReturn () 
    {
        return $this->hasMany(AssetReturn::class);
    }
}
