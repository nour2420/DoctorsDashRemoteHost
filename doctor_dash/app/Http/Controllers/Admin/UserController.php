<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $review = $request->query('review');

        $query = User::withCount([
            'reports',
            'reports as unreviewed_reports_count' => function ($q) {
                $q->whereNull('reviewed_at');
            },
        ])->orderByDesc('created_at');

        if ($role && in_array($role, ['admin', 'assistant', 'user'], true)) {
            $query->where('role', $role);
        } else {
            $role = null;
        }

        if ($review === 'pending') {
            $query->where('review_status', 'pending');
        } elseif ($review === 'reviewed') {
            $query->where('review_status', 'reviewed');
        } else {
            $review = null;
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roleFilter' => $role,
            'reviewFilter' => $review,
        ]);
    }

    public function toggleReportReview(Request $request, Report $report)
    {
        $state = $request->input('state');

        if ($state === 'reviewed') {
            $report->reviewed_at = now();
        } elseif ($state === 'pending') {
            $report->reviewed_at = null;
        } else {
            $report->reviewed_at = $report->reviewed_at ? null : now();
        }
        $report->save();

        return back()->with('status', 'Report review status updated successfully.');
    }

    public function setUserReportsReview(Request $request, User $user)
    {
        $state = $request->input('state');

        if ($state === 'reviewed') {
            $user->reports()->whereNull('reviewed_at')->update([
                'reviewed_at' => now(),
            ]);
            $user->review_status = 'reviewed';
            $user->save();
        } elseif ($state === 'pending') {
            $user->reports()->whereNotNull('reviewed_at')->update([
                'reviewed_at' => null,
            ]);
            $user->review_status = 'pending';
            $user->save();
        }

        return back()->with('status', 'User reports review status updated successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,assistant,user'],
        ]);

        $currentUser = $request->user();

        // لا تسمح بتغيير دورك أنت نفسك من هنا
        if ($currentUser->id === $user->id && $data['role'] !== $user->role) {
            return back()->with('status', 'You cannot change your own role from this screen.');
        }

        // لا تسمح بإزالة آخر أدمن في النظام
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            $otherAdmins = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherAdmins === 0) {
                return back()->with('status', 'You cannot remove the last admin.');
            }
        }

        $user->role = $data['role'];
        $user->save();

        return back()->with('status', 'User role updated successfully.');
    }

    public function show(User $user)
    {
        $visitsCount = Visit::where('user_id', $user->id)->count();
        $lastVisit = Visit::where('user_id', $user->id)->latest('created_at')->first();

        $reports = $user->reports()->latest()->take(5)->get();

        return view('admin.users.show', [
            'user' => $user,
            'visitsCount' => $visitsCount,
            'lastVisit' => $lastVisit,
            'reports' => $reports,
        ]);
    }

    public function reports(User $user)
    {
        $review = request()->query('review');
        if ($review === null) {
            $review = 'pending';
        }
        $query = $user->reports()->latest();

        if ($review === 'pending') {
            $query->whereNull('reviewed_at');
        } elseif ($review === 'reviewed') {
            $query->whereNotNull('reviewed_at');
        } else {
            $review = null;
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.users.reports', [
            'user' => $user,
            'reports' => $reports,
            'reviewFilter' => $review,
        ]);
    }
}
