@extends('layouts.user')

@section('title', 'All doctors group')
@section('header', 'Group chat with all doctors')

@section('content')
    <div class="rounded-3xl bg-slate-900/80 border border-slate-700/70 flex flex-col max-h-[78vh]">
        <div class="border-b border-slate-800 px-5 py-4 flex items-center justify-between text-sm">
            <div>
                <p class="font-semibold text-sm md:text-base">All Doctors</p>
                <p class="text-slate-400 text-xs md:text-sm">Group conversation with all admins and assistants</p>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4 text-sm">
            @forelse ($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div
                        class="max-w-md rounded-2xl px-4 py-3 {{ $message->sender_id === auth()->id() ? 'bg-amber-400 text-black' : 'bg-slate-800 text-slate-100' }}">
                        <p class="text-[11px] font-semibold mb-1 {{ $message->sender_id === auth()->id() ? 'text-slate-800' : 'text-slate-300' }}">
                            {{ optional($message->sender)->name ?? 'Doctor' }}
                        </p>
                        <p>{{ $message->body }}</p>
                        <p class="mt-1 text-[11px] font-semibold {{ $message->sender_id === auth()->id() ? 'text-amber-900/90' : 'text-amber-300/90' }}">
                            {{ $message->created_at->format('g:i A d/m') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">No messages yet. Start the conversation.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('user.chats.group.send') }}"
            class="border-t border-slate-800 px-5 py-4 flex gap-3 text-sm">
            @csrf
            <input type="text" name="body"
                class="flex-1 rounded-lg border border-slate-700 bg-slate-950/80 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400"
                placeholder="Type a message...">
            <button type="submit"
                class="rounded-lg bg-amber-400 px-5 py-3 font-semibold text-sm text-black hover:bg-amber-300">Send</button>
        </form>
    </div>
@endsection
