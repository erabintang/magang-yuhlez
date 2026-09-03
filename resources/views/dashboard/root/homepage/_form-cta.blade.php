<form action="{{ route('root.homepage.cta') }}" method="POST" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Heading <span class="text-red-500">*</span></label>
            <input type="text" name="heading" value="{{ $section->get('heading', 'Sudah siap untuk go digital?') }}" required
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2"
                class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">{{ $section->get('description', '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $section->get('email', 'admin@yuhlez.com') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ $section->get('whatsapp', '6282125126584') }}"
                    class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('root.homepage.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
