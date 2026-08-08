@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body">
        <h6 class="card-title fw-bold text-success mb-2">
            <i class="bi bi-circle-fill text-success small"></i> Pengguna Online Saat Ini ({{ $onlineUsers->count() }})
        </h6>
        <div class="d-flex flex-wrap gap-2">
            @forelse($onlineUsers as $online)
            <span class="badge bg-light text-dark border p-2">
                <i class="bi bi-person-fill text-primary me-1"></i>
                {{ $online->name }}
                <small class="text-muted">({{ $online->role->value }})</small>
            </span>
            @empty
            <span class="text-muted small">Tidak ada pengguna lain yang sedang online.</span>
            @endforelse
        </div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Subjek</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap small">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $log->causer?->name ?? 'System' }}</span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>
                            @if($log->subject)
                            <small class="text-muted">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection