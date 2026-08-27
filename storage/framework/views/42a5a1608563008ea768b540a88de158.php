<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login OJT Form</title>
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
                            50: '#fef3c7',
                            500: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e3a8a',
                            900: '#172554',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8 bg-[radial-gradient(circle_at_top_left,_rgba(217,119,6,0.15),_transparent_28%),linear-gradient(135deg,_#0f172a_0%,_#1e293b_50%,_#0f172a_100%)]">
        <section class="w-full max-w-6xl overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-[0_24px_60px_rgba(0,0,0,0.45)] backdrop-blur">
            <div class="grid min-h-[600px] lg:min-h-[680px] lg:grid-cols-2">
                
                <!-- BAGIAN KIRI: Form Login -->
                <div class="flex items-center bg-white px-6 sm:px-8 py-8 sm:py-10 lg:px-16">
                    <div class="w-full max-w-md">
                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 lg:text-5xl">Selamat Datang</h1>
                        <p class="mt-3 text-base sm:text-lg text-slate-600">Masuk menggunakan SID dan password Anda</p>

                        <?php if($errors->any()): ?>
                            <div class="mt-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-700">
                                <p class="font-semibold">Login gagal.</p>
                                <ul class="mt-2 space-y-1 text-xs">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if(session('status')): ?>
                            <div class="mt-6 rounded-xl border border-blue-500/30 bg-blue-500/10 p-4 text-sm text-blue-700">
                                <?php echo e(session('status')); ?>

                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('login')); ?>" class="mt-8 space-y-6">
                            <?php echo csrf_field(); ?>

                            <div>
                                <label for="sid" class="mb-2 block text-sm font-semibold text-slate-700">SID</label>
                                <input id="sid" name="sid" type="text" value="<?php echo e(old('sid')); ?>" required autofocus autocomplete="username" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20" placeholder="Masukkan SID (contoh: XXXXX)">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 pr-12 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20" placeholder="Password">
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600" aria-label="Tampilkan password">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <label class="flex items-center gap-3 text-sm text-slate-600">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                Ingat saya di perangkat ini
                            </label>

                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-500 px-4 py-3 text-xl font-bold text-slate-900 transition hover:bg-amber-600">
                                Login
                            </button>
                        </form>
                    </div>
                </div>

                <!-- BAGIAN KANAN: Gambar Ilustrasi -->
                <div class="relative hidden lg:block bg-[#1e3a8a]">
                    <img
                        src="<?php echo e(asset('images/tambang-update.jpg')); ?>"
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

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/auth/login.blade.php ENDPATH**/ ?>