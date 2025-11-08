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
            $query = User::with(['profile.firm', 'accessRequest', 'complianceAcknowledgment'])
                ->where('role', '!=', 'admin')
                ->latest('id');

            if ($request->has('role') && $request->role !== 'all') {
                $query->where('role', $request->role);
            }

            $users = $query->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $avatar = $user->avatar
                        ? asset('/' . $user->avatar)
                        : asset('default/default_image.jpg');

                    $email = $user->email;
                    $fullName = $user->profile
                        ? $user->profile->first_name . ' ' . $user->profile->last_name
                        : 'N/A';

                    $statusBadge = $this->getStatusBadge($user);

                    return '
                        <div class="d-flex align-items-center">
                            <img src="' . $avatar . '" alt="avatar" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                            <div>
                                <div class="fw-bold">' . $fullName . '</div>
                                <div class="text-muted small">' . $email . '</div>
                                <div class="mt-1">' . $statusBadge . '</div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('investor_info', function ($user) {
                    if (!$user->profile) {
                        return '<span class="text-muted">No profile data</span>';
                    }

                    $investorType = ucwords(str_replace('_', ' ', $user->profile->investor_type ?? 'N/A'));
                    $firmName = $user->profile->firm_name ?? 'N/A';
                    $country = $user->profile->country ?? 'N/A';

                    return '
                        <div>
                            <div><strong>Type:</strong> ' . $investorType . '</div>
                            <div><strong>Firm:</strong> ' . $firmName . '</div>
                            <div><strong>Country:</strong> ' . $country . '</div>
                        </div>
                    ';
                })
                ->addColumn('action', function ($user) {
                    $viewBtn = '<button class="btn btn-sm btn-info view-investor me-1" data-id="' . $user->id . '" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>';

                    $approveBtn = '';
                    if (!$user->is_active || $user->access_level !== 'full') {
                        $approveBtn = '<button class="btn btn-sm btn-success approve-investor me-1" data-id="' . $user->id . '" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>';
                    }

                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-investor" data-id="' . $user->id . '" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>';

                    return $viewBtn . $approveBtn . $deleteBtn;
                })
                ->rawColumns(['name', 'investor_info', 'action'])
                ->make(true);
        }

        return view('backend.layouts.investors.index');
    }

    private function getStatusBadge($user)
    {
        if ($user->is_active && $user->access_level === 'full') {
            return '<span class="badge bg-success">Approved</span>';
        } elseif ($user->access_level === 'provisional') {
            return '<span class="badge bg-warning">Provisional</span>';
        } elseif ($user->access_level === 'review') {
            return '<span class="badge bg-info">Under Review</span>';
        } elseif ($user->access_level === 'limited') {
            return '<span class="badge bg-secondary">Limited Access</span>';
        } else {
            return '<span class="badge bg-danger">Pending</span>';
        }
    }

    public function show($id)
    {
        $user = User::with(['profile.firm', 'accessRequest', 'complianceAcknowledgment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'avatar' => $user->avatar
                        ? asset('/' . $user->avatar)
                        : asset('default/default_image.jpg'),
                    'access_level' => $user->access_level,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at->format('M d, Y h:i A'),
                ],
                'profile' => $user->profile ? [
                    'first_name' => $user->profile->first_name,
                    'last_name' => $user->profile->last_name,
                    'title' => $user->profile->title,
                    'firm_name' => $user->profile->firm_name,
                    'phone' => $user->profile->phone,
                    'country' => $user->profile->country,
                    'investor_type' => ucwords(str_replace('_', ' ', $user->profile->investor_type ?? 'Other')),
                    'investor_type_other' => $user->profile->investor_type_other,
                ] : null,
                'firm' => $user->profile && $user->profile->firm ? [
                    'is_registered' => $user->profile->firm->is_registered,
                    'firm_crd' => $user->profile->firm->firm_crd,
                    'individual_crd' => $user->profile->firm->individual_crd,
                    'firm_aum' => $user->profile->firm->firm_aum
                        ? '$' . number_format($user->profile->firm->firm_aum)
                        : 'N/A',
                    'address' => $user->profile->firm->address,
                    'city' => $user->profile->firm->city,
                    'state' => $user->profile->firm->state,
                    'zip' => $user->profile->firm->zip,
                    'explanation_if_not_registered' => $user->profile->firm->explanation_if_not_registered,
                ] : null,
                'access_request' => $user->accessRequest ? [
                    'status' => ucfirst($user->accessRequest->status),
                    'verification_type' => $user->accessRequest->verification_type,
                    'verified_at' => $user->accessRequest->verified_at
                        ? $user->accessRequest->verified_at->format('M d, Y h:i A')
                        : null,
                    'admin_notes' => $user->accessRequest->admin_notes,
                    'verifier_document' => $user->accessRequest->verifier_document,
                    'verifier_reference' => $user->accessRequest->verifier_reference,
                ] : null,
                'compliance' => $user->complianceAcknowledgment ? [
                    'terms_agreed' => $user->complianceAcknowledgment->terms_agreed,
                    'terms_agreed_at' => $user->complianceAcknowledgment->terms_agreed_at
                        ? $user->complianceAcknowledgment->terms_agreed_at->format('M d, Y h:i A')
                        : null,
                    'privacy_agreed' => $user->complianceAcknowledgment->privacy_agreed,
                    'privacy_agreed_at' => $user->complianceAcknowledgment->privacy_agreed_at
                        ? $user->complianceAcknowledgment->privacy_agreed_at->format('M d, Y h:i A')
                        : null,
                    'investor_acknowledgment' => $user->complianceAcknowledgment->investor_acknowledgment,
                    'investor_acknowledgment_at' => $user->complianceAcknowledgment->investor_acknowledgment_at
                        ? $user->complianceAcknowledgment->investor_acknowledgment_at->format('M d, Y h:i A')
                        : null,
                    'confidentiality_agreed' => $user->complianceAcknowledgment->confidentiality_agreed,
                    'confidentiality_agreed_at' => $user->complianceAcknowledgment->confidentiality_agreed_at
                        ? $user->complianceAcknowledgment->confidentiality_agreed_at->format('M d, Y h:i A')
                        : null,
                    'marketing_opt_in' => $user->complianceAcknowledgment->marketing_opt_in,
                    'ip_address' => $user->complianceAcknowledgment->ip_address,
                    'user_agent' => $user->complianceAcknowledgment->user_agent,
                ] : null,
            ]
        ]);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::with('accessRequest')->findOrFail($request->id);

        $user->is_active = true;
        $user->access_level = 'full';
        $user->save();

        if ($user->accessRequest) {
            $user->accessRequest->update([
                'status' => 'approved',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Investor approved successfully! User now has full access.',
        ]);
    }

    public function changeAccessLevel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'access_level' => 'required|in:full,provisional,limited,review',
        ]);

        $user = User::findOrFail($request->id);
        $user->access_level = $request->access_level;

        if ($request->access_level === 'full') {
            $user->is_active = true;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Access level updated successfully!',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete admin users!',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Investor deleted successfully!',
        ]);
    }
}
