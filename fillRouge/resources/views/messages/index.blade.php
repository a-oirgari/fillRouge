@extends('layouts.app')
@section('title', __('messages.page_title'))

@section('content')
<div class="mx-auto max-w-2xl space-y-5">
    <h1 class="flex items-center gap-2 text-2xl font-bold tracking-tight text-slate-900">
        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 text-primary-800">
            <i class="fas fa-envelope"></i>
        </span>
        {{ __('messages.page_title') }}
    </h1>

    @if($conversations->isEmpty())
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 py-16 text-center shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <i class="fas fa-comments mb-4 text-5xl text-slate-300"></i>
            <p class="text-slate-600">{{ __('messages.empty') }}</p>
            @if(auth()->user()->isPatient())
            <a href="{{ route('doctors.search') }}"
               class="mt-3 inline-block text-sm font-medium text-primary-600 hover:text-primary-800">
                {{ __('messages.find_doctor_cta') }}
            </a>
            @endif
        </div>
    @else
        <div class="space-y-2">
            @foreach($conversations as $userId => $lastMessage)
            @php
                $contact = $lastMessage->sender_id === auth()->id()
                    ? $lastMessage->receiver
                    : $lastMessage->sender;
            @endphp
            <a href="{{ route('messages.conversation', $contact) }}"
               class="flex items-center gap-4 rounded-2xl border border-slate-300/50 bg-white/90 p-4 shadow-sm ring-1 ring-slate-900/5 backdrop-blur-sm transition hover:border-primary-200 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-800">
                    <span class="text-lg font-bold">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($contact->name, 0, 1)) }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <p class="truncate font-semibold text-slate-900">
                            {{ $contact->role === 'doctor' ? 'Dr. ' : '' }}{{ $contact->name }}
                        </p>
                        <p class="shrink-0 text-xs text-slate-500">
                            {{ $lastMessage->sent_at->diffForHumans() }}
                        </p>
                    </div>
                    <p class="mt-0.5 truncate text-sm text-slate-600">
                        @if($lastMessage->sender_id === auth()->id())
                            <span class="text-slate-400">{{ __('messages.you_prefix') }} </span>
                        @endif
                        {{ $lastMessage->content }}
                    </p>
                </div>
                @if(!$lastMessage->read && $lastMessage->receiver_id === auth()->id())
                <div class="h-2.5 w-2.5 shrink-0 rounded-full bg-primary-600" title="{{ __('messages.unread') }}"></div>
                @endif
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
