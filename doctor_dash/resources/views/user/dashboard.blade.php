@extends('layouts.user')

@section('title', 'User Dashboard')
@section('header', 'My Dashboard')

@section('content')
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div
            class="bh-card p-6">
            <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">My Reports</p>
            <p class="mt-4 text-3xl md:text-4xl font-semibold">{{ $reportsCount }}</p>
            <div class="mt-4 flex gap-3 text-xs">
                <a href="{{ route('user.reports.index') }}"
                    class="inline-flex items-center justify-center rounded-full border border-slate-600/70 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">View
                    all</a>
                <a href="{{ route('user.reports.create') }}"
                    class="inline-flex items-center justify-center rounded-full bg-amber-400 px-3 py-1.5 text-xs font-semibold text-black hover:bg-amber-300 transition-colors">Upload
                    report</a>
            </div>
        </div>
        <div
            class="bh-card p-6">
            <p class="text-xs md:text-sm uppercase tracking-[0.22em] text-slate-400">Chats</p>
            <p class="mt-4 text-sm text-slate-200">Talk with your doctors individually or in a group.</p>
            <div class="mt-4 flex flex-wrap gap-3 text-xs">
                <a href="{{ route('user.chats.doctors') }}"
                    class="inline-flex items-center justify-center rounded-full border border-slate-600/70 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">With
                    doctors</a>
                <a href="{{ route('user.chats.group') }}"
                    class="inline-flex items-center justify-center rounded-full bg-amber-400 px-3 py-1.5 text-xs font-semibold text-black hover:bg-amber-300 transition-colors">All
                    doctors group</a>
            </div>
        </div>
    </div>
@endsection
