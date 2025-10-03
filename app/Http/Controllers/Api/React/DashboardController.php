<?php

namespace App\Http\Controllers\Api\React;

use Exception;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Event;
use App\Models\Venue;
use App\Models\VenueReview;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    use ApiResponse;

    //user events stats
    public function userEventStats()
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return $this->error([], 'Unauthorized user.', 401);
            }

            return $this->success([
            ], 'User event stats fetched successfully.');
        } catch (Exception $e) {
            return $this->error([], 'Failed to fetch stats. ' . $e->getMessage(), 500);
        }
    }
}
