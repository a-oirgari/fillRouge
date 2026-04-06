@extends('layouts.app')
@section('title', 'Conversation avec ' . $contact->name)

@section('content')
<div class="max-w-3xl mx-auto" id="chat-app">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: 600px;">

        <!-- Header -->
        <div class="flex items-center gap-3 p-4 border-b border-gray-100">
            <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700 mr-1">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">
                    {{ $contact->role === 'doctor' ? 'Dr. ' : '' }}{{ $contact->name }}
                </p>
                <p class="text-xs text-gray-500 capitalize flex items-center gap-1">
                    <span id="online-dot" class="w-2 h-2 bg-green-400 rounded-full inline-block"></span>
                    En ligne
                </p>
            </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="messages-container">
            <div v-for="msg in messages" :key="msg.id"
                 class="flex"
                 :class="msg.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl text-sm"
                     :class="msg.sender_id === {{ auth()->id() }}
                         ? 'bg-blue-600 text-white rounded-br-sm'
                         : 'bg-gray-100 text-gray-800 rounded-bl-sm'">
                    <p>@{{ msg.content }}</p>
                    <p class="text-xs mt-1 opacity-60">@{{ formatTime(msg.sent_at) }}</p>
                </div>
            </div>

            <!-- Indicateur "en train d'écrire" -->
            <div v-if="isTyping" class="flex justify-start">
                <div class="bg-gray-100 text-gray-500 px-4 py-2 rounded-2xl rounded-bl-sm text-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.3s"></span>
                </div>
            </div>

            <div v-if="messages.length === 0" class="text-center text-gray-400 py-10">
                <i class="fas fa-comment-dots text-4xl mb-3"></i>
                <p>Démarrez la conversation</p>
            </div>
        </div>

        <!-- Zone de saisie -->
        <div class="p-4 border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex gap-3">
                <input v-model="newMessage"
                       type="text"
                       placeholder="Écrire un message..."
                       class="flex-1 border border-gray-300 rounded-full px-5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                       :disabled="sending">
                <button type="submit"
                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition disabled:opacity-50"
                        :disabled="!newMessage.trim() || sending">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Vue 3 --}}
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

{{-- Laravel Echo + Pusher JS (compatible Reverb) --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>

const pusher = new Pusher('{{ env("REVERB_APP_KEY") }}', {
    wsHost:           '{{ env("REVERB_HOST", "localhost") }}',
    wsPort:           {{ env("REVERB_PORT", 8080) }},
    wssPort:          {{ env("REVERB_PORT", 8080) }},
    forceTLS:         false,
    enabledTransports: ['ws', 'wss'],
    cluster:          'mt1', // requis par Pusher JS mais ignoré par Reverb
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


const { createApp, ref, onMounted, nextTick, onUnmounted } = Vue;

createApp({
    setup() {
        const messages    = ref(@json($messages));
        const newMessage  = ref('');
        const sending     = ref(false);
        const isTyping    = ref(false);

        // ---- Helpers ----
        const scrollToBottom = () => {
            nextTick(() => {
                const c = document.getElementById('messages-container');
                if (c) c.scrollTop = c.scrollHeight;
            });
        };

        const formatTime = (datetime) => {
            if (!datetime) return '';
            return new Date(datetime).toLocaleTimeString('fr-FR', {
                hour: '2-digit', minute: '2-digit'
            });
        };

        // ---- WebSocket : écouter les nouveaux messages ----
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

                // Ajouter notre message immédiatement dans l'UI
                messages.value.push(data.message);
                scrollToBottom();

            } catch (e) {
                console.error('Erreur envoi message:', e);
                newMessage.value = content; // Remettre le texte si erreur
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