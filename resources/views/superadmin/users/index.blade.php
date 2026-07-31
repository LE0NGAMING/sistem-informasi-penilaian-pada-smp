<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User & Admin - SIP SMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between hidden md:flex shrink-0">
            <div>
                <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
                    <div class="bg-blue-600 text-white p-2 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-white tracking-wide">SIP SMP 110</span>
                </div>

                <nav class="px-4 py-6 space-y-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Menu Utama</p>
                        <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition">
                            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Akses & Pengguna</p>
                        <div class="space-y-1">
                            <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white font-medium shadow-sm transition">
                                <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Kelola User & Admin
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- TOPBAR HEADER -->
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-lg font-bold text-slate-800">Kelola User & Admin</h1>

                <div class="relative text-left">
                    <button type="button" id="btnUserDropdown" class="flex items-center gap-2.5 bg-slate-50 hover:bg-slate-100 px-4 py-1.5 rounded-full border border-slate-200 transition cursor-pointer">
                        <div class="text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Super Administrator' }}</p>
                            <p class="text-[10px] text-blue-600 font-medium leading-tight">{{ ucwords(str_replace('_', ' ', Auth::user()->role ?? 'System Admin')) }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="text-[11px] font-semibold text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@smpn1.sch.id' }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Pengaturan Profil</span>
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- MAIN BODY -->
            <main class="p-8 space-y-6">

                <!-- FLASH MESSAGE -->
                @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
                </div>
                @endif
                @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
                </div>
                @endif

                <!-- BARIS JUDUL + TOMBOL TAMBAH USER (Tepat di Bawah Dropdown Super Admin) -->
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Daftar Pengguna</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola akun admin, guru, siswa, dan orang tua sekolah</p>
                    </div>

                    <!-- Tombol Tambah User Ukuran Pas di Kanan -->
                    <div>
                        <button onclick="toggleModal('modalCreateUser')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl transition cursor-pointer" style="padding: 10px 18px; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
                            <svg style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah User Baru</span>
                        </button>
                    </div>
                </div>

                <!-- BOX FILTER (Search + Role + Tombol Cari 1 Baris Horizontal) -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <form method="GET" action="{{ route('superadmin.users.index') }}" style="display: flex; align-items: center; gap: 12px; width: 100%;">

                        <!-- 1. Input Pencarian Nama / Email (Kunci: min-width: 0) -->
                        <div style="position: relative; flex: 1; min-width: 0;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; display: flex; align-items: center; pointer-events: none;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-500 transition" style="width: 100%; padding-left: 38px; padding-right: 12px; padding-top: 9px; padding-bottom: 9px; box-sizing: border-box;">
                        </div>

                        <!-- 2. Dropdown Filter Role -->
                        <div style="width: 200px; flex-shrink: 0;">
                            <select name="role" class="bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-500 transition cursor-pointer" style="width: 100%; padding: 9px 12px; box-sizing: border-box;">
                                <option value="">Semua Role / Akses</option>
                                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin_sekolah" {{ request('role') == 'admin_sekolah' ? 'selected' : '' }}>Admin Sekolah</option>
                                <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="orang_tua" {{ request('role') == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                            </select>
                        </div>

                        <!-- 3. Tombol Cari (Dikunci Agar Pasti Muncul Sejajar) -->
                        <button type="submit" class="hover:bg-slate-800 transition cursor-pointer" style="background-color: #0f172a; color: #ffffff; font-weight: 600; font-size: 12px; border-radius: 8px; padding: 0 22px; height: 36px; white-space: nowrap; flex-shrink: 0; border: none; display: inline-flex; align-items: center; justify-content: center;">
                            Cari
                        </button>

                        @if(request('search') || request('role'))
                        <a href="{{ route('superadmin.users.index') }}" class="text-xs text-rose-600 hover:text-rose-700 font-semibold hover:underline" style="white-space: nowrap; flex-shrink: 0; padding: 0 4px;">
                            Reset
                        </a>
                        @endif
                    </form>
                </div>

                <!-- TABEL USER -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-4">Pengguna</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Tanggal Dibuat</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            @forelse ($users as $user)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                </td>
                                <td class="p-4">
                                    @if ($user->role === 'super_admin')
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-semibold">Super Admin</span>
                                    @elseif ($user->role === 'admin_sekolah')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-semibold">Admin Sekolah</span>
                                    @elseif ($user->role === 'kepala_sekolah')
                                    <span class="px-2.5 py-1 bg-teal-100 text-teal-700 rounded-md text-xs font-semibold">Kepala Sekolah</span>
                                    @elseif ($user->role === 'guru')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-semibold">Guru</span>
                                    @elseif ($user->role === 'orang_tua')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md text-xs font-semibold">Orang Tua</span>
                                    @else
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md text-xs font-semibold">Siswa</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-slate-500">
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="openEditModal({{ json_encode($user) }})" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">Edit</button>

                                    @if ($user->id !== auth()->id())
                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">Hapus</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 text-sm">
                                    Tidak ada data pengguna yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- PAGINATION -->
                    @if ($users->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>

            </main>
        </div>

    </div>

    <!-- MODAL TAMBAH USER -->
    <div id="modalCreateUser" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-slate-800">Tambah User Baru</h3>
                <button onclick="toggleModal('modalCreateUser')" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                    <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                        <option value="admin_sekolah">Admin Sekolah</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="orang_tua">Orang Tua</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-end gap-2 border-t pt-3 mt-4">
                    <button type="button" onclick="toggleModal('modalCreateUser')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div id="modalEditUser" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-slate-800">Edit Data User</h3>
                <button onclick="toggleModal('modalEditUser')" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="formEditUser" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                    <select id="edit_role" name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                        <option value="admin_sekolah">Admin Sekolah</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="orang_tua">Orang Tua</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-end gap-2 border-t pt-3 mt-4">
                    <button type="button" onclick="toggleModal('modalEditUser')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown Topbar
            const btn = document.getElementById('btnUserDropdown');
            const menu = document.getElementById('userDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });

        // Toggle Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.toggle('hidden');
        }

        // Open Edit Modal & Populate Data
        function openEditModal(user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role;

            const form = document.getElementById('formEditUser');
            form.action = `/superadmin/users/${user.id}`;

            toggleModal('modalEditUser');
        }
    </script>

</body>

</html>