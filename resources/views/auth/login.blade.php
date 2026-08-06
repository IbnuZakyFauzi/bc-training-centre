<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login OJT Logbook</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#E6F3EF',
                            500: '#00A859',
                            700: '#00593E',
                            800: '#003829',
                            900: '#024222',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8 bg-[radial-gradient(circle_at_top_left,_rgba(0,168,89,0.18),_transparent_28%),linear-gradient(135deg,_#061212_0%,_#0A1D1D_50%,_#081214_100%)]">
        <section class="w-full max-w-6xl overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-[0_24px_60px_rgba(0,0,0,0.45)] backdrop-blur">
            <div class="grid min-h-[680px] lg:grid-cols-2">
                
                <!-- BAGIAN KIRI: Diubah dari bg-slate-900 menjadi bg-white dan teks jadi gelap -->
                <div class="flex items-center bg-white px-8 py-10 sm:px-12 lg:px-16">
                    <div class="w-full max-w-md">
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">Selamat Datang</h1>
                        <p class="mt-3 text-lg text-slate-600">Masuk menggunakan email dan password Anda</p>

                        @if($errors->any())
                            <div class="mt-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-700">
                                <p class="font-semibold">Login gagal.</p>
                                <ul class="mt-2 space-y-1 text-xs">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                            @csrf

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <!-- Input disesuaikan dengan background terang (bg-slate-50, border abu-abu, teks gelap) -->
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-[#8FC31F] focus:ring-2 focus:ring-[#8FC31F]/20" placeholder="nama@beraucoal.co.id">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-[#8FC31F] focus:ring-2 focus:ring-[#8FC31F]/20" placeholder="Password">
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600" aria-label="Tampilkan password">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <label class="flex items-center gap-3 text-sm text-slate-600">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#8FC31F] focus:ring-[#8FC31F]">
                                Ingat saya di perangkat ini
                            </label>

                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#8FC31F] px-4 py-3 text-xl font-bold text-white transition hover:bg-[#7fb11c]">
                                Login
                            </button>
                        </form>
                    </div>
                </div>

                <!-- BAGIAN KANAN: Gambar Ilustrasi -->
                <div class="relative hidden lg:block bg-[#2D401F]">
                    <img
                        src="{{ asset('images/tambang-update.jpg') }}"
                        alt="Operator alat berat di area pertambangan"
                        class="h-full w-full object-cover object-left"
                    >

    <script>
        const togglePasswordButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePasswordButton && passwordInput) {
            togglePasswordButton.addEventListener('click', () => {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>