@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col items-center gap-3">

        {{-- Info --}}
        <p class="text-sm text-zinc-500">
            Menampilkan
            <span class="font-semibold text-zinc-700">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-semibold text-zinc-700">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-zinc-700">{{ $paginator->total() }}</span>
            data
        </p>

        {{-- Page Numbers --}}
        <div class="flex items-center gap-1.5">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-yellow-50 hover:border-yellow-300 hover:text-yellow-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-white text-zinc-400 text-sm cursor-default">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-yellow-400 text-zinc-950 text-sm font-bold shadow-sm shadow-yellow-400/25">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-white text-zinc-600 text-sm font-medium hover:bg-yellow-50 hover:border-yellow-300 hover:text-yellow-700 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-yellow-50 hover:border-yellow-300 hover:text-yellow-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
