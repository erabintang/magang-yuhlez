<form action="{{ route('root.homepage.about') }}" method="POST" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Subtitle</label>
            <input type="text" name="subtitle" value="{{ $section->get('subtitle', 'Tentang') }}"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Heading <span class="text-red-500">*</span></label>
            <input type="text" name="heading" value="{{ $section->get('heading', 'Software house dari Tegal untuk transformasi digital') }}" required
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">{{ $section->get('description', '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Visi</label>
            <textarea name="vision" rows="2"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">{{ $section->get('vision', '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-2">Misi</label>
            @php $missionItems = $section->get('mission_items', ['Menyiapkan infrastruktur transformasi digital', 'Menerapkan ekosistem digital', 'Edukasi transformasi digital']); @endphp
            <div id="mission-items" class="space-y-2">
                @foreach($missionItems as $i => $item)
                    <div class="flex gap-2 items-center">
                        <input type="text" name="mission_items[]" value="{{ $item }}" placeholder="Misi {{ $i + 1 }}"
                            class="flex-1 rounded-xl border border-zinc-300 px-4 py-2 text-sm focus:border-yellow-400 outline-none">
                        <button type="button" onclick="this.closest('div').remove()" class="text-red-400 hover:text-red-600 px-2">✕</button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addMission()" class="mt-2 text-sm text-yellow-600 hover:underline">+ Tambah Misi</button>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('root.homepage.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
<script>
function addMission() {
    const container = document.getElementById('mission-items');
    const count = container.querySelectorAll('input').length + 1;
    container.insertAdjacentHTML('beforeend', `
        <div class="flex gap-2 items-center">
            <input type="text" name="mission_items[]" placeholder="Misi ${count}" class="flex-1 rounded-xl border border-zinc-300 px-4 py-2 text-sm focus:border-yellow-400 outline-none">
            <button type="button" onclick="this.closest('div').remove()" class="text-red-400 hover:text-red-600 px-2">✕</button>
        </div>
    `);
}
</script>
