<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'sub_title',
        'description',
        'image'
    ];

    //reation with category table
    public function category()
    {
        return $this->BelongsTo(Category::class);
    }

    //relation with pin table
    public function pinnedByUser()
    {
        return $this->hasOne(PinnedEducation::class, 'education_id')
            ->where('user_id', auth()->id());
    }
}
