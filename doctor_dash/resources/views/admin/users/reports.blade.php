@extends('layouts.admin')

@section('title', 'User Reports')
@section('header', 'User Reports')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 text-xs">
            <h2 class="text-sm font-semibold mb-3">User</h2>
            <p class="mb-1"><span class="text-slate-400">Name:</span> <span class="text-slate-100">{{ $user->name }}</span></p>
            <p class="mb-1"><span class="text-slate-400">Email:</span> <span class="text-slate-100">{{ $user->email }}</span></p>
            <p class="mb-1"><span class="text-slate-400">Role:</span> <span class="text-amber-300 uppercase text-[11px] tracking-[0.18em]">{{ $user->role }}</span></p>
        </div>

        <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 text-xs">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold">Reports</h2>
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('admin.users.reports', $user) }}" class="flex items-center gap-2">
                        <span class="text-[11px] text-slate-400">Filter:</span>
                        <select name="review"
                            class="rounded-full border border-slate-600 bg-slate-950/70 px-3 py-1 text-[11px] focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                            <option value="" @selected(empty($reviewFilter))>All</option>
                            <option value="pending" @selected(($reviewFilter ?? null) === 'pending')>Pending</option>
                            <option value="reviewed" @selected(($reviewFilter ?? null) === 'reviewed')>Reviewed</option>
                        </select>
                        <button type="submit" class="bh-btn">Apply</button>
                    </form>

                    <a href="{{ route('admin.users.show', $user) }}" class="text-[11px] text-amber-300 hover:text-amber-200">Back to profile</a>
                </div>
            </div>

            @if ($reports->isEmpty())
                <p class="text-slate-400 text-xs">No reports uploaded by this user.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-xs">
                        <thead class="border-b border-slate-800 text-slate-400">
                            <tr>
                                <th class="py-2 pr-4">Title</th>
                                <th class="py-2 pr-4">Description</th>
                                <th class="py-2 pr-4">Review</th>
                                <th class="py-2 pr-4">Uploaded at</th>
                                <th class="py-2 pr-4">File name</th>
                                <th class="py-2 pr-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach ($reports as $report)
                                <tr>
                                    <td class="py-2 pr-4 text-slate-100">{{ $report->title }}</td>
                                    <td class="py-2 pr-4 text-slate-300 max-w-xs truncate">{{ $report->description }}</td>
                                    <td class="py-2 pr-4">
                                        @if ($report->reviewed_at)
                                            <span class="bh-badge bh-badge--reviewed">
                                                Reviewed
                                            </span>
                                        @else
                                            <span class="bh-badge bh-badge--pending">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2 pr-4 text-slate-300">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="py-2 pr-4">
                                        <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank"
                                            class="text-[11px] text-amber-300 hover:text-amber-200">{{ $report->original_name }}</a>
                                    </td>
                                    <td class="py-2 pr-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.reports.review', $report) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="state" value="reviewed" />
                                                <button type="submit" class="bh-btn bh-btn--review">تمت المراجعة</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reports.review', $report) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="state" value="pending" />
                                                <button type="submit" class="bh-btn bh-btn--unreview">في انتظار المراجعة</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
