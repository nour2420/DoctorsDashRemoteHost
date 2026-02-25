@extends('layouts.user')

@section('title', 'My Cases')
@section('header', 'My Cases')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base md:text-lg font-semibold">Cases</h2>
        <a href="{{ route('user.reports.create') }}"
            class="inline-flex items-center justify-center rounded-full bg-yellow-400 px-4 py-2 text-xs font-semibold text-black hover:bg-yellow-300 transition-colors">Upload
            new case</a>
    </div>

    <div class="rounded-2xl bg-slate-900/80 border border-slate-700/70 p-4 text-xs">
        @if ($reports->isEmpty())
            <p class="text-slate-400">You have not uploaded any cases yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs text-slate-200">
                    <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-700/70">
                        <tr>
                            <th class="text-left py-2 px-2">Patient Name</th>
                            <th class="text-left py-2 px-2">Uploaded at</th>
                            <th class="text-left py-2 px-2">File</th>
                            <th class="text-left py-2 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr class="border-b border-slate-800/70 last:border-0">
                                <td class="py-2 px-2 text-amber-300 font-semibold">{{ $report->title }}</td>
                                <td class="py-2 px-2 text-slate-400">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2 px-2">
                                    <button type="button"
                                        class="text-xs text-amber-300 hover:text-amber-200 underline"
                                        data-preview-url="{{ asset('storage/' . $report->file_path) }}"
                                        data-preview-mime="{{ $report->mime_type }}"
                                        data-preview-title="{{ $report->title }}"
                                        data-preview-name="{{ $report->original_name }}"
                                        data-preview-description="{{ $report->description }}"
                                        data-preview-created="{{ $report->created_at->format('Y-m-d H:i') }}">
                                        View
                                    </button>
                                </td>
                                <td class="py-2 px-2">
                                    <div class="flex gap-2">
                                        <a href="{{ route('user.reports.download', $report) }}"
                                            class="rounded-full border border-blue-500/60 px-3 py-1 text-[11px] font-semibold text-blue-300 hover:bg-blue-500/10 transition-colors">Download</a>
                                        <a href="{{ route('user.reports.edit', $report) }}"
                                            class="rounded-full border border-slate-600/70 px-3 py-1 text-[11px] font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">Edit</a>
                                        <form action="{{ route('user.reports.destroy', $report) }}" method="POST"
                                            onsubmit="return confirm('Delete this case?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full border border-red-500/60 px-3 py-1 text-[11px] font-semibold text-red-300 hover:bg-red-500/10 transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-xs text-slate-400">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <div id="case-preview-panel"
        class="mt-6 hidden rounded-2xl bg-slate-900/80 border border-slate-700/70 p-4 text-xs">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 id="preview-title" class="text-sm font-semibold text-slate-100">Selected case</h3>
                <p id="preview-meta" class="text-[11px] text-slate-400 mt-0.5"></p>
            </div>
            <button type="button" id="preview-clear"
                class="text-[11px] text-slate-400 hover:text-amber-300">Clear</button>
        </div>
        <div id="preview-description" class="mb-3 text-[11px] text-slate-300"></div>
        <div id="preview-content" class="bg-slate-950/80 rounded-xl border border-slate-800 flex items-center justify-center p-4 overflow-auto">
            <p class="text-xs text-slate-400">Select a case to preview it here.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var buttons = document.querySelectorAll('[data-preview-url]');
            var panel = document.getElementById('case-preview-panel');
            var content = document.getElementById('preview-content');
            var titleEl = document.getElementById('preview-title');
            var metaEl = document.getElementById('preview-meta');
            var descEl = document.getElementById('preview-description');
            var clearBtn = document.getElementById('preview-clear');

            if (!buttons.length || !panel || !content || !titleEl || !metaEl || !descEl || !clearBtn) return;

            function showPanel() {
                panel.classList.remove('hidden');
            }

            function clearPanel() {
                panel.classList.add('hidden');
                content.innerHTML = '<p class="text-xs text-slate-400">Select a case to preview it here.</p>';
                titleEl.textContent = 'Selected case';
                metaEl.textContent = '';
                descEl.textContent = '';
            }

            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var url = this.getAttribute('data-preview-url');
                    var mime = this.getAttribute('data-preview-mime') || '';
                    var title = this.getAttribute('data-preview-title') || 'Case preview';
                    var name = this.getAttribute('data-preview-name') || '';
                    var description = this.getAttribute('data-preview-description') || '';
                    var createdAt = this.getAttribute('data-preview-created') || '';

                    titleEl.textContent = title;
                    metaEl.textContent = (name ? name + ' b7 ' : '') + (createdAt ? createdAt : '');
                    descEl.textContent = description;

                    content.innerHTML = '';

                    var lowerUrl = url.toLowerCase();

                    if (mime.startsWith('image/') || /\.(png|jpe?g)$/i.test(lowerUrl)) {
                        var img = document.createElement('img');
                        img.src = url;
                        img.className = 'max-h-[60vh] max-w-full rounded-lg border border-slate-800 object-contain';
                        content.appendChild(img);
                    } else if (mime === 'application/pdf' || lowerUrl.endsWith('.pdf')) {
                        var embed = document.createElement('embed');
                        embed.src = url;
                        embed.type = 'application/pdf';
                        embed.className = 'w-full h-[60vh] rounded-lg border border-slate-800';
                        content.appendChild(embed);
                    } else {
                        var link = document.createElement('a');
                        link.href = url;
                        link.target = '_blank';
                        link.className = 'text-xs text-amber-300 hover:text-amber-200 underline';
                        link.textContent = 'Open file in new tab';

                        var msg = document.createElement('p');
                        msg.className = 'text-xs text-slate-400 mb-2';
                        msg.textContent = 'Preview is not available for this file type.';

                        content.appendChild(msg);
                        content.appendChild(link);
                    }

                    showPanel();
                });
            });

            clearBtn.addEventListener('click', clearPanel);
        });
    </script>
@endpush

