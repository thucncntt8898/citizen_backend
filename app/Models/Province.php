<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table   = 'provinces';
    protected $guarded = [];
    public $timestamps = false;

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
