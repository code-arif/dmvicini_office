<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscribers = Subscriber::latest();

            return DataTables::of($subscribers)
                ->addIndexColumn()

                ->addColumn('email', fn($item) => '<strong>' . $item->email . '</strong>')

                ->addColumn('status', function ($item) {
                    return $item->is_verified
                        ? '<span class="badge bg-success">Verified</span>'
                        : '<span class="badge bg-warning">Pending</span>';
                })

                ->addColumn('subscribed_at', fn($item) => $item->subscribed_at->format('d M, Y h:i A'))

                ->addColumn('verified_at', function ($item) {
                    return $item->verified_at
                        ? $item->verified_at->format('d M, Y h:i A')
                        : '<span class="text-muted">Not verified</span>';
                })

                ->rawColumns(['email', 'status', 'verified_at'])
                ->make(true);
        }

        return view('backend.layouts.subscriber.index');
    }
}
