@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
<div class="space-y-6 pb-12">

    <!-- 1. Card Pengguna Online Saat Ini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3.5">
            <div class="flex items-center gap-2.5">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    Pengguna Online Saat Ini
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        {{ $onlineUsers->count() }} Online
                    </span>
                </h3>
            </div>
        </div>

        <div class="flex flex-wrap gap-2.5">
            @forelse($onlineUsers as $online)
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition text-xs font-medium text-slate-700 shadow-2xs">
                <!-- Initial Avatar -->
                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-[10px] shrink-0">
                    {{ strtoupper(substr($online->name, 0, 1)) }}
                </div>
                <span class="font-semibold text-slate-800">{{ $online->name }}</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100/80">
                    {{ $online->role->value ?? $online->role }}
                </span>
            </div>
            @empty
            <div class="text-xs text-slate-400 italic py-1">
                Tidak ada pengguna lain yang sedang online saat ini.
            </div>
            @endforelse
        </div>
    </div>

    <!-- 2. Card Tabel Log Aktivitas -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Riwayat Log Aktivitas</h3>
                <p class="text-xs text-slate-500 mt-0.5">Memantau seluruh aktivitas dan perubahan data dalam sistem.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-xs uppercase font-semibold text-slate-500 tracking-wider">
                        <th class="py-3.5 px-6">Waktu</th>
                        <th class="py-3.5 px-6">User / Pelaku</th>
                        <th class="py-3.5 px-6">Aktivitas</th>
                        <th class="py-3.5 px-6">Subjek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 transition">
                        <!-- Waktu -->
                        <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-500 font-mono">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>

                        <!-- User (Causer) -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            @if($log->causer)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 flex items-center justify-center text-[10px] font-bold shrink-0 border border-slate-200">
                                    {{ strtoupper(substr($log->causer->name, 0, 1)) }}
                                </div>
                                <span class="text-slate-800 font-semibold text-xs">{{ $log->causer->name }}</span>
                            </div>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                🤖 System
                            </span>
                            @endif
                        </td>

                        <!-- Aktivitas -->
                        <td class="py-4 px-6 text-slate-700 text-xs leading-relaxed">
                            {{ $log->description }}
                        </td>

                        <!-- Subjek -->
                        <td class="py-4 px-6 whitespace-nowrap text-xs">
                            @if($log->subject)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-mono text-[11px] border border-slate-200/60">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                            </span>
                            @else
                            <span class="text-slate-400 font-mono">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                            Belum ada log aktivitas yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection