@extends('layouts.app')
@section('title', 'Mon profil médecin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Mon profil</h1>
            <p class="text-slate-500 mt-1">Gérez vos informations personnelles et vos disponibilités.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white p-8 text-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-white opacity-50 z-0"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 p-1 mb-4 shadow-lg shadow-primary-500/30 transform transition duration-500 group-hover:scale-105">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-5xl text-primary-600 font-bold overflow-hidden border-4 border-white">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium mt-1 mb-4 flex items-center gap-1.5 justify-center">
                        <i class="fas fa-stethoscope text-primary-500 text-xs"></i> Médecin
                    </p>
                    <div class="w-full flex justify-center gap-2 flex-wrap mt-2">
                        @foreach($doctor->specialities as $spec)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100 shadow-sm">
                                {{ $spec->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-lg shadow-slate-200/50 border border-white p-6">
                 <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-primary-500"></i> En bref</h3>
                 <ul class="space-y-3 text-sm">
                     <li class="flex items-center justify-between"><span class="text-slate-500">Email</span> <span class="font-medium text-slate-700 truncate ml-2">{{ auth()->user()->email }}</span></li>
                     <li class="flex items-center justify-between"><span class="text-slate-500">Ville</span> <span class="font-medium text-slate-700">{{ $doctor->city ?: 'Non renseigné' }}</span></li>
                     <li class="flex items-center justify-between"><span class="text-slate-500">Statut</span> {!! $doctor->validated ? '<span class="text-emerald-600 font-medium flex items-center gap-1"><i class="fas fa-check-circle"></i> Validé</span>' : '<span class="text-amber-500 font-medium flex items-center gap-1"><i class="fas fa-clock"></i> En attente</span>' !!}</li>
                 </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Informations générales -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-user-edit text-primary-500"></i> Informations générales
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ route('doctor.profile.update') }}" class="space-y-6">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-slate-700">Nom complet</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-slate-400"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                           class="w-full pl-10 border border-slate-300 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition duration-200 shadow-sm">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-slate-700">Téléphone</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-slate-400"></i>
                                    </div>
                                    <input type="text" name="phone" value="{{ $doctor->phone }}"
                                           class="w-full pl-10 border border-slate-300 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition duration-200 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-slate-700">Ville</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-slate-400"></i>
                                </div>
                                <input type="text" name="city" value="{{ $doctor->city }}"
                                       class="w-full pl-10 border border-slate-300 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition duration-200 shadow-sm">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-slate-700">Biographie</label>
                            <textarea name="bio" rows="4"
                                      class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none resize-none transition duration-200 shadow-sm placeholder-slate-400" placeholder="Parlez-nous de votre expérience et de votre parcours...">{{ $doctor->bio }}</textarea>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-slate-700">Spécialités</label>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 shadow-sm inset-shadow">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($allSpecialities as $spec)
                                    <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-white transition hover:shadow-sm cursor-pointer group">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="specialities[]" value="{{ $spec->id }}"
                                                {{ $doctor->specialities->contains($spec->id) ? 'checked' : '' }}
                                                class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500 transition duration-150">
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium text-slate-700 group-hover:text-primary-700 transition">{{ $spec->name }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end flex-col sm:flex-row gap-3">
                            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-primary-600 to-primary-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-primary-500/50">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Availabilities -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden" x-data="availabilities()">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary-500"></i> Mes disponibilités
                    </h2>
                    <button type="button" @click="addSlot()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg text-sm font-bold shadow-sm hover:bg-primary-200 transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1">
                        <i class="fas fa-plus"></i> Ajouter 
                    </button>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('doctor.availabilities.save') }}">
                        @csrf
                        
                        <template x-if="slots.length === 0">
                            <div class="text-center py-10 bg-slate-50/50 rounded-2xl border border-dashed border-slate-300">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100">
                                    <i class="fas fa-clock text-slate-400 text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-1">Aucune disponibilité</h3>
                                <p class="text-xs text-slate-500 mb-4">Ajoutez des créneaux pour que les patients puissent prendre rendez-vous.</p>
                                <button type="button" @click="addSlot()" class="text-sm font-medium text-primary-600 hover:text-primary-700 p-2 border border-primary-100 bg-white rounded-lg shadow-sm">Ajouter maintenant</button>
                            </div>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(slot, i) in slots" :key="i">
                                <div class="flex flex-col sm:flex-row items-center gap-3 bg-slate-50 border border-slate-200 p-3 sm:p-4 rounded-2xl transition-all duration-300 hover:shadow-md hover:border-primary-200 group">
                                    
                                    <div class="flex-grow w-full sm:w-auto relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-day text-slate-400"></i>
                                        </div>
                                        <select :name="`availabilities[${i}][day]`" x-model="slot.day"
                                                class="w-full pl-9 pr-8 border border-slate-300 rounded-xl py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white shadow-sm appearance-none">
                                            <option value="Lundi">Lundi</option>
                                            <option value="Mardi">Mardi</option>
                                            <option value="Mercredi">Mercredi</option>
                                            <option value="Jeudi">Jeudi</option>
                                            <option value="Vendredi">Vendredi</option>
                                            <option value="Samedi">Samedi</option>
                                            <option value="Dimanche">Dimanche</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex-grow w-full sm:w-auto relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-hourglass-start text-slate-400"></i>
                                        </div>
                                        <input type="time" :name="`availabilities[${i}][start_time]`" x-model="slot.start_time"
                                               class="w-full pl-9 pr-3 border border-slate-300 rounded-xl py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white shadow-sm">
                                    </div>
                                    
                                    <div class="hidden sm:block text-slate-400 font-medium px-1">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                    <div class="block sm:hidden text-slate-400 font-medium py-1">
                                        <i class="fas fa-arrow-down"></i>
                                    </div>

                                    <div class="flex-grow w-full sm:w-auto relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-hourglass-end text-slate-400"></i>
                                        </div>
                                        <input type="time" :name="`availabilities[${i}][end_time]`" x-model="slot.end_time"
                                               class="w-full pl-9 pr-3 border border-slate-300 rounded-xl py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white shadow-sm">
                                    </div>
                                    
                                    <button type="button" @click="slots.splice(i, 1)" 
                                            class="mt-2 sm:mt-0 w-full sm:w-11 h-11 flex items-center justify-center rounded-xl bg-white text-red-500 hover:bg-red-500 hover:text-white transition-colors border border-slate-200 hover:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm" title="Supprimer ce créneau">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="block sm:hidden ml-2 font-semibold text-sm">Supprimer</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                        
                        <div class="pt-6 flex justify-end border-t border-slate-100 mt-6 gap-3 flex-col sm:flex-row">
                            <button type="submit" class="w-full sm:w-auto bg-slate-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-slate-300 hover:bg-slate-900 hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-slate-500/50">
                                Mettre à jour l'agenda
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@php
    $slotsData = $doctor->availabilities->map(function($a) {
        return [
            'day' => $a->day, 
            'start_time' => substr($a->start_time, 0, 5), 
            'end_time' => substr($a->end_time, 0, 5)
        ];
    })->values();
@endphp
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('availabilities', () => ({
        slots: @json($slotsData),
        addSlot() {
            this.slots.push({ day: 'Lundi', start_time: '09:00', end_time: '17:00' });
        }
    }))
})
</script>
@endpush