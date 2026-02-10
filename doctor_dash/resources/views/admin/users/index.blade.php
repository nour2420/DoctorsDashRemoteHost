@extends('layouts.admin')

@section('title', 'Users')
@section('header', 'All Users')

@section('content')
    <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-4">
        <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-[25px] font-semibold">Total Users</h2>
                <p class="text-[15px] text-slate-400">Manage Roles , Reportes and Filter By Type</p>
            </div>
            <div class="flex items-center gap-2">
                <input id="usersSearch" type="text" placeholder="Search name / email / phone"
                    class="w-56 rounded-full border border-slate-600 bg-slate-950/70 px-4 py-1.5 text-[11px] text-slate-200 placeholder:text-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400" />
            </div>
            <form method="GET" action="{{ route('admin.users.index') }}"
                class="flex items-center gap-2 text-[11px] text-slate-300">
                <span>Role:</span>
                <select name="role"
                    class="rounded-full border border-slate-600 bg-slate-950/70 px-3 py-1 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                    <option value="" @selected(empty($roleFilter))>All</option>
                    <option value="admin" @selected(($roleFilter ?? null) === 'admin')>Admins</option>
                    <option value="assistant" @selected(($roleFilter ?? null) === 'assistant')>Assistants</option>
                    <option value="user" @selected(($roleFilter ?? null) === 'user')>Users</option>
                </select>

                <span class="ml-2">Status:</span>
                <select name="review"
                    class="rounded-full border border-slate-600 bg-slate-950/70 px-3 py-1 focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                    <option value="" @selected(empty($reviewFilter))>All</option>
                    <option value="pending" @selected(($reviewFilter ?? null) === 'pending')>Pending</option>
                    <option value="reviewed" @selected(($reviewFilter ?? null) === 'reviewed')>Reviewed</option>
                </select>
                @if (!empty($roleFilter) || !empty($reviewFilter))
                    <a href="{{ route('admin.users.index') }}"
                        class="rounded-full border border-slate-600/70 px-3 py-1 text-[10px] font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">Reset</a>
                @endif
                <button type="submit"
                    class="rounded-full border border-slate-600/70 px-3 py-1 text-[10px] font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">Apply</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-slate-200">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-700/70">
                    <tr>
                        <th class="text-left py-2 px-2 text-[14px]">Name</th>
                        <th class="text-left py-2 px-2 text-[14px]">Email</th>
                        <th class="text-left py-2 px-2 text-[14px]">Address</th>
                        <th class="text-left py-2 px-2 text-[14px]">Phone</th>
                        <th class="text-left py-2 px-2 text-[14px]">Role</th>
                        <th class="text-left py-2 px-2 text-[14px]">Status</th>
                        <th class="text-right py-2 px-2 text-[14px]">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-slate-800/70 last:border-0">
                            <td class="py-3 px-3 text-amber-300 font-semibold text-[14px]">
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="hover:text-amber-300 transition-colors">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td class="py-2 px-2 text-[14px]">{{ $user->email }}</td>
                            <td class="py-2 px-2 text-[14px]">{{ $user->address ?? '-' }}</td>
                            <td class="py-2 px-2 text-[14px]">{{ $user->phone ?? '-' }}</td>
                            <td class="py-2 px-2 text-[14px]">
                                @if (auth()->id() === $user->id)
                                    <span
                                        class="inline-flex items-center rounded-full border border-amber-400/70 bg-amber-400/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-amber-300">
                                        {{ $user->role }} (you)
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}"
                                        class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role"
                                            class="rounded-full border border-slate-600 bg-slate-950/70 px-2 py-1 text-[11px] uppercase tracking-[0.16em] focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                                            <option value="user" @selected($user->role === 'user')>User</option>
                                            <option value="assistant" @selected($user->role === 'assistant')>Assistant</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        </select>
                                        <button type="submit"
                                            class="rounded-full border border-slate-600/70 px-2 py-1 text-[10px] font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">Save</button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-2 px-2 text-[14px]">
                                @php
                                    $status = ($user->review_status ?? 'pending');
                                @endphp
                                @if ($status === 'reviewed')
                                    <span class="bh-badge bh-badge--reviewed">Reviewed</span>
                                @else
                                    <span class="bh-badge bh-badge--pending">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-2 text-right">
                                <form method="POST" action="{{ route('admin.users.reports.review', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="state"
                                        onchange="this.form.submit()"
                                        class="rounded-full border border-slate-600 bg-slate-950/70 px-3 py-1 text-[11px] focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400">
                                        <option value="pending" @selected(($user->review_status ?? 'pending') === 'pending')>Pending</option>
                                        <option value="reviewed" @selected(($user->review_status ?? 'pending') === 'reviewed')>Reviewed</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-xs text-slate-400">
            {{ $users->links() }}
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('usersSearch');
            const table = document.querySelector('table');
            if (!input || !table) return;

            const rows = Array.from(table.querySelectorAll('tbody tr'));

            function normalize(value) {
                return (value || '').toLowerCase().replace(/\s+/g, ' ').trim();
            }

            function applyFilter() {
                const q = normalize(input.value);
                rows.forEach((row) => {
                    const text = normalize(row.innerText);
                    row.style.display = !q || text.includes(q) ? '' : 'none';
                });
            }

            input.addEventListener('input', applyFilter);
        })();
    </script>
@endsection
