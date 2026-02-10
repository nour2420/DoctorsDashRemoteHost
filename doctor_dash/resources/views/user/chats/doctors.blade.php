@extends('layouts.user')

@section('title', 'Chat with doctors')
@section('header', 'Chats with Doctors')

@section('content')
    <div class="grid gap-8 md:grid-cols-3">
        <div class="rounded-3xl bg-slate-900/80 border border-slate-700/70 p-5 md:p-6 md:col-span-1">
            <h2 class="text-base font-semibold mb-3">Doctors</h2>
            <div class="mb-3">
                <input id="doctor-search" type="text" placeholder="Search by name or email"
                    class="w-full rounded-lg border border-slate-700 bg-slate-950/80 px-3 py-2 text-xs text-slate-200 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">
            </div>
            <ul class="space-y-2 text-sm">
                @forelse ($doctors as $doctor)
                    <li data-doctor-item data-search-text="{{ strtolower($doctor->name . ' ' . $doctor->email) }}">
                        <a href="{{ route('user.chats.doctors.show', $doctor) }}"
                            class="flex items-center justify-between gap-3 rounded-xl px-4 py-3 text-sm {{ optional($activeDoctor)->id === $doctor->id ? 'bg-slate-800 text-amber-300' : 'hover:bg-slate-900 text-slate-200' }}">
                            <div class="min-w-0">
                                <p class="font-medium truncate max-w-[140px]">{{ $doctor->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate max-w-[160px]">{{ $doctor->email }}</p>
                            </div>
                            <div class="text-right min-w-0">
                                @if ($doctor->last_message_at)
                                    <p class="text-[11px] text-slate-400">
                                        {{ $doctor->last_message_at->format('g:i A') }}
                                    </p>
                                @endif
                                <p class="mt-1 text-[11px] text-slate-500 max-w-[150px] truncate">
                                    @if ($doctor->last_message)
                                        {{ \Illuminate\Support\Str::limit($doctor->last_message, 40) }}
                                    @else
                                        No messages yet
                                    @endif
                                </p>
                                @if (!empty($doctor->last_message_from_self) && $doctor->last_message)
                                    <span
                                        class="mt-1 inline-flex items-center rounded-full bg-amber-400/10 px-2 py-0.5 text-[10px] font-semibold text-amber-300 border border-amber-400/50">Waiting
                                        reply</span>
                                @endif
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="text-xs text-slate-500">No doctors available.</li>
                @endforelse
            </ul>
        </div>

        <div class="md:col-span-2 rounded-3xl bg-slate-900/80 border border-slate-700/70 flex flex-col max-h-[78vh]">
            @if ($activeDoctor && $conversation)
                <div class="border-b border-slate-800 px-5 py-4 flex items-center justify-between text-sm">
                    <div>
                        <p class="font-semibold text-sm md:text-base">{{ $activeDoctor->name }}</p>
                        <p class="text-slate-400 text-xs md:text-sm">Doctor</p>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4 text-sm">
                    @foreach ($messages as $message)
                        <div
                            class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-md rounded-2xl px-4 py-3 {{ $message->sender_id === auth()->id() ? 'bg-amber-400 text-black' : 'bg-slate-800 text-slate-100' }}">
                                <p>{{ $message->body }}</p>
                                <p class="mt-1 text-[11px] font-semibold {{ $message->sender_id === auth()->id() ? 'text-amber-900/90' : 'text-amber-300/90' }}">
                                    {{ $message->created_at->format('g:i A d/m') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('user.chats.doctors.send', $activeDoctor) }}"
                    class="border-t border-slate-800 px-5 py-4 flex gap-3 text-sm">
                    @csrf
                    <input type="text" name="body"
                        class="flex-1 rounded-lg border border-slate-700 bg-slate-950/80 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400"
                        placeholder="Type a message...">
                    <button type="submit"
                        class="rounded-lg bg-amber-400 px-5 py-3 font-semibold text-sm text-black hover:bg-amber-300">Send</button>
                </form>
            @else
                <div class="flex-1 flex items-center justify-center text-sm text-slate-400">
                    Select a doctor to start chatting.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('doctor-search');
            if (!searchInput) return;

            var items = document.querySelectorAll('[data-doctor-item]');

            searchInput.addEventListener('input', function() {
                var term = this.value.toLowerCase();

                items.forEach(function(item) {
                    var text = item.getAttribute('data-search-text') || '';
                    if (text.indexOf(term) !== -1) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endpush
