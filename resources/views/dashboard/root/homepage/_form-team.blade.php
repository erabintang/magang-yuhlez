@php $items = $section->items(); @endphp

{{-- Existing Members --}}
@if(count($items) > 0)
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Anggota Tim Saat Ini ({{ count($items) }})</h3>
    <div class="grid gap-3">
        @foreach($items as $i => $member)
            <div class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-zinc-200 rounded-full flex items-center justify-center">
                        <span class="text-zinc-500 text-sm font-medium">{{ substr($member['name'] ?? '?', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900">{{ $member['name'] ?? '-' }}</p>
                        <p class="text-xs text-zinc-500">{{ $member['role'] ?? '' }}{{ !empty($member['focus']) ? ' · ' . $member['focus'] : '' }}</p>
                    </div>
                </div>
                <form action="{{ route('root.homepage.team.remove') }}" method="POST" onsubmit="return confirm('Hapus {{ $member['name'] ?? 'anggota' }}?')">
                    @csrf
                    <input type="hidden" name="index" value="{{ $i }}">
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Add New Member --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-sm font-semibold text-zinc-900 mb-4">Tambah Anggota Tim</h3>
    <form action="{{ route('root.homepage.team') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Role <span class="text-red-500">*</span></label>
                <input type="text" name="role" required placeholder="CEO, CTO, Designer..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Fokus</label>
                <input type="text" name="focus" placeholder="Web Developer, Design & Branding..." class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Path Foto</label>
                <input type="text" name="photo" placeholder="brand/nama.png" class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Tambah Anggota</button>
    </form>
</div>
