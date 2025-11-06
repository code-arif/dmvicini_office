<?php

namespace App\Http\Controllers\Api;

use App\Models\Footer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FooterResource;

class FooterController extends Controller
{
    use ApiResponse;

    // get all footer item
    public function index()
    {
        $footer = Footer::first();
        return $this->success(new FooterResource($footer), 'Footer settings retrieved.');
    }
}
