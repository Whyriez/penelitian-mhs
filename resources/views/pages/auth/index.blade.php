<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Arsip Penelitian Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <style>
        body {
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            overflow-x: hidden;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 25%, #ffffff 100%);
        }
        .fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in-left { animation: slideInLeft 0.8s ease-out; }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-right { animation: slideInRight 0.8s ease-out; }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .input-group { position: relative; }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 10;
        }
        .input-field { padding-left: 40px; transition: all 0.3s ease; }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
        .btn-primary:active { transform: translateY(0); }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .illustration-container {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        @media (max-width: 768px) {
            .split-screen { grid-template-columns: 1fr; }
            .illustration-container { display: none; }
        }
        /* Perbaikan untuk layout yang lebih compact */
        .compact-layout { min-height: 100vh; max-height: 100vh; overflow: hidden; }
        .compact-form { padding-top: 1rem; padding-bottom: 1rem; }
        .compact-card { padding: 1.5rem; }
        .compact-logo { width: 3rem; height: 3rem; margin-bottom: 0.75rem; }
        .compact-title { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .compact-input { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .compact-footer { margin-top: 1rem; }
    </style>
    <style>
        @view-transition { navigation: auto; }
    </style>
</head>

<body class="h-full gradient-bg compact-layout">
    <div class="min-h-full grid grid-cols-1 lg:grid-cols-2 split-screen">
        <div class="illustration-container hidden lg:flex items-center justify-center p-8 slide-in-left">
            <div class="max-w-md text-center text-white">
                <div class="mb-6">
                    <svg class="w-48 h-48 mx-auto" viewbox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="200" cy="200" r="180" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2" />
                        <rect x="120" y="140" width="80" height="100" rx="8" fill="rgba(255,255,255,0.9)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <rect x="130" y="130" width="80" height="100" rx="8" fill="rgba(255,255,255,0.8)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <rect x="140" y="120" width="80" height="100" rx="8" fill="rgba(255,255,255,0.7)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <rect x="150" y="110" width="80" height="100" rx="8" fill="white" stroke="rgba(59,130,246,0.3)" stroke-width="2" />
                        <line x1="160" y1="130" x2="210" y2="130" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" />
                        <line x1="160" y1="140" x2="200" y2="140" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" />
                        <line x1="160" y1="150" x2="220" y2="150" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" />
                        <line x1="160" y1="160" x2="190" y2="160" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" />
                        <circle cx="200" cy="180" r="15" fill="#10b981" />
                        <path d="M193 180l4 4 8-8" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M200 260 L170 280 L170 310 Q170 320 200 325 Q230 320 230 310 L230 280 Z" fill="rgba(255,255,255,0.8)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <path d="M165 280 L235 280" stroke="rgba(255,255,255,0.5)" stroke-width="2" />
                        <circle cx="280" cy="150" r="8" fill="rgba(255,255,255,0.6)" />
                        <circle cx="320" cy="180" r="6" fill="rgba(255,255,255,0.4)" />
                        <circle cx="100" cy="160" r="10" fill="rgba(255,255,255,0.5)" />
                        <circle cx="80" cy="200" r="7" fill="rgba(255,255,255,0.3)" />
                        <path d="M200 280 L180 290 L180 320 Q180 330 200 335 Q220 330 220 320 L220 290 Z" fill="rgba(255,255,255,0.8)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <path d="M190 300 L195 305 L210 290" stroke="#10b981" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-3">Arsip Penelitian Terpusat</h2>
                <p class="text-blue-100 leading-relaxed text-sm">
                    Platform digital untuk mengelola, menyimpan, dan mengakses arsip penelitian
                    mahasiswa secara terstruktur dan aman.
                </p>
            </div>
        </div>
        
        <div class="flex items-center justify-center p-4 lg:p-8 slide-in-right compact-form">
            <div class="w-full max-w-md">
                <div class="text-center mb-6 fade-in">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl mb-3 shadow-lg compact-logo">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12m0 0h6m-6 0h6" />
                        </svg>
                    </div>
                    <h1 id="system-title" class="text-xl font-bold text-gray-900 mb-1 compact-title">
                        Sistem Arsip Penelitian Mahasiswa
                    </h1>
                    <p id="welcome-text" class="text-gray-600 text-sm">
                        Selamat datang di sistem arsip penelitian mahasiswa
                    </p>
                </div>

                <div class="login-card rounded-2xl shadow-xl border border-white/20 compact-card fade-in">
                    
                    @if (session('flash.error'))
                        <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700" role="alert">
                            {{ session('flash.error') }}
                        </div>
                    @endif
                    @if (session('flash.success'))
                         <div class="mb-4 rounded-lg bg-green-100 p-3 text-sm text-green-700" role="alert">
                            {{ session('flash.success') }}
                        </div>
                    @endif
                    
                    {{-- Tampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="login-form" action="{{ route('login.process') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <input type="text" id="email" name="email" required value="{{ old('email') }}"
                                    class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                    placeholder="Masukkan email" />
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <input type="password" id="password" name="password" required
                                    class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                    placeholder="Masukkan password" />
                                <button type="button" id="toggle-password"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center text-xs mt-3">
                            <a href="{{ route('auth.register') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Daftar Akun Baru
                            </a>
                        </div>

                        <button type="submit" id="login-btn"
                            class="btn-primary w-full py-2 px-4 text-white font-semibold rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 mt-2">
                            <span id="login-text">Masuk ke Sistem</span>
                            <svg id="login-spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white inline"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="text-center mt-4 text-xs text-gray-500 fade-in compact-footer">
                    <p>
                        © 2025 <span id="institution-name">Fakultas Ilmu Komputer - Universitas Contoh</span>
                    </p>
                    <p class="mt-1">Sistem Arsip Penelitian Mahasiswa v2.0</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setupEventListeners() {
            const togglePassword = document.getElementById("toggle-password");
            const passwordInput = document.getElementById("password");

            togglePassword.addEventListener("click", () => {
                const type =
                    passwordInput.getAttribute("type") === "password" ?
                    "text" :
                    "password";
                passwordInput.setAttribute("type", type);

                // Toggle icon
                const icon = togglePassword.querySelector("svg");
                if (type === "text") {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    `;
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            });

            const inputs = document.querySelectorAll(".input-field");
            inputs.forEach((input) => {
                input.addEventListener("focus", () => {
                    input.parentElement.classList.add("focused");
                });
                input.addEventListener("blur", () => {
                    input.parentElement.classList.remove("focused");
                });
            });

            const loginForm = document.getElementById("login-form");
            const loginBtn = document.getElementById("login-btn");
            const loginText = document.getElementById("login-text");
            const loginSpinner = document.getElementById("login-spinner");

            loginForm.addEventListener("submit", function() {
                // Tampilkan status loading saat form di-submit
                loginText.textContent = "Memproses...";
                loginSpinner.classList.remove("hidden");
                loginBtn.disabled = true;
                
                // Form akan di-submit secara normal ke server (PHP)
            });
        }

        // Jalankan event listeners saat halaman dimuat
        document.addEventListener('DOMContentLoaded', setupEventListeners);
    </script>
</body>
</html>