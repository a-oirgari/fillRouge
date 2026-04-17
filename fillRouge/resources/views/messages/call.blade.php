@extends('layouts.app')
@section('title', __('app.messages.video_call') . ' - ' . $contact->name)

@section('content')
<div class="mx-auto flex w-full max-w-5xl flex-col items-center" style="height: calc(100vh - 10rem); min-height: 30rem;">
    <!-- Container using full size, minus small padding -->
    <div class="flex h-full w-full flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        
        <!-- Header with back button -->
        <div class="flex flex-shrink-0 items-center justify-between border-b border-slate-100 p-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('messages.conversation', $contact) }}" class="mr-1 flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-800">
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
                        {{ __('app.messages.video_call') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ZegoCloud Call Interface Container -->
        <div class="min-h-0 flex-1 bg-slate-900 relative">
            <div id="root" class="absolute inset-0 h-full w-full"></div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
<script>
window.onload = function () {
    // Generate a Kit Token using credentials from .env
    const appID = {{ config('services.zegocloud.app_id') ?? 'null' }};
    const serverSecret = "{{ config('services.zegocloud.server_secret') }}";
    const roomID = "{{ $roomID }}";
    const userID = "{{ auth()->id() }}";
    const userName = "{{ auth()->user()->name }}";

    if (!appID || !serverSecret) {
        console.error("ZegoCloud credentials are not configured in .env.");
        alert("ZegoCloud credentials are not configured in .env.");
        return;
    }

    const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(
        appID, 
        serverSecret, 
        roomID, 
        userID, 
        userName
    );

    // Create ZegoCloud instance
    const zp = ZegoUIKitPrebuilt.create(kitToken);

    // Join room
    zp.joinRoom({
        container: document.querySelector('#root'),
        sharedLinks: [],
        scenario: {
            mode: ZegoUIKitPrebuilt.OneONoneCall, // 1-on-1 Call
        },
        showRoomTimer: true,
        showPreJoinView: false, // join directly
        onLeaveRoom: () => {
             @if(isset($appointment) && $appointment)
                 window.location.href = "{{ route('doctor.consultation.show', $appointment->id) }}";
             @else
                 window.location.href = "{{ route('messages.conversation', $contact) }}";
             @endif
        }
    });
}
</script>
@endpush
