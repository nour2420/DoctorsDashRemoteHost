<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalAssistants = User::where('role', 'assistant')->count();

        $totalVisits = Visit::where('path', '!=', 'auth/status')->count();
        $todayVisits = Visit::where('path', '!=', 'auth/status')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalAssistants' => $totalAssistants,
            'totalVisits' => $totalVisits,
            'todayVisits' => $todayVisits,
        ]);
    }

    public function stats()
    {
        $totalVisits = Visit::count();
        $todayVisits = Visit::whereDate('created_at', now()->toDateString())->count();

        $dashboardVisits = Visit::where('path', 'like', 'admin%')->count();
        $websiteVisits = $totalVisits - $dashboardVisits;

        $todayDashboardVisits = Visit::where('path', 'like', 'admin%')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $todayWebsiteVisits = $todayVisits - $todayDashboardVisits;

        $last7Days = Visit::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('path', '!=', 'auth/status')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Sort ascending for charts so days go from oldest to newest
        $last7Sorted = $last7Days->sortBy('date')->values();
        $last7Labels = $last7Sorted->pluck('date');
        $last7Counts = $last7Sorted->pluck('count');

        $topPaths = Visit::selectRaw('path, COUNT(*) as count')
            ->where('path', '!=', 'auth/status')
            ->groupBy('path')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $pageNames = [
            '' => 'Home',
            'about' => 'Services',
            'contact' => 'Upload Your Case',
            'login' => 'Login',
            'register' => 'Register',
        ];

        $topPathLabels = $topPaths
            ->map(function ($row) use ($pageNames) {
                $path = $row->path ?? '';
                $trimmed = trim($path, '/');

                if (isset($pageNames[$trimmed])) {
                    return $pageNames[$trimmed];
                }

                if (Str::startsWith($trimmed, 'admin')) {
                    return 'Admin Dashboard';
                }

                return $trimmed === '' ? '/' : '/' . $trimmed;
            })
            ->values();
        $topPathCounts = $topPaths->pluck('count');

        return view('admin.stats', [
            'totalVisits' => $totalVisits,
            'todayVisits' => $todayVisits,
            'last7Days' => $last7Days,
            'topPaths' => $topPaths,
            'last7Labels' => $last7Labels,
            'last7Counts' => $last7Counts,
            'topPathLabels' => $topPathLabels,
            'topPathCounts' => $topPathCounts,
            'dashboardVisits' => $dashboardVisits,
            'websiteVisits' => $websiteVisits,
            'todayDashboardVisits' => $todayDashboardVisits,
            'todayWebsiteVisits' => $todayWebsiteVisits,
        ]);
    }
}
