@extends('layouts.app')
@section('title', __('messages.conversation_with', ['name' => ($contact->role === 'doctor' ? 'Dr. ' : '').$contact->name]))

@section('content')
<div class="mx-auto flex max-w-3xl flex-col" style="height: calc(100vh - 10rem); min-height: 22rem;" id="chat-app">
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-300/50 bg-white/95 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">

        <div class="flex flex-shrink-0 items-center gap-3 border-b border-slate-200/80 p-4">
            <a href="{{ route('messages.index') }}" class="me-1 text-slate-500 transition hover:text-slate-800">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary-100">
                <i class="fas fa-user text-primary-700"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-slate-900">
                    {{ $contact->role === 'doctor' ? 'Dr. ' : '' }}{{ $contact->name }}
                </p>
                <p class="text-xs text-slate-500">
                    @if($lastMessageFromContact)
                        {{ __('messages.last_received', ['time' => $lastMessageFromContact->sent_at->diffForHumans()]) }}
                    @else
                        {{ __('messages.no_outbound_yet') }}
                    @endif
                </p>
            </div>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-4" id="messages-container">
            <div class="space-y-3">
                <div v-for="msg in messages" :key="msg.id"
                     class="flex"
                     :class="msg.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[85%] rounded-2xl px-4 py-2.5 text-sm sm:max-w-md"
                         :class="msg.sender_id === {{ auth()->id() }}
                             ? 'rounded-br-md bg-primary-600 text-white shadow-sm'
                             : 'rounded-bl-md border border-slate-100 bg-slate-100 text-slate-800'">
                        <p class="whitespace-pre-wrap break-words">@{{ msg.content }}</p>
                        <p class="mt-1 text-xs opacity-70">@{{ formatTime(msg.sent_at) }}</p>
                    </div>
                </div>

                <div v-if="isTyping" class="flex justify-start">
                    <div class="flex items-center gap-1 rounded-2xl rounded-bl-md border border-slate-100 bg-slate-100 px-4 py-2.5 text-sm text-slate-500">
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay:0s"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay:0.15s"></span>
                        <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400" style="animation-delay:0.3s"></span>
                    </div>
                </div>

                <div v-if="messages.length === 0" class="py-12 text-center text-slate-400">
                    <i class="fas fa-comments mb-3 text-4xl opacity-50"></i>
                    <p class="text-sm">{{ __('messages.start_conversation') }}</p>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 border-t border-slate-200/80 bg-slate-50/90 p-3 sm:p-4">
            <form @submit.prevent="sendMessage" class="flex gap-2 sm:gap-3">
                <input v-model="newMessage"
                       type="text"
                       autocomplete="off"
                       placeholder="{{ __('messages.placeholder') }}"
                       class="min-h-[2.75rem] flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                       :disabled="sending">
                <button type="submit"
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-sm transition hover:bg-primary-700 disabled:opacity-50 sm:h-11 sm:w-auto sm:px-5"
                        :disabled="!newMessage.trim() || sending"
                        aria-label="{{ __('messages.send') }}">
                    <i class="fas fa-paper-plane text-sm sm:me-2"></i>
                    <span class="hidden sm:inline">{{ __('messages.send') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const pusher = window.MediConnectPusher || new Pusher('{{ config('reverb.apps.apps.0.key', env('REVERB_APP_KEY')) }}', {
    wsHost:           '{{ config('reverb.apps.apps.0.options.host', env('REVERB_HOST', 'localhost')) }}',
    wsPort:           {{ (int) config('reverb.apps.apps.0.options.port', env('REVERB_PORT', 8080)) }},
    wssPort:          {{ (int) config('reverb.apps.apps.0.options.port', env('REVERB_PORT', 8080)) }},
    forceTLS:         false,
    enabledTransports: ['ws', 'wss'],
    cluster:          'mt1',
    authEndpoint:     '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});


const currentUserId = {{ auth()->id() }};
const contactId     = {{ $contact->id }};
const ids = [currentUserId, contactId].sort((a, b) => a - b);
const channelName = `private-chat.${ids[0]}.${ids[1]}`;
const timeLocale = @json(app()->getLocale() === 'ar' ? 'ar' : 'fr-FR');


const { createApp, ref, onMounted, nextTick, onUnmounted } = Vue;

createApp({
    setup() {
        const messages    = ref(@json($messages));
        const newMessage  = ref('');
        const sending     = ref(false);
        const isTyping    = ref(false);

        const scrollToBottom = () => {
            nextTick(() => {
                const c = document.getElementById('messages-container');
                if (c) c.scrollTop = c.scrollHeight;
            });
        };

        const formatTime = (datetime) => {
            if (!datetime) return '';
            return new Date(datetime).toLocaleTimeString(timeLocale, {
                hour: '2-digit', minute: '2-digit'
            });
        };

        let channel = null;

        const subscribeToChannel = () => {
            channel = pusher.subscribe(channelName);

            channel.bind('message.sent', (data) => {
                const alreadyExists = messages.value.some(m => m.id === data.id);
                if (!alreadyExists) {
                    messages.value.push(data);
                    scrollToBottom();
                }
                isTyping.value = false;
            });
        };

        const sendMessage = async () => {
            if (!newMessage.value.trim()) return;
            sending.value = true;

            const content = newMessage.value;
            newMessage.value = '';

            try {
                const res = await fetch('{{ route('messages.send', $contact) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({ content })
                });

                const data = await res.json();

                messages.value.push(data.message);
                scrollToBottom();

            } catch (e) {
                console.error('Erreur envoi message:', e);
                newMessage.value = content;
            } finally {
                sending.value = false;
            }
        };

        onMounted(() => {
            subscribeToChannel();
            scrollToBottom();
        });

        onUnmounted(() => {
            if (channel) pusher.unsubscribe(channelName);
        });

        return {
            messages, newMessage, sending, isTyping,
            sendMessage, formatTime
        };
    }
}).mount('#chat-app');
</script>
@endpush
