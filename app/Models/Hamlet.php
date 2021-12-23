<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hamlet extends Model
{
    protected $table   = 'hamlets';
    protected $guarded = [];
    public $timestamps = false;

    public function ward() {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}
