<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    function project(){
        return $this->belongsTo(Project::class);
    }
}
