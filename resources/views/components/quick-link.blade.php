@props([
'href' => '#',
'icon',
'iconColor' => 'text-blue-500'
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 text-sm bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-slate-700 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50/30 transition']) }}>
    <i class="{{ $icon }} {{ $iconColor }} text-lg"></i>
    <span>{{ $slot }}</span>
</a>