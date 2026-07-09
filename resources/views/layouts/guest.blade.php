<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SAMIS') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }

        .gradient-bg {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 70%, #6366f1 100%);
        }
        .glass {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.25;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 380px; height: 380px; background: #818cf8; top: -100px; right: -100px; animation-delay: 0s; }
        .orb-2 { width: 280px; height: 280px; background: #c084fc; bottom: 0; left: -60px; animation-delay: 3s; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.5s ease forwards; }
        @media (prefers-reduced-motion: reduce) {
            .orb, .fade-in { animation: none; opacity: 1; }
        }

        /* Override Breeze default input/button look agar konsisten dengan brand */
        input[type="text"], input[type="email"], input[type="password"] {
            border-radius: 0.5rem !important;
            border-color: #d1d5db !important;
            font-size: 0.875rem !important;
            padding: 0.625rem 0.75rem !important;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3) !important;
        }
        button[type="submit"] {
            background-color: #4f46e5 !important;
            border-radius: 0.5rem !important;
            padding: 0.625rem 1.25rem !important;
            font-size: 0.875rem !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            width: 100%;
            justify-content: center;
        }
        button[type="submit"]:hover {
            background-color: #4338ca !important;
        }
    </style>
</head>
<body class="bg-gray-950 antialiased">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- ─── LEFT: Form ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col justify-center px-6 sm:px-12 lg:px-20 py-12 bg-white">
        <div class="max-w-sm w-full mx-auto fade-in">

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-10">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <span class="text-lg font-bold text-gray-900">SAMIS</span>
            </a>

            {{ $slot }}

        </div>
    </div>

    {{-- ─── RIGHT: Brand Visual ─────────────────────────────────────────── --}}
    <div class="gradient-bg relative hidden lg:flex flex-col items-center justify-center overflow-hidden px-12">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <div class="relative z-10 max-w-sm text-center">
            <div class="glass rounded-2xl p-6 mb-8 text-left">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs text-indigo-300">Deadline Terdekat</p>
                    <span class="text-xs bg-amber-500/30 text-amber-200 px-2 py-0.5 rounded-full">2 hari lagi</span>
                </div>
                <p class="text-sm font-semibold text-white mb-3">Implementasi CRUD Produk</p>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-white/10 rounded-full h-1.5">
                        <div class="h-1.5 bg-indigo-400 rounded-full" style="width: 60%"></div>
                    </div>
                    <span class="text-xs text-indigo-300">60%</span>
                </div>
            </div>

            <h2 class="text-2xl font-black text-white mb-3 leading-snug">
                Semua tugas, satu tempat, tanpa drama.
            </h2>
            <p class="text-sm text-indigo-200 leading-relaxed">
                SAMIS membantu mahasiswa, dosen, dan admin memantau tugas akademik tanpa kehilangan jejak di grup WhatsApp.
            </p>
        </div>
    </div>

</div>

</body>
</html>