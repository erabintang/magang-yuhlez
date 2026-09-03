@extends('layouts.dashboard')

@section('page-title', $company->name . ' - Perusahaan - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('root.companies') }}" class="text-sm text-zinc-500 hover:text-zinc-700">← Kembali ke Perusahaan</a>
    <h1 class="mt-2 text-2xl font-bold text-zinc-900">{{ $company->name }}</h1>
    @if($company->short_description)
        <p class="mt-1 text-zinc-600">{{ $company->short_description }}</p>
    @endif
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Informasi Perusahaan</h2>
            <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <dt class="text-zinc-500">Nama</dt>
                    <dd class="mt-1 text-zinc-900">{{ $company->name }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Email</dt>
                    <dd class="mt-1 text-zinc-900">{{ $company->contact_email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">WhatsApp</dt>
                    <dd class="mt-1 text-zinc-900">{{ $company->whatsapp ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Alamat</dt>
                    <dd class="mt-1 text-zinc-900">{{ $company->address ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Gmail Access</dt>
                    <dd class="mt-1 text-zinc-900">{{ $company->gmail_access ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Slug</dt>
                    <dd class="mt-1 font-mono text-xs text-zinc-900">{{ $company->slug }}</dd>
                </div>
            </dl>

            @if($company->description)
                <div class="mt-4 border-t border-zinc-200 pt-4">
                    <h3 class="text-sm font-medium text-zinc-700 mb-2">Deskripsi</h3>
                    <div class="text-sm text-zinc-600 prose prose-sm max-w-none">{!! $company->description !!}</div>
                </div>
            @endif
        </div>

        {{-- Programs --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="font-semibold text-zinc-900 mb-4">Program Magang ({{ $company->programs->count() }})</h2>
            @if($company->programs->count() > 0)
                <div class="space-y-2">
                    @foreach($company->programs as $program)
                        <a href="{{ route('root.programs.show', $program->slug) }}" class="flex items-center justify-between rounded-xl bg-zinc-50 px-4 py-3 hover:bg-zinc-100 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $program->title }}</p>
                                <p class="text-xs text-zinc-500">{{ $program->registration_end?->format('d M Y') }}</p>
                            </div>
                            @if($program->registration_end >= now())
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500">Selesai</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-zinc-400 text-sm">Belum ada program magang</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">Statistik</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-zinc-500">Total Program</span>
                    <span class="font-semibold text-zinc-900">{{ $stats['total_programs'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-zinc-500">Program Aktif</span>
                    <span class="font-semibold text-zinc-900">{{ $stats['active_programs'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-zinc-500">Total Intern</span>
                    <span class="font-semibold text-zinc-900">{{ $stats['total_interns'] }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h3 class="font-semibold text-zinc-900 mb-3">User Account</h3>
            @if($company->user)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-zinc-100 rounded-full flex items-center justify-center">
                        <span class="text-zinc-500 text-sm font-medium">{{ substr($company->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-900">{{ $company->user->name }}</p>
                        <p class="text-xs text-zinc-500">{{ $company->user->email }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
