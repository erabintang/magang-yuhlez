@extends('layouts.dashboard')

@section('title', 'Notifikasi - YUHLEZ')

@section('sidebar-nav')
@include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Notifikasi</h1>
        <p class="mt-1 text-sm text-zinc-500">{{ $unreadCount }} belum dibaca</p>
    </div>
    @if($unreadCount > 0)
        <form action="{{ route('company.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50">Tandai semua sudah dibaca</button>
        </form>
    @endif
</div>

<div class="space-y-2">
    @forelse($notifications as $notification)
        @php
            $iconColor = match($notification->type) {
                'APPLICATION_ACCEPTED' => 'text-green-500',
                'APPLICATION_REJECTED' => 'text-red-500',
                'APPLICATION_RECEIVED' => 'text-blue-500',
                'PROGRAM_UPDATE' => 'text-yellow-500',
                'NEW_PROGRAM' => 'text-purple-500',
                'CERTIFICATE_AVAILABLE' => 'text-emerald-500',
                'PROFILE_UPDATE' => 'text-zinc-500',
                'WORK_ADDED' => 'text-indigo-500',
                'TASK_ASSIGNED' => 'text-orange-500',
                'TASK_COMPLETED' => 'text-teal-500',
                'WORK_SUBMISSION_RECEIVED' => 'text-blue-500',
                'WORK_SUBMISSION_ACCEPTED' => 'text-green-500',
                'WORK_SUBMISSION_REJECTED' => 'text-red-500',
                default => 'text-zinc-400',
            };
            $bgColor = match($notification->type) {
                'APPLICATION_ACCEPTED' => 'bg-green-50',
                'APPLICATION_REJECTED' => 'bg-red-50',
                'APPLICATION_RECEIVED' => 'bg-blue-50',
                'PROGRAM_UPDATE' => 'bg-yellow-50',
                'NEW_PROGRAM' => 'bg-purple-50',
                'CERTIFICATE_AVAILABLE' => 'bg-emerald-50',
                'PROFILE_UPDATE' => 'bg-zinc-50',
                'WORK_ADDED' => 'bg-indigo-50',
                'TASK_ASSIGNED' => 'bg-orange-50',
                'TASK_COMPLETED' => 'bg-teal-50',
                'WORK_SUBMISSION_RECEIVED' => 'bg-blue-50',
                'WORK_SUBMISSION_ACCEPTED' => 'bg-green-50',
                'WORK_SUBMISSION_REJECTED' => 'bg-red-50',
                default => 'bg-zinc-50',
            };
        @endphp
        <div class="rounded-2xl border border-zinc-200 bg-white p-4 transition-colors {{ $notification->is_read ? '' : 'border-l-4 border-l-yellow-400' }}">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $notification->is_read ? 'bg-zinc-100' : $bgColor }}">
                        <svg class="w-4 h-4 {{ $notification->is_read ? 'text-zinc-400' : $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900">{{ $notification->title }}</p>
                        <p class="mt-0.5 text-sm text-zinc-600">{{ $notification->message }}</p>
                        <p class="mt-1 text-xs text-zinc-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(!$notification->is_read)
                    <form action="{{ route('company.notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-zinc-400 hover:text-zinc-600 shrink-0">✓</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
            <p class="text-zinc-500">Belum ada notifikasi</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $notifications->links() }}</div>
@endsection