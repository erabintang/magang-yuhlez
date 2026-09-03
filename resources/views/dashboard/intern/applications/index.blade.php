@extends('layouts.dashboard')
@section('page-title', 'Pendaftaran Saya')
@section('sidebar-nav')
@include('dashboard.intern._sidebar')
@endsection
@section('content')
<h2 class="text-2xl font-bold text-gray-900 mb-6">Pendaftaran Saya</h2>
<div class="space-y-4">
    @forelse($applications as $app)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ $app->program->title ?? '-' }}</h3>
                    <p class="text-sm text-gray-600">{{ $app->position->name ?? '-' }} &middot; {{ $app->program->company->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400 mt-1">Mendaftar: {{ $app->applied_at?->format('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    @if($app->status === 'PENDING')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                        <form action="{{ route('intern.applications.cancel', $app->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Batalkan pendaftaran?')">
                            @csrf
                            <button type="submit" class="text-red-500 hover:underline text-xs">Batalkan</button>
                        </form>
                    @elseif($app->status === 'ACCEPTED')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Diterima</span>
                    @elseif($app->status === 'REJECTED')
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Ditolak</span>
                    @elseif($app->status === 'CANCELLED')
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-500 rounded">Dibatalkan</span>
                    @endif
                </div>
            </div>
            @if($app->rejection_reason)
                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-700"><strong>Alasan Penolakan:</strong> {{ $app->rejection_reason }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-500">Kamu belum mendaftar program magang apapun.</p>
            <a href="{{ route('intern.programs.index') }}" class="mt-4 inline-block px-6 py-2 bg-yuhlez-primary text-white rounded-lg hover:bg-yuhlez-secondary">Cari Program</a>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $applications->links() }}</div>
@endsection
