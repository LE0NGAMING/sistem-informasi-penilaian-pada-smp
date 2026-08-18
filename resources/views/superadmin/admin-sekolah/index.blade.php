@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 font-weight-bold">Kelola Admin Sekolah</h1>
            <p class="text-muted mb-0">Kelola akun administrator sekolah yang bertanggung jawab atas data operasional harian.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin Sekolah
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username / NIP</th>
                            <th>Email</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminSekolah as $index => $admin)
                        <tr>
                            <td class="ps-3">{{ $adminSekolah->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-dark">{{ $admin->name }}</strong>
                            </td>
                            <td><code>{{ $admin->username }}</code></td>
                            <td>{{ $admin->email }}</td>
                            <td class="text-center">
                                @if($admin->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditAdmin{{ $admin->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('superadmin.admin-sekolah.toggle-status', $admin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $admin->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                            {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('superadmin.admin-sekolah.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Admin -->
                        <div class="modal fade" id="modalEditAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('superadmin.admin-sekolah.update', $admin->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Admin Sekolah</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Username / NIP</label>
                                                <input type="text" name="username" class="form-control" value="{{ $admin->username }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada akun Admin Sekolah. Klik tombol di atas untuk membuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($adminSekolah->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $adminSekolah->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('superadmin.admin-sekolah.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin Sekolah Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username / NIP</label>
                        <input type="text" name="username" class="form-control" placeholder="admin_budi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="budi@smpn1.sch.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Awal</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Akun Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection