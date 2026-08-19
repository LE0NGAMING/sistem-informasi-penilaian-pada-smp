@props(['color' => 'slate'])

@php
$colorMap = [
'slate' => 'bg-slate-100 text-slate-700 border-slate-200',
'red' => 'bg-red-50 text-red-600 border-red-100',
'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
'cyan' => 'bg-cyan-50 text-cyan-600 border-cyan-100',
];
$badgeStyle = $colorMap[$color] ?? $colorMap['slate'];
@endphp

<span {{ $attributes->merge(['class' => "border px-2.5 py-0.5 rounded-md text-xs font-medium inline-flex items-center gap-1 $badgeStyle"]) }}>
    {{ $slot }}
</span>