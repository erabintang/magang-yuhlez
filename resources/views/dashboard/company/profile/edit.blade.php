@extends('layouts.dashboard')
@section('page-title', 'Edit Profil Perusahaan')
@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-900">Edit Profil Perusahaan</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi profil perusahaan Anda</p>
        </div>
        <form action="{{ route('company.profile.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Logo Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan</label>
                <div class="flex items-center gap-4">
                    @if($company && $company->logo_file_id)
                        <img src="{{ route('files.download', $company->logo_file_id) }}" alt="Logo"
                            class="w-20 h-20 rounded-xl object-cover border border-gray-200" id="logo-preview">
                    @else
                        <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200 border-dashed">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" accept="image/*" data-chunked-upload data-bucket="yuhlez" data-progress="logo-progress" data-preview="#logo-preview" data-hidden-input="#logo_file_id"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yuhlez-light file:text-yuhlez-primary hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, SVG (Maks. 5MB)</p>
                        <div id="logo-progress" class="hidden mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-fill bg-yuhlez-primary h-2 rounded-full transition-all" style="width:0%"></div>
                            </div>
                            <p class="progress-text text-xs text-gray-500 mt-1">Mengupload...</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="logo_file_id" id="logo_file_id" value="{{ old('logo_file_id', $company->logo_file_id ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                <input type="text" name="short_description" value="{{ old('short_description', $company->short_description ?? '') }}" maxlength="500" placeholder="Ringkasan singkat tentang perusahaan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Maks. 500 karakter</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap</label>
                <input type="hidden" name="description" id="company-description-hidden" value="{{ old('description', $company->description ?? '') }}">
                <div id="company-description-editor" data-wysiwyg="company-description-hidden" data-placeholder="Ceritakan tentang perusahaan, visi, misi, dan layanan..." class="wysiwyg-container"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $company->whatsapp ?? '') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="2" placeholder="Alamat lengkap perusahaan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">{{ old('address', $company->address ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gmail Access (Email Google)</label>
                <input type="email" name="gmail_access" value="{{ old('gmail_access', $company->gmail_access ?? '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Google Maps Embed URL</label>
                <input type="text" name="gmap_embed" value="{{ old('gmap_embed', $company->gmap_embed ?? '') }}" placeholder="https://www.google.com/maps/embed?..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Paste embed URL dari Google Maps</p>
            </div>
            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary font-medium">Simpan Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/chunked-upload.js') }}"></script>
@endsection
