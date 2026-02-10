<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enter code | BoneHard</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "IBM Plex Sans", "IBM Plex Sans Arabic", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-black bg-gradient-to-b from-black via-slate-900 to-black text-white flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center"></div>
        <div class="bg-slate-900/70 border border-slate-700/80 rounded-2xl shadow-xl shadow-amber-500/10 p-6 sm:p-8 backdrop-blur">
            <h1 class="text-2xl font-semibold mb-2">Check your email</h1>
            <p class="text-sm text-slate-300 mb-2">We have sent a 6-digit code to <span class="font-semibold">{{ $email }}</span>.</p>
            <p class="text-xs text-slate-400 mb-4">Enter the code below. The code is valid for 2 minutes.</p>

            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-xs text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif


            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.otp.verify') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <div>
                    <label for="code" class="block text-sm mb-1">6-digit code</label>
                    <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required class="w-full tracking-[0.35em] text-center rounded-lg border border-slate-600 bg-slate-900/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 placeholder:text-slate-500">
                </div>
                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center rounded-lg bg-amber-400 px-4 py-2.5 text-sm font-semibold text-black shadow-lg shadow-amber-500/40 hover:bg-amber-300 transition-colors">Verify code</button>
            </form>

            <div class="mt-4 flex items-center justify-between text-xs text-slate-300">
                <span id="bh-otp-timer" data-remaining="{{ $remainingSeconds }}"></span>
                <button type="submit" form="bh-otp-resend-form" id="bh-otp-resend-btn" class="text-amber-300 hover:text-amber-200 disabled:opacity-50 disabled:cursor-not-allowed">Resend code</button>
            </div>

            <form method="POST" action="{{ route('password.otp.resend') }}" id="bh-otp-resend-form" class="hidden">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
            </form>
        </div>
    </div>

    <script>
        (function () {
            var timerEl = document.getElementById('bh-otp-timer');
            var resendBtn = document.getElementById('bh-otp-resend-btn');
            var remaining = parseInt(timerEl?.getAttribute('data-remaining') || '0', 10);

            function formatSeconds(sec) {
                var m = Math.floor(sec / 60);
                var s = sec % 60;
                return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
            }

            function tick() {
                if (!timerEl) return;

                if (remaining > 0) {
                    timerEl.textContent = 'Code expires in ' + formatSeconds(remaining);
                    if (resendBtn) {
                        resendBtn.disabled = true;
                    }
                    remaining -= 1;
                    setTimeout(tick, 1000);
                } else {
                    timerEl.textContent = 'You can request a new code.';
                    if (resendBtn) {
                        resendBtn.disabled = false;
                    }
                }
            }

            if (timerEl) {
                tick();
            }
        })();
    </script>
</body>

</html>
