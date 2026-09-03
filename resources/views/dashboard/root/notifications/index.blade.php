@extends('layouts.dashboard')
@section('page-title', 'Notifikasi')
@section('sidebar-nav')
@include('dashboard.root._sidebar')
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
    @if($unreadCount > 0)
        <form action="{{ route('root.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 text-sm text-yuhlez-primary hover:bg-yuhlez-light rounded-lg">Tandai semua sudah dibaca ({{ $unreadCount }})</button>
        </form>
    @endif
</div>
<div class="space-y-3">
    @forelse($notifications as $notification)
        @php
            $iconColor = match($notification->type) {
                'APPLICATION_ACCEPTED' => 'text-green-500',
                'APPLICATION_REJECTED' => 'text-red-500',
                'APPLICATION_RECEIVED' => 'text-blue-500',
                'PROGRAM_UPDATE' => 'text-yellow-500',
                'NEW_PROGRAM' => 'text-purple-500',
                'CERTIFICATE_AVAILABLE' => 'text-emerald-500',
                'PROFILE_UPDATE' => 'text-gray-500',
                'WORK_ADDED' => 'text-indigo-500',
                default => 'text-gray-400',
            };
            $bgColor = match($notification->type) {
                'APPLICATION_ACCEPTED' => 'bg-green-50',
                'APPLICATION_REJECTED' => 'bg-red-50',
                'APPLICATION_RECEIVED' => 'bg-blue-50',
                'PROGRAM_UPDATE' => 'bg-yellow-50',
                'NEW_PROGRAM' => 'bg-purple-50',
                'CERTIFICATE_AVAILABLE' => 'bg-emerald-50',
                'PROFILE_UPDATE' => 'bg-gray-50',
                'WORK_ADDED' => 'bg-indigo-50',
                default => 'bg-gray-50',
            };
        @endphp
        <div class="bg-white rounded-xl shadow-sm p-4 {{ $notification->is_read ? '' : 'border-l-4 border-yuhlez-primary' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full {{ $notification->is_read ? 'bg-gray-100' : $bgColor }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 {{ $notification->is_read ? 'text-gray-400' : $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 {{ $notification->is_read ? '' : 'font-bold' }}">{{ $notification->title }}</p>
                        <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(!$notification->is_read)
                    <form action="{{ route('root.notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">Tandai dibaca</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-500">Tidak ada notifikasi.</p>
        </div>
    @endforelse
</div>
<div class="mt-6">{{ $notifications->links() }}</div>
@endsection