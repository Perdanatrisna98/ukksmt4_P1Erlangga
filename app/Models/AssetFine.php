<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetFine extends Model
{
    protected $table = 'tbl_asset_fines';

    protected $fillable = [
        'asset_return_id',
        'type',
        'amount',
        'notes'
    ];

    public function assetReturn ()
    {
        return $this->belongsTo(AssetReturn::class);
    }
}
