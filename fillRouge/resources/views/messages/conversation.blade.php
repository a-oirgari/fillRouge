@extends('layouts.app')
@section('title', 'Conversation avec ' . $contact->name)

@section('content')
<div class="max-w-3xl mx-auto" id="chat-app">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: 600px;">
        
        <div class="flex items-center gap-3 p-4 border-b border-gray-100">
            <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700 mr-1">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $contact->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ $contact->role }}</p>
            </div>
        </div>

        
        <div class="flex-1 overflow-y-auto p-4 space-y-3" ref="messagesContainer" id="messages-container">
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

            <div v-if="messages.length === 0" class="text-center text-gray-400 py-10">
                <i class="fas fa-comment-dots text-4xl mb-3"></i>
                <p>Démarrez la conversation</p>
            </div>
        </div>

        
        <div class="p-4 border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex gap-3">
                <input v-model="newMessage" type="text"
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
<script>
const { createApp, ref, onMounted, nextTick } = Vue;

createApp({
    setup() {
        const messages = ref(@json($messages));
        const newMessage = ref('');
        const sending = ref(false);
        const messagesContainer = ref(null);

        const scrollToBottom = () => {
            nextTick(() => {
                const container = document.getElementById('messages-container');
                if (container) container.scrollTop = container.scrollHeight;
            });
        };

        const formatTime = (datetime) => {
            return new Date(datetime).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        };

        const fetchMessages = async () => {
            try {
                const res = await fetch('{{ route('messages.fetch', $contact) }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.length !== messages.value.length) {
                    messages.value = data;
                    scrollToBottom();
                }
            } catch (e) {}
        };

        const sendMessage = async () => {
            if (!newMessage.value.trim()) return;
            sending.value = true;

            try {
                const res = await fetch('{{ route('messages.send', $contact) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content: newMessage.value })
                });

                const data = await res.json();
                messages.value.push(data.message);
                newMessage.value = '';
                scrollToBottom();
            } catch (e) {
                console.error(e);
            } finally {
                sending.value = false;
            }
        };

        onMounted(() => {
            scrollToBottom();
            
            setInterval(fetchMessages, 3000);
        });

        return { messages, newMessage, sending, sendMessage, formatTime, messagesContainer };
    }
}).mount('#chat-app');
</script>
@endpush