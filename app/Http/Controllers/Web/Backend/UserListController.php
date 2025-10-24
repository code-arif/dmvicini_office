<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class UserListController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', '!=', 'admin');

            if ($request->has('role') && $request->role !== 'all') {
                $query->where('role', $request->role);
            }

            $users = $query->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = asset('default/default_image.jpg');
                    $email = $user->email;
                    $acceptStatus = $user->accept ? 'Accepted' : 'Declined';
                    $dropdownOptions = $user->accept
                        ? '<li><a class="dropdown-item decline-user" data-id="' . $user->id . '" data-action="decline" href="#">Decline</a></li>'
                        : '<li><a class="dropdown-item accept-user" data-id="' . $user->id . '" data-action="accept" href="#">Accept</a></li>';

                    return '
                        <div class="d-flex align-items-center">
                            <img src="' . $avatar . '" alt="avatar" class="rounded-circle me-2" width="35" height="35">
                            <div>
                                <div class="fw-bold">' . $email . '</div>
                                <div class="dropdown mt-1">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        ' . $acceptStatus . '
                                    </button>
                                    <ul class="dropdown-menu">
                                        ' . $dropdownOptions . '
                                    </ul>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('action', function ($user) {
                    return '
                        <button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }

        return view('backend.layouts.investors.index');
    }

    public function changeAcceptStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'action' => 'required|in:accept,decline',
        ]);

        $user = User::findOrFail($request->id);
        $user->accept = $request->action === 'accept' ? 1 : 0;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User ' . $request->action . 'ed successfully!',
        ]);
    }
}
