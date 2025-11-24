<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use App\Models\InterestedUser;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InterestUserController extends Controller
{
    // get all interst user
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $interests = InterestedUser::with(['user.profile', 'investment'])->latest();

            return DataTables::of($interests)
                ->addIndexColumn()
                ->addColumn('investor', function ($row) {
                    $name = $row->user?->profile?->first_name . ' ' . $row->user?->profile?->last_name;
                    return $name ? '<strong>' . htmlspecialchars($name) . '</strong>' : '<em class="text-muted">Deleted User</em>';
                })
                ->addColumn('email', fn($row) => '<a href="mailto:' . $row->email . '">' . $row->email . '</a>')
                ->addColumn('deal', fn($row) => '<strong>' . htmlspecialchars($row->investment?->title ?? 'Deal Removed') . '</strong>')
                ->addColumn('expressed_at', fn($row) => $row->created_at->format('d M, Y h:i A'))
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '">
                                <i class="fa fa-eye"></i> View
                            </button>';
                })
                ->rawColumns(['investor', 'email', 'deal', 'action'])
                ->make(true);
        }

        return view('backend.layouts.interested_users.index');
    }


    public function show($id)
    {
        $interest = InterestedUser::with(['user.profile', 'investment'])->findOrFail($id);

        return view('backend.layouts.interested_users.modal_details', compact('interest'))->render();
    }
}
