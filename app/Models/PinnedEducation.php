<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinnedEducation extends Model
{
    protected $fillable = [
        'education_id',
        'user_id'
    ];
}
