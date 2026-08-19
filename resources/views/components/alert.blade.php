@props(['type' => 'success', 'message' => ''])

@php
$styles = $type === 'success'
? 'bg-emerald-50 text-emerald-800 border-emerald-200'
: 'bg-red-50 text-red-800 border-red-200';
$icon = $type === 'success' ? 'bi-check-circle-fill text-emerald-600' : 'bi-exclamation-triangle-fill text-red-600';
@endphp

<div class="flash-alert flex items-center justify-between p-4 mb-4 border rounded-xl shadow-sm transition-opacity duration-500 opacity-100 {{ $styles }}">
    <div class="flex items-center gap-3">
        <i class="bi {{ $icon }} text-lg"></i>
        <span class="text-sm font-medium">{{ $message }}</span>
    </div>

    <!-- Tombol Close Manual -->
    <button type="button" onclick="this.closest('.flash-alert').remove()" class="text-slate-400 hover:text-slate-600 transition">
        <i class="bi bi-x-lg text-sm"></i>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelectorAll('.flash-alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500); // Hapus elemen dari DOM setelah animasi fade out
            });
        }, 3000);
    });
</script>