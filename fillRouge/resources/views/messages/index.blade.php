@extends('layouts.app')
@section('title', 'Messages')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
        <i class="fas fa-envelope text-primary-600 mr-2"></i>Messages
    </h1>

    @if($conversations->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
            <i class="fas fa-comment-dots text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune conversation pour l'instant</p>
            @if(auth()->user()->isPatient())
            <a href="{{ route('doctors.search') }}"
               class="text-primary-600 hover:text-primary-700 font-medium text-sm mt-2 inline-block">
                Trouver un médecin pour démarrer une conversation
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
               class="flex items-center gap-4 bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:shadow-md transition hover:border-primary-200">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-primary-700 font-bold text-lg">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-gray-800">
                            {{ $contact->role === 'doctor' ? 'Dr. ' : '' }}{{ $contact->name }}
                        </p>
                        <p class="text-xs text-gray-400 flex-shrink-0">
                            {{ $lastMessage->sent_at->diffForHumans() }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500 truncate mt-0.5">
                        @if($lastMessage->sender_id === auth()->id())
                            <span class="text-gray-400">Vous : </span>
                        @endif
                        {{ $lastMessage->content }}
                    </p>
                </div>
                @if(!$lastMessage->read && $lastMessage->receiver_id === auth()->id())
                <div class="w-2.5 h-2.5 bg-primary-600 rounded-full flex-shrink-0" title="Non lu"></div>
                @endif
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection