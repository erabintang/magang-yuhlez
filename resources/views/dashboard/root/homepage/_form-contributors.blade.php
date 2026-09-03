@php $items = $section->items(); @endphp

@if(count($items) > 0)
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Kontributor Saat Ini ({{ count($items) }})</h3>
    <div class="grid gap-3">
        @foreach($items as $i => $contrib)
            <div class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-zinc-900">{{ $contrib['name'] ?? '-' }}</p>
                    <p class="text-xs text-zinc-500">{{ $contrib['description'] ?? '' }}{{ !empty($contrib['url']) ? ' · ' . $contrib['url'] : '' }}</p>
                </div>
                <form action="{{ route('root.homepage.contributors.remove') }}" method="POST" onsubmit="return confirm('Hapus kontributor ini?')">
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
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Tambah Kontributor</h3>
    <form action="{{ route('root.homepage.contributors') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
                <input type="text" name="description" placeholder="Ruang kreatif, Komunitas..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">URL</label>
                <input type="text" name="url" placeholder="https://..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Path Logo</label>
                <input type="text" name="logo" placeholder="brand/contributors/nama.png" class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Tambah Kontributor</button>
    </form>
</div>
