@extends('layouts.user')

@section('title', 'Notifications')
@section('header', 'Notifications')

@section('content')
    <div class="space-y-4">
        <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-4">
            <h2 class="text-sm font-semibold text-slate-200 mb-3">Messages from admin</h2>

            @forelse ($doctorNotifications as $doctor)
                <a href="{{ route('user.chats.doctors.show', $doctor->id) }}"
                    class="flex items-start gap-3 rounded-xl border border-slate-700/70 bg-slate-900/70 px-3 py-2 mb-2 hover:border-amber-400/80 hover:bg-slate-900 transition-colors">
                    <div
                        class="h-8 w-8 flex items-center justify-center rounded-full bg-amber-400/20 text-[11px] font-semibold text-amber-200">
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
                    <span
                        class="ml-2 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] leading-none px-1.5 py-0.5">
                        1
                    </span>
                </a>
            @empty
                <p class="text-xs text-slate-500">No new messages from admin.</p>
            @endforelse
        </div>
    </div>
@endsection
