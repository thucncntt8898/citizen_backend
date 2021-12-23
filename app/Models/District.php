<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table   = 'districts';
    protected $guarded = [];
    public $timestamps = false;

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function wards() {
        return $this->hasMany(Ward::class );
    }
}
