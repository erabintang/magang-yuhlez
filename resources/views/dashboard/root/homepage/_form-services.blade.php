@php $items = $section->items(); @endphp

@if(count($items) > 0)
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Layanan Saat Ini ({{ count($items) }})</h3>
    <div class="grid gap-3">
        @foreach($items as $i => $service)
            <div class="flex items-start justify-between rounded-xl bg-zinc-50 px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-zinc-900">{{ $service['title'] ?? '-' }}</p>
                    <p class="text-xs text-zinc-500 mt-0.5">{{ $service['description'] ?? '' }}</p>
                </div>
                <form action="{{ route('root.homepage.services.remove') }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                    @csrf
                    <input type="hidden" name="index" value="{{ $i }}">
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Tambah Layanan</h3>
    <form action="{{ route('root.homepage.services') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" required placeholder="Web Design, Web Apps, IT Consultant..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="2" required placeholder="Deskripsi singkat layanan..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none"></textarea>
        </div>
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Tambah Layanan</button>
    </form>
</div>
