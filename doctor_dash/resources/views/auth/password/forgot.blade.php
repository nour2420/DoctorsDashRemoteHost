<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot password | BoneHard</title>
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
            <h1 class="text-2xl font-semibold mb-2">Forgot your password?</h1>
            <p class="text-sm text-slate-300 mb-6">Enter your email address and we will send you a 6-digit code valid for 2 minutes.</p>

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

            <form action="{{ route('password.otp.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm mb-1">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-600 bg-slate-900/60 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 placeholder:text-slate-500">
                </div>
                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center rounded-lg bg-amber-400 px-4 py-2.5 text-sm font-semibold text-black shadow-lg shadow-amber-500/40 hover:bg-amber-300 transition-colors">Send code</button>
            </form>

            <p class="mt-6 text-xs text-center text-slate-300">
                Remembered your password?
                <a href="{{ route('login') }}" class="text-amber-300 hover:text-amber-200 font-medium">Back to login</a>
            </p>
        </div>
    </div>
</body>

</html>
