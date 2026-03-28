@extends('layouts.app')
@section('title', 'Mon profil médecin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Mon profil</h1>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-5">Informations générales</h2>
        <form method="POST" action="{{ route('doctor.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ $doctor->phone }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="city" value="{{ $doctor->city }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea name="bio" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ $doctor->bio }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Spécialités</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($allSpecialities as $spec)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="specialities[]" value="{{ $spec->id }}"
                               {{ $doctor->specialities->contains($spec->id) ? 'checked' : '' }}
                               class="rounded text-blue-600">
                        {{ $spec->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
                Sauvegarder
            </button>
        </form>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="availabilities()">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-800">Mes disponibilités</h2>
            <button type="button" @click="addSlot()"
                    class="text-blue-600 text-sm font-medium hover:underline flex items-center gap-1">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>

        <form method="POST" action="{{ route('doctor.availabilities.save') }}">
            @csrf
            <div class="space-y-3">
                <template x-for="(slot, i) in slots" :key="i">
                    <div class="flex items-center gap-3">
                        <select :name="`availabilities[${i}][day]`" x-model="slot.day"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="Lundi">Lundi</option>
                            <option value="Mardi">Mardi</option>
                            <option value="Mercredi">Mercredi</option>
                            <option value="Jeudi">Jeudi</option>
                            <option value="Vendredi">Vendredi</option>
                            <option value="Samedi">Samedi</option>
                        </select>
                        <input type="time" :name="`availabilities[${i}][start_time]`" x-model="slot.start_time"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <span class="text-gray-400">—</span>
                        <input type="time" :name="`availabilities[${i}][end_time]`" x-model="slot.end_time"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="button" @click="slots.splice(i, 1)" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </template>
            </div>
            <button type="submit" class="mt-4 bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700 transition">
                Enregistrer les disponibilités
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function availabilities() {
    return {
        slots: @json($doctor->availabilities->map(fn($a) => ['day' => $a->day, 'start_time' => $a->start_time, 'end_time' => $a->end_time])),
        addSlot() {
            this.slots.push({ day: 'Lundi', start_time: '09:00', end_time: '17:00' });
        }
    }
}
</script>
@endpush