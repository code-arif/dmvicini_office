<?php

namespace App\Http\Controllers\Api\CMS;

use App\Models\CMS;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CMSController extends Controller
{
    use ApiResponse;
    public function helpCenterPage()
    {
        $data = CMS::where('page', 'help-center-page')->select('title', 'description')->get();

        return $this->success($data, 'Help Center page data retrieved successfully');
    }
}
