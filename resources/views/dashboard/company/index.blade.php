@extends('layouts.dashboard')

@section('page-title', 'Dashboard Perusahaan')

@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-zinc-500">Total Program</p>
                <p class="text-3xl font-bold text-zinc-900">{{ $stats['total_programs'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-amber-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-zinc-500">Pending Review</p>
                <p class="text-3xl font-bold text-zinc-900">{{ $stats['pending_applications'] }}</p>
                @if($stats['pending_applications'] > 0)
                    <a href="{{ route('company.applications.index') }}" class="text-xs text-amber-600 hover:underline font-medium">Review sekarang →</a>
                @endif
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-zinc-500">Intern Diterima</p>
                <p class="text-3xl font-bold text-zinc-900">{{ $stats['accepted_interns'] }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-violet-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-zinc-500">Total Karya</p>
                <p class="text-3xl font-bold text-zinc-900">{{ $stats['total_works'] }}</p>
            </div>
            <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <a href="{{ route('company.programs.create') }}" class="bg-gradient-to-r from-yellow-400 to-amber-400 text-zinc-900 rounded-xl p-5 hover:shadow-md transition group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center group-hover:bg-white/40 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Buat Program Baru</p>
                <p class="text-xs opacity-75">Buka pendaftaran magang</p>
            </div>
        </div>
    </a>

    <a href="{{ route('company.works.create') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition group border border-zinc-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center group-hover:bg-violet-100 transition">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-zinc-900">Buat Karya</p>
                <p class="text-xs text-zinc-500">Publikasikan karya terbaru</p>
            </div>
        </div>
    </a>

    <a href="{{ route('company.tasks.create') }}" class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition group border border-zinc-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-zinc-900">Buat Tugas</p>
                <p class="text-xs text-zinc-500">Kirim tugas ke intern</p>
            </div>
        </div>
    </a>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-zinc-900 mb-4">Tren Pendaftaran Bulanan</h3>
        <div class="relative" style="height: 220px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-zinc-900 mb-4">Status Pendaftaran</h3>
        <div class="flex items-center gap-6">
            <div class="relative" style="width: 180px; height: 180px;">
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

{{-- Two Column: Activity + Applications --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Activity Feed --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-zinc-900">Aktivitas Terbaru</h3>
            <a href="{{ route('company.notifications.index') }}" class="text-xs text-yellow-600 hover:text-yellow-700 font-medium">Semua →</a>
        </div>
        <div class="p-4 max-h-[420px] overflow-y-auto">
            @forelse($activityFeed as $item)
                <div class="flex items-start gap-3 py-3 @if(!$loop->last) border-b border-zinc-50 @endif">
                    <div class="mt-0.5 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        @if(str_contains($item->type, 'ACCEPTED')) bg-emerald-50
                        @elseif(str_contains($item->type, 'REJECTED')) bg-red-50
                        @elseif(str_contains($item->type, 'RECEIVED')) bg-amber-50
                        @elseif(str_contains($item->type, 'TASK')) bg-blue-50
                        @else bg-zinc-100 @endif">
                        @if(str_contains($item->type, 'ACCEPTED'))
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif(str_contains($item->type, 'REJECTED'))
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                <p class="text-center text-zinc-400 text-sm py-8">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-zinc-900">Pendaftaran Terbaru</h3>
            <a href="{{ route('company.applications.index') }}" class="text-xs text-yellow-600 hover:text-yellow-700 font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Intern</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Program</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($recentApplications as $application)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-zinc-900">{{ $application->intern->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-700">{{ $application->program->title ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($application->status === 'PENDING')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                @elseif($application->status === 'ACCEPTED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                                @elseif($application->status === 'REJECTED')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($application->status === 'PENDING')
                                    <div class="flex gap-2">
                                        <form action="{{ route('company.applications.accept', $application->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-500 text-white text-xs rounded-lg hover:bg-emerald-600 transition font-medium">Terima</button>
                                        </form>
                                        <a href="{{ route('company.applications.show', $application->id) }}" class="px-3 py-1 bg-zinc-100 text-zinc-600 text-xs rounded-lg hover:bg-zinc-200 transition font-medium">Detail</a>
                                    </div>
                                @else
                                    <a href="{{ route('company.applications.show', $application->id) }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">Detail</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-400 text-sm">
                                Belum ada pendaftaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recentApplications->hasPages())
            <div class="px-6 py-4 border-t border-zinc-100">
                {{ $recentApplications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyTrend, 'month')),
                datasets: [{
                    label: 'Pendaftaran',
                    data: @json(array_column($monthlyTrend, 'count')),
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#eab308',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
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
