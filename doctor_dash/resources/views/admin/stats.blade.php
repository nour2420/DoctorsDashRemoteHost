@extends('layouts.admin')

@section('title', 'Analytics')
@section('header', 'Visitors Analytics')

@section('content')
    <div class="grid gap-6 md:grid-cols-3 mb-10">
        <div class="bh-card p-6 md:p-7">
            <p class="text-sm uppercase tracking-[0.22em] text-slate-400">Total Visits</p>
            <p class="mt-4 text-4xl md:text-5xl font-semibold">{{ $totalVisits }}</p>
            <p class="mt-2 text-xs text-slate-400">Today: <span class="font-semibold text-slate-100">{{ $todayVisits }}</span></p>
        </div>
        <div class="bh-card p-6 md:p-7">
            <p class="text-sm uppercase tracking-[0.22em] text-slate-400">Dashboard Visits</p>
            <p class="mt-4 text-4xl md:text-5xl font-semibold">{{ $dashboardVisits }}</p>
            <p class="mt-2 text-xs text-slate-400">Today: <span class="font-semibold text-slate-100">{{ $todayDashboardVisits }}</span></p>
        </div>
        <div class="bh-card p-6 md:p-7">
            <p class="text-sm uppercase tracking-[0.22em] text-slate-400">Website Visits</p>
            <p class="mt-4 text-4xl md:text-5xl font-semibold">{{ $websiteVisits }}</p>
            <p class="mt-2 text-xs text-slate-400">Today: <span class="font-semibold text-slate-100">{{ $todayWebsiteVisits }}</span></p>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="bh-card p-6 md:p-7">
            <h2 class="text-base md:text-lg font-semibold mb-4">Last 7 days</h2>
            <canvas id="visitsLast7Chart" class="mt-2 h-48 w-full"></canvas>
            <div class="mt-4 border-t border-slate-800/70 pt-3">
                <table class="w-full text-sm text-slate-200">
                    <thead class="text-slate-400 border-b border-slate-700/60">
                        <tr>
                            <th class="text-left py-2">Date</th>
                            <th class="text-right py-2">Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($last7Days as $row)
                            <tr class="border-b border-slate-800/60 last:border-0">
                                <td class="py-1.5">{{ $row->date }}</td>
                                <td class="py-1.5 text-right">{{ $row->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bh-card p-6 md:p-7">
            <h2 class="text-base md:text-lg font-semibold mb-4">Top pages</h2>
            <canvas id="topPathsChart" class="mt-2 h-48 w-full"></canvas>
            <div class="mt-4 border-t border-slate-800/70 pt-3">
                <table class="w-full text-sm text-slate-200">
                    <thead class="text-slate-400 border-b border-slate-700/60">
                        <tr>
                            <th class="text-left py-2">Page</th>
                            <th class="text-right py-2">Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topPaths as $index => $row)
                            <tr class="border-b border-slate-800/60 last:border-0">
                                <td class="py-1.5">
                                    <div>{{ $topPathLabels[$index] ?? ('/' . $row->path) }}</div>
                                </td>
                                <td class="py-1.5 text-right">{{ $row->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const last7Labels = @json($last7Labels);
            const last7Counts = @json($last7Counts);
            const topPathLabels = @json($topPathLabels);
            const topPathCounts = @json($topPathCounts);

            const last7Ctx = document.getElementById('visitsLast7Chart');
            if (last7Ctx) {
                new Chart(last7Ctx, {
                    type: 'bar',
                    data: {
                        labels: last7Labels,
                        datasets: [{
                            label: 'Visits',
                            data: last7Counts,
                            backgroundColor: 'rgba(255, 255, 255, 0.28)',
                            borderColor: 'rgba(255, 255, 255, 0.9)',
                            borderWidth: 1,
                            borderRadius: 10,
                            barPercentage: 0.7,
                            categoryPercentage: 0.7,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                borderColor: 'rgba(255, 255, 255, 0.25)',
                                borderWidth: 1,
                                titleColor: '#ffffff',
                                bodyColor: '#e5e7eb',
                            },
                        },
                        scales: {
                            x: {
                                ticks: { color: 'rgba(229, 231, 235, 0.85)', font: { size: 10 } },
                                grid: { display: false },
                            },
                            y: {
                                ticks: { color: 'rgba(229, 231, 235, 0.75)', font: { size: 10 } },
                                grid: { color: 'rgba(255, 255, 255, 0.10)' },
                                beginAtZero: true,
                                precision: 0,
                            },
                        },
                    },
                });
            }

            const topCtx = document.getElementById('topPathsChart');
            if (topCtx) {
                new Chart(topCtx, {
                    type: 'bar',
                    data: {
                        labels: topPathLabels,
                        datasets: [{
                            label: 'Visits',
                            data: topPathCounts,
                            backgroundColor: 'rgba(255, 255, 255, 0.18)',
                            borderColor: 'rgba(255, 255, 255, 0.55)',
                            borderWidth: 1,
                            borderRadius: 10,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        scales: {
                            x: {
                                ticks: { color: 'rgba(229, 231, 235, 0.75)', font: { size: 10 } },
                                grid: { display: false },
                            },
                            y: {
                                ticks: { color: 'rgba(229, 231, 235, 0.75)', font: { size: 10 } },
                                grid: { color: 'rgba(255, 255, 255, 0.10)' },
                                beginAtZero: true,
                                precision: 0,
                            },
                        },
                    },
                });
            }
        });
    </script>
@endpush
