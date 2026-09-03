@extends('layouts.dashboard')
@section('page-title', 'Buat Program Magang')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-900">Buat Program Magang Baru</h2>
        </div>
        <form action="{{ route('company.programs.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Program <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap</label>
                <input type="hidden" name="description" id="program-description-hidden" value="{{ old('description') }}">
                <div id="program-description-editor" data-wysiwyg="program-description-hidden" data-placeholder="Jelaskan detail program magang..."></div>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">①</span>
                    <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">②</span>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">③</span>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">④</span>
                    <span class="text-xs text-gray-500 ml-1">← Urutan: Buka Daftar → Tutup Daftar → Mulai Program → Selesai Program</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran Dimulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="registration_start" value="{{ old('registration_start') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('registration_start') border-red-500 @enderror">
                    @error('registration_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendaftaran Ditutup <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="registration_end" value="{{ old('registration_end') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('registration_end') border-red-500 @enderror">
                    @error('registration_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Dimulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="program_start" value="{{ old('program_start') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('program_start') border-red-500 @enderror">
                    @error('program_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="program_end" value="{{ old('program_end') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('program_end') border-red-500 @enderror">
                    @error('program_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Positions --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Posisi <span class="text-red-500">*</span></label>
                <div id="positions-container" class="space-y-3">
                    <div class="position-row flex gap-3 items-end">
                        <div class="flex-1">
                            <input type="text" name="positions[0][name]" placeholder="Nama posisi (contoh: Barista)" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        </div>
                        <div class="w-32">
                            <input type="number" name="positions[0][quota]" placeholder="Kuota" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        </div>
                        <button type="button" onclick="addPosition()" class="px-3 py-2 bg-yellow-400 text-zinc-900 rounded-lg hover:bg-yellow-300 font-bold">+</button>
                    </div>
                </div>
                <button type="button" onclick="addPosition()" class="mt-2 text-sm text-yellow-600 hover:underline">+ Tambah Posisi</button>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('company.programs.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-yellow-400 text-zinc-900 font-semibold rounded-lg hover:bg-yellow-300">Simpan Program</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
let positionIndex = 1;
function addPosition() {
    const html = `
        <div class="position-row flex gap-3 items-end">
            <div class="flex-1">
                <input type="text" name="positions[${positionIndex}][name]" placeholder="Nama posisi" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
            </div>
            <div class="w-32">
                <input type="number" name="positions[${positionIndex}][quota]" placeholder="Kuota" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
            </div>
            <button type="button" onclick="this.closest('.position-row').remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>
        </div>`;
    document.getElementById('positions-container').insertAdjacentHTML('beforeend', html);
    positionIndex++;
}
</script>
@endsection
