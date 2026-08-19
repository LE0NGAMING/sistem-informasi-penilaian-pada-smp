@props([
'title' => null,
'icon' => null,
'action' => null
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col']) }}>
    @if ($title || $action)
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h6 class="font-bold text-slate-800 flex items-center gap-2">
            @if($icon) <i class="{{ $icon }} text-blue-600"></i> @endif
            {{ $title }}
        </h6>
        @if($action)
        <div>{{ $action }}</div>
        @endif
    </div>
    @endif

    <div class="p-0">
        {{ $slot }}
    </div>
</div>