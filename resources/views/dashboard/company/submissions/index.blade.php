@extends('layouts.dashboard')

@section('title', 'Review Karya - YUHLEZ')

@section('sidebar-nav')
    @include('dashboard.company._sidebar')
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-zinc-900">Review Karya</h1>
    <p class="mt-1 text-sm text-zinc-500">Daftar karya yang dikirim intern untuk review.</p>
</div>

{{-- Status Filter --}}
<div class="mb-4 flex flex-wrap gap-2">
    <a href="{{ route('company.submissions.index') }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Semua</a>
    <a href="{{ route('company.submissions.index', ['status' => 'PENDING']) }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request('status') === 'PENDING' ? 'bg-amber-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Menunggu</a>
    <a href="{{ route('company.submissions.index', ['status' => 'ACCEPTED']) }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request('status') === 'ACCEPTED' ? 'bg-green-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Diterima</a>
    <a href="{{ route('company.submissions.index', ['status' => 'REJECTED']) }}" class="rounded-full px-4 py-1.5 text-sm font-medium {{ request('status') === 'REJECTED' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">Ditolak</a>
</div>

@if($submissions->count() > 0)
    <div class="space-y-4">
        @foreach($submissions as $submission)
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-zinc-900">{{ $submission->title }}</h3>
                            @if($submission->status === 'PENDING')
                                <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Menunggu</span>
                            @elseif($submission->status === 'ACCEPTED')
                                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Diterima</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Ditolak</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">Intern: <strong>{{ $submission->intern->name ?? '-' }}</strong> · Karya: {{ $submission->work->title }}</p>
                        @if($submission->description)
                            <p class="mt-1 text-sm text-zinc-600 line-clamp-2">{{ $submission->description }}</p>
                        @endif
                        <div class="mt-2 flex items-center gap-3 text-xs text-zinc-400">
                            <span>{{ $submission->files->count() }} file</span>
                            <span>·</span>
                            <span>{{ $submission->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('company.submissions.show', $submission->id) }}" class="shrink-0 rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-yellow-300">
                        {{ $submission->status === 'PENDING' ? 'Review' : 'Detail' }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $submissions->links() }}</div>
@else
    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-12 text-center">
        <p class="text-zinc-500">Belum ada karya yang dikirim intern.</p>
    </div>
@endif
@endsection
