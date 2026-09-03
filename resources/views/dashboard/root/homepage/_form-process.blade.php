@php $steps = $section->get('steps', [['step'=>'01','title'=>'Konsultasi','description'=>'Ceritakan kebutuhan Anda'],['step'=>'02','title'=>'Perancangan','description'=>'Desain UI/UX dan arsitektur'],['step'=>'03','title'=>'Pengembangan','description'=>'Tim mengembangkan aplikasi'],['step'=>'04','title'=>'Peluncuran','description'=>'Testing dan go-live']]); @endphp

<form action="{{ route('root.homepage.process') }}" method="POST" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Subtitle</label>
                <input type="text" name="subtitle" value="{{ $section->get('subtitle', 'Cara kerja') }}" class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1">Heading <span class="text-red-500">*</span></label>
                <input type="text" name="heading" value="{{ $section->get('heading', 'Dari konsultasi hingga peluncuran') }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-1">Deskripsi</label>
            <input type="text" name="description" value="{{ $section->get('description', '') }}" class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm focus:border-yellow-400 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700 mb-2">Langkah-langkah</label>
            <div id="steps-container" class="space-y-3">
                @foreach($steps as $i => $step)
                    <div class="flex gap-3 items-start rounded-xl bg-zinc-50 p-3">
                        <span class="mt-2 text-lg font-extrabold text-yellow-500 w-8 text-center">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex-1 grid grid-cols-2 gap-3">
                            <input type="text" name="steps[{{ $i }}][title]" value="{{ $step['title'] ?? '' }}" placeholder="Judul langkah" required class="rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-yellow-400 outline-none">
                            <input type="text" name="steps[{{ $i }}][description]" value="{{ $step['description'] ?? '' }}" placeholder="Deskripsi langkah" required class="rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-yellow-400 outline-none">
                        </div>
                        <button type="button" onclick="this.closest('.flex').remove()" class="mt-2 text-red-400 hover:text-red-600 px-1">✕</button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addStep()" class="mt-2 text-sm text-yellow-600 hover:underline">+ Tambah Langkah</button>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        <button type="submit" class="rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">Simpan Perubahan</button>
        <a href="{{ route('root.homepage.index') }}" class="rounded-xl border border-zinc-300 px-6 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">Batal</a>
    </div>
</form>
<script>
function addStep() {
    const container = document.getElementById('steps-container');
    const count = container.querySelectorAll('.flex').length + 1;
    const num = String(count).padStart(2, '0');
    container.insertAdjacentHTML('beforeend', `
        <div class="flex gap-3 items-start rounded-xl bg-zinc-50 p-3">
            <span class="mt-2 text-lg font-extrabold text-yellow-500 w-8 text-center">${num}</span>
            <div class="flex-1 grid grid-cols-2 gap-3">
                <input type="text" name="steps[${count-1}][title]" placeholder="Judul langkah" required class="rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-yellow-400 outline-none">
                <input type="text" name="steps[${count-1}][description]" placeholder="Deskripsi langkah" required class="rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-yellow-400 outline-none">
            </div>
            <button type="button" onclick="this.closest('.flex').remove()" class="mt-2 text-red-400 hover:text-red-600 px-1">✕</button>
        </div>
    `);
}
</script>
