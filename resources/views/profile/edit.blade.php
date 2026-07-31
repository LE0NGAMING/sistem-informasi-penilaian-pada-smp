<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - SIP SMP 110 Jakarta</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased">

    <div class="flex min-h-screen overflow-hidden">

        <!-- SIDEBAR -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <!-- HEADER -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data informasi akun dan kata sandi Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- CARD 1: EDIT INFORMASI PROFIL -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Informasi Pengguna</h2>
                            <p class="text-xs text-slate-500">Perbarui nama lengkap dan alamat email Anda.</p>
                        </div>
                    </div>

                    @if(session('success_profile'))
                    <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2.5">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-base"></i>
                        <span>{{ session('success_profile') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('name')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('email')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Role / Hak Akses</label>
                            <input type="text" value="{{ ucwords(str_replace('_', ' ', $user->role)) }}" disabled class="w-full px-3.5 py-2 border border-slate-200 bg-slate-50 rounded-lg text-slate-500 text-sm font-semibold cursor-not-allowed">
                            <span class="text-[11px] text-slate-400 mt-1 block">*Role akun hanya dapat diubah oleh Super Admin.</span>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-xs cursor-pointer">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CARD 2: UBAH PASSWORD -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Ubah Kata Sandi</h2>
                            <p class="text-xs text-slate-500">Pastikan akun Anda menggunakan password yang kuat.</p>
                        </div>
                    </div>

                    @if(session('success_password'))
                    <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2.5">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-base"></i>
                        <span>{{ session('success_password') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('current_password')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Password Baru</label>
                            <input type="password" name="password" required class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('password')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-semibold bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition shadow-xs cursor-pointer">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </main>

    </div>

</body>

</html>