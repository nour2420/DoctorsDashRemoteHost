@extends('layouts.user')

@section('title', 'User Dashboard')
@section('header', 'My Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="bh-card p-6">
                <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">My Cases</p>
                <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $reportsCount }}</p>
                <div class="mt-4 flex gap-3 text-xs">
                    <a href="{{ route('user.reports.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-slate-600/70 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">View
                        all</a>
                    <a href="{{ route('user.reports.create') }}"
                        class="inline-flex items-center justify-center rounded-full bg-amber-400 px-3 py-1.5 text-xs font-semibold text-black hover:bg-amber-300 transition-colors">Upload
                        case</a>
                </div>
            </div>

            <div class="bh-card p-6">
                <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Pending Review</p>
                <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $pendingCasesCount ?? 0 }}</p>
                <p class="mt-3 text-xs text-slate-400">Cases waiting for review</p>
            </div>

            <div class="bh-card p-6">
                <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Reviewed</p>
                <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $reviewedCasesCount ?? 0 }}</p>
                <p class="mt-3 text-xs text-slate-400">Reviewed cases</p>
            </div>

            <div class="bh-card p-6">
                <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Unread Messages</p>
                <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $unreadMessagesCount ?? 0 }}</p>
                <div class="mt-4 flex gap-3 text-xs">
                    <a href="{{ route('user.notifications.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-slate-600/70 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">View
                        messages</a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 text-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold">Recent Cases</h2>
                    <a href="{{ route('user.reports.index') }}" class="text-[11px] text-amber-300 hover:text-amber-200">View all</a>
                </div>

                @if (empty($recentCases) || $recentCases->isEmpty())
                    <p class="text-slate-400 text-xs">No cases yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-800 text-slate-400">
                                <tr>
                                    <th class="py-2 pr-4">Patient Name</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4">Uploaded</th>
                                    <th class="py-2 pr-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach ($recentCases as $case)
                                    <tr>
                                        <td class="py-2 pr-4 text-slate-100">{{ $case->title }}</td>
                                        <td class="py-2 pr-4">
                                            @if ($case->reviewed_at)
                                                <span class="inline-flex items-center rounded-full border border-emerald-400/40 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-semibold text-emerald-200">Reviewed</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full border border-amber-400/40 bg-amber-500/10 px-2 py-0.5 text-[10px] font-semibold text-amber-200">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-slate-300">{{ $case->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="py-2 pr-4 text-right">
                                            <a href="{{ route('user.reports.edit', $case) }}" class="text-[11px] text-amber-300 hover:text-amber-200">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-5 text-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold">Recent Messages</h2>
                    <a href="{{ route('user.notifications.index') }}" class="text-[11px] text-amber-300 hover:text-amber-200">View all</a>
                </div>

                @if (empty($doctorNotifications) || $doctorNotifications->isEmpty())
                    <p class="text-slate-400 text-xs">No new messages.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($doctorNotifications->take(5) as $doctor)
                            <a href="{{ route('user.chats.doctors.show', $doctor->id) }}"
                                class="flex items-start gap-3 rounded-xl border border-slate-700/70 bg-slate-900/70 px-3 py-2 hover:border-amber-400/80 hover:bg-slate-900 transition-colors">
                                <div class="h-8 w-8 flex items-center justify-center rounded-full bg-amber-400/20 text-[11px] font-semibold text-amber-200">
                                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-xs font-semibold text-slate-100 truncate">{{ $doctor->name }}</p>
                                        @if ($doctor->last_message_at)
                                            <span class="text-[10px] text-slate-400">
                                                {{ $doctor->last_message_at->format('g:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-[11px] text-slate-400 truncate">
                                        {{ \Illuminate\Support\Str::limit($doctor->last_message, 80) }}
                                    </p>
                                </div>
                                <span class="ml-2 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] leading-none px-1.5 py-0.5">1</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
