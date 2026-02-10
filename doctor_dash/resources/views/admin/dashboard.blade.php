@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Overview')

@section('content')
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-2">
        <div class="bh-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Total Users</p>
                    <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $totalUsers }}</p>
                </div>
                <div class="bh-card-icon h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M7.5 7.5a3 3 0 116 0 3 3 0 01-6 0z" />
                        <path d="M4.5 18a4.5 4.5 0 019 0v.75a.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V18z" />
                        <path d="M15.75 8.25a2.25 2.25 0 112.25 2.25 2.25 2.25 0 01-2.25-2.25z" />
                        <path d="M15.004 18.75a3.751 3.751 0 013.746-3.5 3.75 3.75 0 013.75 3.75v.75a.75.75 0 01-.75.75h-5.996" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bh-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Admins</p>
                    <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $totalAdmins }}</p>
                </div>
                <div class="bh-card-icon h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M12 2.25a.75.75 0 01.673.418l1.89 3.78 4.176.607a.75.75 0 01.416 1.279L16.5 11.25l.714 4.164a.75.75 0 01-1.088.791L12 14.708l-4.126 2.197a.75.75 0 01-1.088-.79L7.5 11.25 4.845 8.334a.75.75 0 01.416-1.28l4.176-.606 1.89-3.78A.75.75 0 0112 2.25z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bh-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Assistants</p>
                    <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $totalAssistants }}</p>
                </div>
                <div class="bh-card-icon h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M18 8.25A3.75 3.75 0 1114.25 12 3.75 3.75 0 0118 8.25z" />
                        <path d="M6 8.25A3.75 3.75 0 112.25 12 3.75 3.75 0 016 8.25z" />
                        <path d="M6 13.5a4.5 4.5 0 00-4.5 4.5v1.25A1.25 1.25 0 002.75 20.5h6.5A1.25 1.25 0 0010.5 19.25V18a4.5 4.5 0 00-4.5-4.5z" />
                        <path d="M18 13.5a4.5 4.5 0 00-4.5 4.5v1.25a1.25 1.25 0 001.25 1.25h6.5a1.25 1.25 0 001.25-1.25V18A4.5 4.5 0 0018 13.5z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bh-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Visits (Today)</p>
                    <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $todayVisits }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">Total: {{ $totalVisits }}</p>
                </div>
                <div class="bh-card-icon h-10 w-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M12 4.5a7.5 7.5 0 107.5 7.5A7.509 7.509 0 0012 4.5zm0 1.5a6 6 0 11-6 6 6.007 6.007 0 016-6zm-.75 2.25a.75.75 0 011.5 0v3.19l2.28 2.28a.75.75 0 11-1.06 1.06l-2.47-2.47A.75.75 0 0111.25 12z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection
