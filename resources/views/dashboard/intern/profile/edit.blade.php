@extends('layouts.dashboard')
@section('page-title', 'Edit Profil')
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
@php $intern = $intern ?? null; @endphp
<div class="max-w-2xl mx-auto">
    @php
        $pct = $intern->getCompletionPercentage();
        $missing = $intern->getMissingFields();
        $isComplete = $intern->isComplete();
        $requiredCount = count($intern->requiredFields);
    @endphp

    {{-- Completion Progress --}}
    @if(!$isComplete)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-sm font-semibold text-amber-800">Profil {{ $pct }}% lengkap</span>
            </div>
            <span class="text-xs text-amber-700">{{ $requiredCount - count($missing) }}/{{ $requiredCount }} kolom terisi</span>
        </div>
        <div class="w-full bg-amber-200 rounded-full h-2">
            <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
        </div>
        @if(count($missing) > 0)
            <div class="mt-2 flex flex-wrap gap-1">
                @foreach($missing as $field => $label)
                    <span class="text-xs px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">{{ $label }}</span>
                @endforeach
            </div>
        @endif
    </div>
    @else
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-semibold text-emerald-800">Profil sudah lengkap!</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-900">Edit Profil</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi profil untuk bisa mendaftar program magang</p>
        </div>
        <form action="{{ route('intern.profile.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $intern->name ?? Auth::user()->name ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $intern->whatsapp ?? '') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $intern->contact_email ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">{{ old('address', $intern->address ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gmail Access (Email Google)</label>
                <input type="email" name="gmail_access" value="{{ old('gmail_access', $intern->gmail_access ?? '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Email Google yang digunakan untuk login</p>
            </div>

            {{-- Profile Photo Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <div class="flex items-center gap-4">
                    @if(!empty($intern->profile_photo_file_id))
                        <img src="{{ route('files.download', $intern->profile_photo_file_id) }}" alt="Foto Profil"
                            class="w-16 h-16 rounded-full object-cover border border-gray-200" id="photo-preview">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200" id="photo-preview-container">
                            <span class="text-gray-400 text-lg font-medium">{{ substr($intern->name ?? Auth::user()->name ?? '?', 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" accept="image/*" data-chunked-upload data-bucket="yuhlez" data-progress="photo-progress" data-preview="#photo-preview" data-hidden-input="#profile_photo_file_id"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yuhlez-light file:text-yuhlez-primary hover:file:bg-blue-100">
                        <div id="photo-progress" class="hidden mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="progress-fill bg-yuhlez-primary h-2 rounded-full transition-all" style="width:0%"></div>
                            </div>
                            <p class="progress-text text-xs text-gray-500 mt-1">Mengupload...</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="profile_photo_file_id" id="profile_photo_file_id" value="{{ old('profile_photo_file_id', $intern->profile_photo_file_id ?? '') }}">
            </div>

            {{-- CV Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CV / Resume</label>
                <div class="border border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition">
                    @if(!empty($intern->cv_file_id))
                        <div class="flex items-center justify-center gap-2 text-green-700 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm font-medium">CV sudah diupload</span>
                        </div>
                        <a href="{{ route('files.download', $intern->cv_file_id) }}" target="_blank" class="text-xs text-yuhlez-primary hover:underline">Lihat CV saat ini →</a>
                    @endif
                    <input type="file" accept=".pdf,.doc,.docx" data-chunked-upload data-bucket="yuhlez" data-progress="cv-progress" data-hidden-input="#cv_file_id"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yuhlez-light file:text-yuhlez-primary hover:file:bg-blue-100 mt-2">
                    <p class="text-xs text-gray-400 mt-1">Format: PDF, DOC, DOCX (Maks. 10MB)</p>
                    <div id="cv-progress" class="hidden mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="progress-fill bg-yuhlez-primary h-2 rounded-full transition-all" style="width:0%"></div>
                        </div>
                        <p class="progress-text text-xs text-gray-500 mt-1">Mengupload...</p>
                    </div>
                </div>
                <input type="hidden" name="cv_file_id" id="cv_file_id" value="{{ old('cv_file_id', $intern->cv_file_id ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="short_description" rows="2" maxlength="500" placeholder="Ceritakan diri Anda secara singkat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yuhlez-primary focus:border-transparent">{{ old('short_description', $intern->short_description ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Maks. 500 karakter</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tentang Saya</label>
                <input type="hidden" name="description" id="intern-description-hidden" value="{{ old('description', $intern->description ?? '') }}">
                <div id="intern-description-editor" data-wysiwyg="intern-description-hidden" data-placeholder="Ceritakan pengalaman, skill, dan minat Anda..." class="wysiwyg-container"></div>
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
