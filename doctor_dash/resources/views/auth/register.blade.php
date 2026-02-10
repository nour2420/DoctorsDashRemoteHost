<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | BoneHard</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "IBM Plex Sans", "IBM Plex Sans Arabic", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-black text-white flex items-center justify-center px-4" style="background: radial-gradient(1200px 600px at 18% 0%, rgba(255,255,255,0.06), transparent 60%), radial-gradient(900px 500px at 85% 20%, rgba(255,255,255,0.04), transparent 65%), #060606;">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
        </div>
        <div class="border border-white/15 rounded-2xl shadow-xl p-6 sm:p-8 backdrop-blur" style="background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)); box-shadow: 0 18px 55px rgba(0,0,0,0.55), inset 0 1px 0 rgba(255,255,255,0.06);">
            <h1 class="text-2xl font-semibold mb-2">Create an account</h1>
            <p class="text-sm text-slate-300 mb-6">Join BoneHard doctors dashboard to manage your cases and requests.</p>
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm mb-1">Full name</label>
                    <input id="name" name="name" type="text" required autocomplete="name" value="{{ old('name') }}" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40">
                </div>
                <div>
                    <label for="email" class="block text-sm mb-1">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-sm mb-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm mb-1">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="address" class="block text-sm mb-1">Address (optional)</label>
                        <input id="address" name="address" type="text" value="{{ old('address') }}" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40" placeholder="Dubai, UAE">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm mb-1">Phone number</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" pattern="^\+?[0-9]{9,15}$" class="w-full rounded-lg border border-white/15 bg-black/40 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 placeholder:text-white/40" placeholder="+971501234567">
                    </div>
                </div>
                <div class="flex items-start gap-2 text-xs text-slate-300">
                    <input id="terms" type="checkbox" required class="mt-1 rounded border-white/20 bg-black text-white focus:ring-white/30" required>
                    <label for="terms">I agree to the processing of my data according to BoneHard's privacy policy.</label>
                </div>
                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-black shadow-lg hover:bg-white/90 transition-colors">Create account</button>
            </form>
            <p class="mt-6 text-xs text-center text-slate-300">
                Already have an account?
                <a href="{{ route('login') }}" class="text-white hover:text-white/90 font-medium">Sign in</a>
            </p>
        </div>

        <div class="mb-8 text-center">
        </div>
    </div>
</body>

</html>