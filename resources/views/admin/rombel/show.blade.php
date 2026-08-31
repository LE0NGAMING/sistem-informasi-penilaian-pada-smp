@extends('layouts.app')

@section('title', 'Detail Rombel')

@section('content')
<div class="space-y-6">
    {{-- Informasi Rombel Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="bi bi-info-circle text-indigo-600"></i> Informasi Rombel
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <span class="text-xs text-slate-400 block mb-1">Nama Rombel</span>
                <span class="text-lg font-bold text-slate-800">{{ $rombel->nama_rombel }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block mb-1">Tingkat Kelas</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                    Kelas {{ $rombel->tingkat }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block mb-1">Wali Kelas</span>
                <span class="font-semibold text-slate-700">{{ $rombel->waliKelas?->nama_lengkap ?? '-' }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block mb-1">Tahun Ajaran</span>
                <span class="font-bold text-slate-800">{{ $rombel->tahunAjaran?->tahun ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Daftar Siswa Dalam Rombel --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-people text-indigo-600 text-lg"></i>
                <h3 class="font-bold text-slate-800">Daftar Siswa Dalam Rombel</h3>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-medium rounded-full border border-slate-200">
                    Total: {{ $siswaList->count() }} Siswa
                </span>
                <button type="button" onclick="openPlotModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    <i class="bi bi-person-plus"></i> Plotting Siswa
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-medium border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16">No</th>
                        <th scope="col" class="px-6 py-4 w-40">NISN</th>
                        <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-4 w-40">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $siswa)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-mono font-medium text-slate-700">{{ $siswa->nisn ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</td>
                        <td class="px-6 py-4">
                            {{ str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']) ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.rombel.unplot-siswa', [$rombel, $siswa]) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini dari rombel?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 transition" title="Keluarkan Siswa">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                    <i class="bi bi-person-x text-2xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-medium mb-1">Belum ada siswa</h3>
                                <p class="text-slate-500 text-sm">Belum ada siswa yang terdaftar di rombel ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Plotting Siswa (Tailwind pure modal dengan JS vanila) --}}
<div id="modalPlottingSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <form action="{{ route('admin.rombel.plot-siswa', $rombel) }}" method="POST" class="flex flex-col h-full">
            @csrf
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-person-plus text-indigo-600"></i> Tambah Siswa ke Rombel {{ $rombel->nama_rombel }}
                </h3>
                <button type="button" onclick="closePlotModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4 flex-grow">
                <p class="text-sm text-slate-500">Pilih siswa tanpa rombel yang akan dimasukkan ke rombel ini:</p>

                @if($siswaTersedia->isNotEmpty())
                <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" id="selectAllSiswa" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700">Pilih Semua</span>
                    </label>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-full border border-indigo-100">
                        {{ $siswaTersedia->count() }} Siswa Tersedia
                    </span>
                </div>

                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                    @foreach($siswaTersedia as $siswa)
                    <label class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" class="checkbox-siswa w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <div>
                                <div class="text-sm font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-medium rounded border border-slate-200">
                            {{ str($siswa->jenis_kelamin?->value ?? $siswa->jenis_kelamin)->upper()->startsWith(['L', 'LAKI']) ? 'L' : 'P' }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2 border border-emerald-100">
                        <i class="bi bi-check-lg text-xl"></i>
                    </div>
                    <p class="text-slate-800 font-medium">Semua siswa sudah memiliki rombel.</p>
                </div>
                @endif
            </div>

            <div class="p-6 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                <button type="button" onclick="closePlotModal()" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-lg transition">
                    Batal
                </button>
                @if($siswaTersedia->isNotEmpty())
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    Simpan Plotting
                </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openPlotModal() {
        const modal = document.getElementById('modalPlottingSiswa');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePlotModal() {
        const modal = document.getElementById('modalPlottingSiswa');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('selectAllSiswa');
        const checkboxes = document.querySelectorAll('.checkbox-siswa');

        if (selectAll) {
            selectAll.addEventListener('change', (e) => {
                checkboxes.forEach(cb => cb.checked = e.target.checked);
            });
        }
    });
</script>
@endpush