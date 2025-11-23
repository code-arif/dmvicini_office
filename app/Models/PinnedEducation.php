<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PinnedEducation extends Model
{
    protected $fillable = [
        'education_id',
        'user_id'
    ];

    public function education()
    {
        return $this->BelongsTo(Education::class);
    }
}
