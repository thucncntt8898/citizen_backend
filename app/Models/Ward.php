<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table   = 'wards';
    protected $guarded = [];
    public $timestamps = false;

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function hamlets() {
        return $this->hasMany(Hamlet::class );
    }
}
