@props([
'label',
'value',
'icon',
'color' => 'blue'
])

@php
$colorMap = [
'blue' => 'bg-blue-50 text-blue-600',
'emerald' => 'bg-emerald-50 text-emerald-600',
'amber' => 'bg-amber-50 text-amber-500',
'cyan' => 'bg-cyan-50 text-cyan-600',
'red' => 'bg-red-50 text-red-600',
];
$iconColor = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center justify-between transition hover:shadow-md">
    <div>
        <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider block mb-1">{{ $label }}</span>
        <h3 class="font-bold text-3xl text-slate-800">{{ $value }}</h3>
    </div>
    <div class="{{ $iconColor }} w-14 h-14 rounded-xl flex items-center justify-center text-2xl">
        <i class="{{ $icon }}"></i>
    </div>
</div>