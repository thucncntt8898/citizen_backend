<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $table   = 'citizens';
    protected $guarded = [];
    public $timestamps = false;
}
