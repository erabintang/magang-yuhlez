<form action="{{ route('root.homepage.hero') }}" method="POST" class="max-w-2xl">
    @csrf @method('POST')
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Judul Utama <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ $section->get('title', 'From Useless to YUHLEZ') }}" required
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Subtitle</label>
            <input type="text" name="subtitle" value="{{ $section->get('subtitle', 'The Best Solution for Website & Web Apps') }}"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">{{ $section->get('description', '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">CTA Primer - Teks</label>
                <input type="text" name="cta_primary_text" value="{{ $section->get('cta_primary_text', 'Lihat Program Magang') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">CTA Primer - URL</label>
                <input type="text" name="cta_primary_url" value="{{ $section->get('cta_primary_url', '/magang') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">CTA Sekunder - Teks</label>
                <input type="text" name="cta_secondary_text" value="{{ $section->get('cta_secondary_text', 'Masuk dengan Google') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">CTA Sekunder - URL</label>
                <input type="text" name="cta_secondary_url" value="{{ $section->get('cta_secondary_url', '/login') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('root.homepage.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
