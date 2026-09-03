@props(['items' => [], 'currentRoute' => ''])

@foreach($items as $item)
    @php
        $isActive = $currentRoute === $item['href']
            ? request()->is(ltrim($item['href'], '/'))
            : request()->is(ltrim($item['href'], '/') . '*');
    @endphp
    <a href="{{ $item['href'] }}" class="flex items-center justify-between rounded-xl px-4 py-2.5 text-sm font-medium transition-colors {{ $isActive ? 'bg-zinc-900 text-yellow-400' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
        {{ $item['label'] }}
        @if(isset($item['badge']) && $item['badge'] > 0)
            <span class="ml-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-xs font-bold bg-yellow-400 text-zinc-950">
                {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
            </span>
        @endif
    </a>
@endforeach
