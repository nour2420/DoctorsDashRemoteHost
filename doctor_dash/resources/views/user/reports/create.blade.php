@extends('layouts.user')

@section('title', 'Upload case')
@section('header', 'Upload new case')

@section('content')
    <div class="max-w-xl mx-auto rounded-2xl bg-slate-900/80 border border-slate-700/70 p-6 text-xs">
        <h2 class="text-base md:text-lg font-semibold mb-4">Upload new case</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="bh-upload-case-form" action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block mb-1">Patient Name</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required
                    class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div>
                <label for="description" class="block mb-1">Description (optional)</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400">{{ old('description') }}</textarea>
            </div>
            <div class="space-y-2">
                <label class="block mb-1">Files (any type, large sizes allowed)</label>
                <div id="file-inputs" class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input id="files" name="files[]" type="file" required data-report-file-input
                            class="w-full text-xs text-slate-200 file:mr-3 file:rounded-md file:border-0 file:bg-yellow-400 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-black hover:file:bg-yellow-300">
                    </div>
                </div>
                <button type="button" id="add-file-input"
                    class="inline-flex items-center gap-1 rounded-full border border-slate-700 px-3 py-1 text-[11px] font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">
                    <span class="text-sm">+</span>
                    <span>Add another file</span>
                </button>
                <div id="file-previews" class="space-y-2 pt-1"></div>
            </div>
            <div id="bh-upload-progress" class="hidden space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-semibold text-slate-200">Uploading...</p>
                    <p id="bh-upload-progress-text" class="text-[11px] font-semibold text-slate-300">0%</p>
                </div>
                <div class="h-2 w-full rounded-full bg-slate-800 overflow-hidden border border-slate-700/70">
                    <div id="bh-upload-progress-bar" class="h-full w-0 bg-yellow-400 transition-[width] duration-150"></div>
                </div>
            </div>
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-amber-400 px-4 py-2 text-xs font-semibold text-black hover:bg-amber-300 transition-colors">Save
                case</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputsWrapper = document.getElementById('file-inputs');
            var addBtn = document.getElementById('add-file-input');
            var previews = document.getElementById('file-previews');
            var form = document.getElementById('bh-upload-case-form');
            var progressWrap = document.getElementById('bh-upload-progress');
            var progressBar = document.getElementById('bh-upload-progress-bar');
            var progressText = document.getElementById('bh-upload-progress-text');

            if (!inputsWrapper || !addBtn || !previews) return;

            function renderPreviews() {
                previews.innerHTML = '';

                var inputs = inputsWrapper.querySelectorAll('[data-report-file-input]');

                var allFiles = [];

                inputs.forEach(function(input) {
                    if (!input.files || !input.files.length) {
                        return;
                    }

                    Array.from(input.files).forEach(function(file) {
                        allFiles.push(file);
                        var wrapper = document.createElement('div');
                        wrapper.className = 'rounded-xl border border-slate-700/70 bg-slate-900/80 px-3 py-2 flex items-center gap-3';

                        var info = document.createElement('div');
                        info.className = 'flex-1 min-w-0';
                        info.innerHTML = '<p class="text-xs font-semibold text-slate-100 truncate">' + file.name + '</p>' +
                            '<p class="text-[11px] text-slate-400">' + Math.round(file.size / 1024) + ' KB</p>';

                        wrapper.appendChild(info);

                        var progressWrap = document.createElement('div');
                        progressWrap.className = 'mt-2 w-full';

                        var progressOuter = document.createElement('div');
                        progressOuter.className = 'h-2 w-full rounded-full bg-slate-800 overflow-hidden border border-slate-700/70';

                        var progressInner = document.createElement('div');
                        progressInner.className = 'h-full w-0 bg-yellow-400 transition-[width] duration-150';
                        progressInner.setAttribute('data-file-progress', '1');
                        progressInner.setAttribute('data-file-size', String(file.size || 0));

                        progressOuter.appendChild(progressInner);
                        progressWrap.appendChild(progressOuter);
                        wrapper.appendChild(progressWrap);

                        if (file.type && file.type.startsWith('image/')) {
                            var thumb = document.createElement('img');
                            thumb.className = 'w-12 h-12 rounded-lg object-cover border border-slate-700/70';

                            var reader = new FileReader();
                            reader.onload = function(e) {
                                thumb.src = e.target.result;
                            };
                            reader.readAsDataURL(file);

                            wrapper.appendChild(thumb);
                        } else if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                            var badge = document.createElement('span');
                            badge.className = 'inline-flex items-center rounded-full bg-slate-800 px-2 py-0.5 text-[10px] font-semibold text-amber-300';
                            badge.textContent = 'PDF';
                            wrapper.appendChild(badge);
                        }

                        previews.appendChild(wrapper);
                    });
                });

                var totalBytes = allFiles.reduce(function(sum, f) {
                    return sum + (f.size || 0);
                }, 0);

                previews.setAttribute('data-total-bytes', String(totalBytes));
                previews.querySelectorAll('[data-file-progress]').forEach(function(bar) {
                    bar.style.width = '0%';
                });
            }

            inputsWrapper.addEventListener('change', function(e) {
                if (e.target && e.target.matches('[data-report-file-input]')) {
                    renderPreviews();
                }
            });

            addBtn.addEventListener('click', function() {
                var row = document.createElement('div');
                row.className = 'flex items-center gap-2';

                var input = document.createElement('input');
                input.type = 'file';
                input.name = 'files[]';
                input.setAttribute('data-report-file-input', '1');
                input.className = 'w-full text-xs text-slate-200 file:mr-3 file:rounded-md file:border-0 file:bg-yellow-400 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-black hover:file:bg-yellow-300';

                row.appendChild(input);
                inputsWrapper.appendChild(row);
            });

            if (form && progressWrap && progressBar && progressText) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('bg-yellow-400', 'text-black', 'opacity-90', 'cursor-not-allowed');
                    }

                    progressWrap.classList.remove('hidden');
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';

                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.getAttribute('action'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json');

                    xhr.upload.addEventListener('progress', function(evt) {
                        if (!evt.lengthComputable) return;
                        var percent = Math.round((evt.loaded / evt.total) * 100);
                        progressBar.style.width = percent + '%';
                        progressText.textContent = percent + '%';

                        var totalBytes = parseInt(previews.getAttribute('data-total-bytes') || '0', 10);
                        var loadedBytes = evt.loaded;
                        if (totalBytes > 0) {
                            loadedBytes = Math.min(loadedBytes, totalBytes);
                        }

                        var remaining = loadedBytes;
                        var bars = previews.querySelectorAll('[data-file-progress]');
                        bars.forEach(function(bar) {
                            var size = parseInt(bar.getAttribute('data-file-size') || '0', 10);
                            var consumed = Math.max(0, Math.min(remaining, size));
                            remaining = Math.max(0, remaining - consumed);
                            var p = size > 0 ? Math.round((consumed / size) * 100) : 0;
                            bar.style.width = p + '%';
                        });
                    });

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState !== 4) return;

                        if (xhr.status >= 200 && xhr.status < 300) {
                            window.location.href = "{{ route('user.reports.index') }}";
                            return;
                        }

                        // fallback: reload to show server-side validation errors
                        progressWrap.classList.add('hidden');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                        }
                        form.submit();
                    };

                    xhr.send(formData);
                });
            }
        });
    </script>
@endpush
