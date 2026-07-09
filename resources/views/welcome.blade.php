<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMIS — Sistem Monitoring Tugas Mahasiswa</title>
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

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .glow {
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.4);
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.25;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: #818cf8; top: -100px; right: -80px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: #c084fc; bottom: 50px; left: -60px; animation-delay: 3s; }
        .orb-3 { width: 200px; height: 200px; background: #38bdf8; top: 50%; right: 20%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.6s ease forwards; }
        .fade-in-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-2 { animation-delay: 0.25s; opacity: 0; }
        .fade-in-3 { animation-delay: 0.4s; opacity: 0; }
        .fade-in-4 { animation-delay: 0.55s; opacity: 0; }

        .stat-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }

        /* Progress bar animation */
        .progress-bar {
            animation: growWidth 1.5s ease forwards;
            animation-delay: 1s;
            width: 0%;
        }
        @keyframes growWidth {
            to { width: var(--target-width); }
        }

        @media (prefers-reduced-motion: reduce) {
            .orb, .fade-in, .progress-bar { animation: none; opacity: 1; }
            .progress-bar { width: var(--target-width); }
        }
    </style>
</head>
<body class="bg-gray-950 text-white overflow-x-hidden">

{{-- ─── NAVBAR ──────────────────────────────────────────────────────────────── --}}
<nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
    <div class="max-w-6xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-white text-sm"></i>
            </div>
            <span class="text-lg font-bold text-white">SAMIS</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="text-sm text-indigo-300 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/10 transition">
                Masuk
            </a>
            <a href="{{ route('register') }}"
               class="text-sm bg-indigo-500 hover:bg-indigo-400 text-white font-medium px-4 py-2 rounded-lg transition">
                Daftar
            </a>
        </div>
    </div>
</nav>

{{-- ─── HERO ────────────────────────────────────────────────────────────────── --}}
<section class="gradient-bg relative min-h-screen flex items-center overflow-hidden pt-20">

    {{-- Orbs --}}
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="max-w-6xl mx-auto px-6 py-24 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Copy --}}
            <div>
                <div class="fade-in fade-in-1 inline-flex items-center gap-2 glass px-3 py-1.5 rounded-full text-xs text-indigo-200 mb-6">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    Platform Manajemen Tugas Akademik
                </div>

                <h1 class="fade-in fade-in-2 text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                    Satu tempat <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-300">
                        semua tugas
                    </span><br>
                    terpantau.
                </h1>

                <p class="fade-in fade-in-3 text-lg text-indigo-200 leading-relaxed mb-8 max-w-lg">
                    SAMIS menggantikan WhatsApp grup, catatan manual, dan pengingat yang mudah terlewat — dengan satu dashboard yang jelas untuk mahasiswa, dosen, dan admin.
                </p>

                <div class="fade-in fade-in-4 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}"
                       class="glow bg-white text-indigo-700 hover:bg-indigo-50 font-bold px-8 py-4 rounded-xl text-sm transition text-center">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                       class="glass hover:bg-white/15 text-white font-semibold px-8 py-4 rounded-xl text-sm transition text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sudah Punya Akun
                    </a>
                </div>

                <p class="fade-in fade-in-4 mt-4 text-xs text-indigo-400">
                    Gratis untuk digunakan. Tidak perlu kartu kredit.
                </p>
            </div>

            {{-- Right: Mock Dashboard --}}
            <div class="fade-in fade-in-3 hidden lg:block">
                <div class="glass rounded-2xl p-5 max-w-md ml-auto">
                    {{-- Mock topbar --}}
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-indigo-300">Dashboard Mahasiswa</p>
                            <p class="text-sm font-semibold text-white">Selamat pagi, Ahmad 👋</p>
                        </div>
                        <div class="relative">
                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-sm font-bold">A</div>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-indigo-900 flex items-center justify-center">
                                <span class="text-white" style="font-size:7px">3</span>
                            </span>
                        </div>
                    </div>

                    {{-- Mock stats --}}
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-white">8</p>
                            <p class="text-xs text-indigo-300">Total</p>
                        </div>
                        <div class="bg-green-500/20 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-green-400">5</p>
                            <p class="text-xs text-green-300">Selesai</p>
                        </div>
                        <div class="bg-red-500/20 rounded-xl p-3 text-center">
                            <p class="text-xl font-bold text-red-400">1</p>
                            <p class="text-xs text-red-300">Terlambat</p>
                        </div>
                    </div>

                    {{-- Mock deadline alert --}}
                    <div class="bg-amber-500/20 border border-amber-400/30 rounded-xl p-3 mb-4 flex items-center gap-3">
                        <i class="fas fa-bell text-amber-400 text-sm"></i>
                        <div>
                            <p class="text-xs text-amber-200 font-medium">Deadline besok!</p>
                            <p class="text-xs text-amber-300">Implementasi CRUD Produk</p>
                        </div>
                    </div>

                    {{-- Mock tugas list --}}
                    <div class="space-y-2">
                        <p class="text-xs text-indigo-400 font-medium uppercase tracking-wide mb-2">Tugas Aktif</p>

                        <div class="bg-white/8 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-medium text-white truncate mr-2">Form Login Laravel</span>
                                <span class="text-xs bg-amber-500/30 text-amber-300 px-2 py-0.5 rounded-full whitespace-nowrap">Sedang</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-white/10 rounded-full h-1.5">
                                    <div class="progress-bar h-1.5 bg-indigo-400 rounded-full" style="--target-width: 65%"></div>
                                </div>
                                <span class="text-xs text-indigo-300">65%</span>
                            </div>
                        </div>

                        <div class="bg-white/8 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-medium text-white truncate mr-2">Normalisasi Database</span>
                                <span class="text-xs bg-gray-500/30 text-gray-300 px-2 py-0.5 rounded-full whitespace-nowrap">Belum</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-white/10 rounded-full h-1.5">
                                    <div class="progress-bar h-1.5 bg-indigo-400 rounded-full" style="--target-width: 0%"></div>
                                </div>
                                <span class="text-xs text-indigo-300">0%</span>
                            </div>
                        </div>

                        <div class="bg-white/8 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-medium text-white truncate mr-2">Belajar Tailwind CSS</span>
                                <span class="text-xs bg-green-500/30 text-green-300 px-2 py-0.5 rounded-full whitespace-nowrap">Selesai</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-white/10 rounded-full h-1.5">
                                    <div class="progress-bar h-1.5 bg-green-400 rounded-full" style="--target-width: 100%"></div>
                                </div>
                                <span class="text-xs text-green-300">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ─── STATS BAR ───────────────────────────────────────────────────────────── --}}
<section class="bg-indigo-950 border-y border-indigo-900 py-12">
    <div class="max-w-5xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <p class="text-3xl font-black text-white mb-1">100%</p>
            <p class="text-sm text-indigo-400">Berbasis Web</p>
        </div>
        <div class="stat-card">
            <p class="text-3xl font-black text-white mb-1">3 Role</p>
            <p class="text-sm text-indigo-400">Admin · Dosen · Mahasiswa</p>
        </div>
        <div class="stat-card">
            <p class="text-3xl font-black text-white mb-1">Real-time</p>
            <p class="text-sm text-indigo-400">Notifikasi Deadline</p>
        </div>
        <div class="stat-card">
            <p class="text-3xl font-black text-white mb-1">PDF</p>
            <p class="text-sm text-indigo-400">Export Laporan</p>
        </div>
    </div>
</section>

{{-- ─── FITUR ───────────────────────────────────────────────────────────────── --}}
<section class="bg-gray-950 py-24">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-16">
            <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Fitur Sistem</p>
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                Dirancang untuk semua pihak
            </h2>
            <p class="text-gray-400 max-w-xl mx-auto">
                Setiap role punya tampilan dan fitur yang sesuai kebutuhan — tidak lebih, tidak kurang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Mahasiswa --}}
            <div class="card-hover rounded-2xl p-6 border border-violet-900/60"
                 style="background: linear-gradient(135deg, #1a1040 0%, #2d1b69 100%)">
                <div class="w-12 h-12 bg-violet-500/20 rounded-xl flex items-center justify-center mb-5">
                    <i class="fas fa-user-graduate text-violet-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Mahasiswa</h3>
                <p class="text-sm text-gray-400 mb-5 leading-relaxed">
                    Pantau semua tugas dari berbagai kelas dalam satu halaman. Tidak ada lagi tugas yang terlewat.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-violet-200">
                        <i class="fas fa-check text-violet-400 text-xs"></i> Dashboard & kalender deadline
                    </li>
                    <li class="flex items-center gap-2 text-sm text-violet-200">
                        <i class="fas fa-check text-violet-400 text-xs"></i> Upload pengumpulan tugas
                    </li>
                    <li class="flex items-center gap-2 text-sm text-violet-200">
                        <i class="fas fa-check text-violet-400 text-xs"></i> Tugas pribadi mandiri
                    </li>
                    <li class="flex items-center gap-2 text-sm text-violet-200">
                        <i class="fas fa-check text-violet-400 text-xs"></i> Lihat nilai & feedback
                    </li>
                    <li class="flex items-center gap-2 text-sm text-violet-200">
                        <i class="fas fa-check text-violet-400 text-xs"></i> Reminder otomatis H-7 hingga H-0
                    </li>
                </ul>
            </div>

            {{-- Dosen --}}
            <div class="card-hover rounded-2xl p-6 border border-teal-900/60"
                 style="background: linear-gradient(135deg, #0d2b2b 0%, #0f4040 100%)">
                <div class="w-12 h-12 bg-teal-500/20 rounded-xl flex items-center justify-center mb-5">
                    <i class="fas fa-chalkboard-teacher text-teal-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Dosen</h3>
                <p class="text-sm text-gray-400 mb-5 leading-relaxed">
                    Buat tugas, pantau progres mahasiswa, dan berikan penilaian — semua dari satu tempat.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-teal-200">
                        <i class="fas fa-check text-teal-400 text-xs"></i> Buat & kelola tugas kelas
                    </li>
                    <li class="flex items-center gap-2 text-sm text-teal-200">
                        <i class="fas fa-check text-teal-400 text-xs"></i> Monitor progres per mahasiswa
                    </li>
                    <li class="flex items-center gap-2 text-sm text-teal-200">
                        <i class="fas fa-check text-teal-400 text-xs"></i> Lihat file pengumpulan
                    </li>
                    <li class="flex items-center gap-2 text-sm text-teal-200">
                        <i class="fas fa-check text-teal-400 text-xs"></i> Berikan nilai 0–100 + feedback
                    </li>
                    <li class="flex items-center gap-2 text-sm text-teal-200">
                        <i class="fas fa-check text-teal-400 text-xs"></i> Statistik penyelesaian kelas
                    </li>
                </ul>
            </div>

            {{-- Admin --}}
            <div class="card-hover rounded-2xl p-6 border border-indigo-900/60"
                 style="background: linear-gradient(135deg, #0f1035 0%, #1e2060 100%)">
                <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center mb-5">
                    <i class="fas fa-shield-alt text-indigo-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Admin</h3>
                <p class="text-sm text-gray-400 mb-5 leading-relaxed">
                    Kelola seluruh pengguna dan kelas, pantau aktivitas sistem, dan ekspor laporan.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-indigo-200">
                        <i class="fas fa-check text-indigo-400 text-xs"></i> CRUD dosen & mahasiswa
                    </li>
                    <li class="flex items-center gap-2 text-sm text-indigo-200">
                        <i class="fas fa-check text-indigo-400 text-xs"></i> Kelola kelas & enrollment
                    </li>
                    <li class="flex items-center gap-2 text-sm text-indigo-200">
                        <i class="fas fa-check text-indigo-400 text-xs"></i> Monitoring sistem real-time
                    </li>
                    <li class="flex items-center gap-2 text-sm text-indigo-200">
                        <i class="fas fa-check text-indigo-400 text-xs"></i> Log aktivitas pengguna
                    </li>
                    <li class="flex items-center gap-2 text-sm text-indigo-200">
                        <i class="fas fa-check text-indigo-400 text-xs"></i> Export laporan PDF
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- ─── HOW IT WORKS ────────────────────────────────────────────────────────── --}}
<section class="bg-gray-900 py-24">
    <div class="max-w-4xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">Cara Kerja</p>
            <h2 class="text-3xl sm:text-4xl font-black text-white">
                Mulai dalam 3 langkah
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-black text-white">
                    1
                </div>
                <h3 class="font-bold text-white mb-2">Daftar Akun</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Admin membuat akun untuk dosen dan mahasiswa, lalu menyusun kelas.
                </p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-black text-white">
                    2
                </div>
                <h3 class="font-bold text-white mb-2">Buat & Bagikan Tugas</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Dosen membuat tugas dengan deadline dan prioritas. Mahasiswa langsung mendapat notifikasi.
                </p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 bg-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl font-black text-white">
                    3
                </div>
                <h3 class="font-bold text-white mb-2">Kumpul & Nilai</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Mahasiswa upload tugas, dosen memberi nilai dan feedback. Semua tercatat otomatis.
                </p>
            </div>
        </div>

    </div>
</section>

{{-- ─── CTA ─────────────────────────────────────────────────────────────────── --}}
<section class="gradient-bg relative overflow-hidden py-24">
    <div class="orb orb-1" style="opacity:0.15;"></div>
    <div class="orb orb-2" style="opacity:0.12;"></div>

    <div class="max-w-3xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
            Siap mulai mengelola tugas<br>dengan lebih rapi?
        </h2>
        <p class="text-indigo-200 mb-8 text-lg">
            Bergabung sekarang dan rasakan perbedaannya.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="glow bg-white text-indigo-700 hover:bg-indigo-50 font-bold px-10 py-4 rounded-xl text-sm transition">
                <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
            </a>
            <a href="{{ route('login') }}"
               class="glass hover:bg-white/15 text-white font-semibold px-10 py-4 rounded-xl text-sm transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </a>
        </div>
    </div>
</section>

{{-- ─── FOOTER ──────────────────────────────────────────────────────────────── --}}
<footer class="bg-gray-950 border-t border-gray-800 py-8">
    <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-indigo-500 rounded-md flex items-center justify-center">
                <i class="fas fa-graduation-cap text-white text-xs"></i>
            </div>
            <span class="text-sm font-semibold text-white">SAMIS</span>
            <span class="text-xs text-gray-600">Sistem Monitoring Tugas Mahasiswa</span>
        </div>
        <p class="text-xs text-gray-600">
            &copy; {{ date('Y') }} SAMIS. Dibangun dengan Laravel 12 & Tailwind CSS.
        </p>
    </div>
</footer>

</body>
</html>