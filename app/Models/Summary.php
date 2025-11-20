<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $fillable = ["investment_id", "summary", "project_picture"];
}
