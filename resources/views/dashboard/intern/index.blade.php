@extends('layouts.dashboard')

@section('page-title', 'Dashboard Intern')

@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection

@section('content')
{{-- Profile Incomplete Banner --}}
@if(!$intern->isComplete())
@php $missing = $intern->getMissingFields(); $pct = $intern->getCompletionPercentage(); @endphp
<div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-amber-800">Profil belum lengkap</h3>
            <p class="text-sm text-amber-700 mt-1">Lengkapi profil agar bisa mendaftar program magang.</p>
            {{-- Progress Bar --}}
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-amber-700 mb-1">
                    <span>Progress profil</span>
                    <span class="font-semibold">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-amber-200 rounded-full h-2">
                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            {{-- Missing Fields --}}
            @if(count($missing) > 0)
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @foreach($missing as $field => $label)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ $label }}</span>
                    @endforeach
                </div>
            @endif
            <div class="mt-4">
                <a href="{{ route('intern.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Lengkapi Profil
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Welcome Banner --}}
<div class="bg-gradient-to-r from-zinc-900 via-zinc-800 to-zinc-900 text-white rounded-xl p-6 mb-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-yellow-400/10 to-transparent"></div>
    <div class="relative">
        <h2 class="text-2xl font-bold">Halo, {{ $intern->name ?? Auth::user()->name }}! 👋</h2>
        <p class="text-zinc-400 mt-1">Selamat datang di dashboard YUHLEZ. Semangat mengejar impianmu!</p>
        @if($stats['pending_applications'] > 0)
            <p class="text-sm text-yellow-400 mt-2 font-medium">📋 Ada {{ $stats['pending_applications'] }} pendaftaran yang sedang menunggu review.</p>
        @endif
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <p class="text-xs text-zinc-500 uppercase font-medium">Total Pendaftaran</p>
        <p class="text-3xl font-bold text-zinc-900 mt-1">{{ $stats['total_applications'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <p class="text-xs text-zinc-500 uppercase font-medium">Menunggu</p>
        <p class="text-3xl font-bold text-zinc-900 mt-1">{{ $stats['pending_applications'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
        <p class="text-xs text-zinc-500 uppercase font-medium">Diterima</p>
        <p class="text-3xl font-bold text-zinc-900 mt-1">{{ $stats['accepted_applications'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-violet-500">
        <p class="text-xs text-zinc-500 uppercase font-medium">Sertifikat</p>
        <p class="text-3xl font-bold text-zinc-900 mt-1">{{ $stats['total_certificates'] }}</p>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <a href="{{ route('intern.programs.index') }}" class="bg-gradient-to-r from-yellow-400 to-amber-400 text-zinc-900 rounded-xl p-5 hover:shadow-md transition group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Cari Program Magang</p>
                <p class="text-xs opacity-75">Temukan program yang sesuai</p>
            </div>
        </div>
    </a>
    <a href="{{ route('intern.profile.edit') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-zinc-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-zinc-900">Lengkapi Profil</p>
                <p class="text-xs text-zinc-500">Untuk bisa mendaftar magang</p>
            </div>
        </div>
    </a>
    <a href="{{ route('intern.certificates.index') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition border border-zinc-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-zinc-900">Sertifikat Saya</p>
                <p class="text-xs text-zinc-500">{{ $stats['total_certificates'] }} sertifikat tersedia</p>
            </div>
        </div>
    </a>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-zinc-900 mb-4">Tren Pendaftaran Bulanan</h3>
        <div class="relative" style="height: 200px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-zinc-900 mb-4">Status Pendaftaran</h3>
        <div class="flex items-center gap-6">
            <div class="relative" style="width: 160px; height: 160px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <span class="text-zinc-600">Pending</span>
                    <span class="font-semibold text-zinc-900 ml-auto">{{ $statusCounts['PENDING'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-zinc-600">Diterima</span>
                    <span class="font-semibold text-zinc-900 ml-auto">{{ $statusCounts['ACCEPTED'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-zinc-600">Ditolak</span>
                    <span class="font-semibold text-zinc-900 ml-auto">{{ $statusCounts['REJECTED'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-zinc-300"></span>
                    <span class="text-zinc-600">Dibatalkan</span>
                    <span class="font-semibold text-zinc-900 ml-auto">{{ $statusCounts['CANCELLED'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Two Column --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Activity Feed --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-zinc-900">Notifikasi</h3>
            <a href="{{ route('intern.notifications.index') }}" class="text-xs text-yellow-600 hover:text-yellow-700 font-medium">Semua →</a>
        </div>
        <div class="p-4 max-h-[380px] overflow-y-auto">
            @forelse($activityFeed as $item)
                <div class="flex items-start gap-3 py-3 @if(!$loop->last) border-b border-zinc-50 @endif">
                    <div class="mt-0.5 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        @if(str_contains($item->type, 'ACCEPTED')) bg-emerald-50
                        @elseif(str_contains($item->type, 'REJECTED')) bg-red-50
                        @elseif(str_contains($item->type, 'CERTIFICATE')) bg-amber-50
                        @elseif(str_contains($item->type, 'TASK')) bg-blue-50
                        @else bg-zinc-100 @endif">
                        @if(str_contains($item->type, 'ACCEPTED'))
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif(str_contains($item->type, 'REJECTED'))
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @elseif(str_contains($item->type, 'CERTIFICATE'))
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138z"/></svg>
                        @elseif(str_contains($item->type, 'TASK'))
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        @else
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-800">{{ $item->title }}</p>
                        <p class="text-xs text-zinc-500 mt-0.5 line-clamp-2">{{ $item->message }}</p>
                        <p class="text-xs text-zinc-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$item->is_read)
                        <span class="w-2 h-2 rounded-full bg-blue-500 mt-2 flex-shrink-0"></span>
                    @endif
                </div>
            @empty
                <p class="text-center text-zinc-400 text-sm py-8">Belum ada notifikasi</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-zinc-900">Pendaftaran Terbaru</h3>
            <a href="{{ route('intern.applications.index') }}" class="text-xs text-yellow-600 hover:text-yellow-700 font-medium">Lihat Semua →</a>
        </div>
        <div class="p-4">
            @forelse($recentApplications as $application)
                <div class="flex items-center justify-between py-3 @if(!$loop->last) border-b border-zinc-50 @endif">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-zinc-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-zinc-500 text-xs font-medium">{{ substr($application->program->title ?? '?', 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-zinc-900 text-sm">{{ $application->program->title ?? '-' }}</p>
                            <p class="text-xs text-zinc-500">{{ $application->position->name ?? '-' }} · {{ $application->applied_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                    @if($application->status === 'PENDING')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                    @elseif($application->status === 'ACCEPTED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                    @elseif($application->status === 'REJECTED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600">{{ $application->status }}</span>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-zinc-400 text-sm">Belum ada pendaftaran.</p>
                    <a href="{{ route('intern.programs.index') }}" class="mt-2 inline-flex items-center gap-1 text-sm text-yellow-600 hover:text-yellow-700 font-medium">Cari Program Magang →</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($monthlyTrend, 'month')),
                datasets: [{
                    label: 'Pendaftaran',
                    data: @json(array_column($monthlyTrend, 'count')),
                    backgroundColor: 'rgba(234, 179, 8, 0.6)',
                    borderColor: '#eab308',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#71717a' } },
                    y: { beginAtZero: true, grid: { color: '#f4f4f5' }, ticks: { font: { size: 11 }, color: '#71717a', stepSize: 1 } }
                }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Diterima', 'Ditolak', 'Dibatalkan'],
                datasets: [{
                    data: [{{ $statusCounts['PENDING'] }}, {{ $statusCounts['ACCEPTED'] }}, {{ $statusCounts['REJECTED'] }}, {{ $statusCounts['CANCELLED'] }}],
                    backgroundColor: ['#fbbf24', '#10b981', '#ef4444', '#d4d4d8'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endsection
