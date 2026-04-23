<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\Appointment;
use App\Http\Requests\BookAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Str;
use \Illuminate\Support\Carbon;

class PatientController extends Controller
{
    public function dashboard()
    {
        $patient = Auth::user()->patient;

        $upcomingAppointments = $patient->appointments()
            ->with(['doctor.user', 'doctor.specialities'])
            ->whereIn('status', ['pending', 'accepted'])
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        $recentHistory = $patient->appointments()
            ->with(['doctor.user', 'consultation.prescription'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('upcomingAppointments', 'recentHistory'));
    }

    public function searchDoctors(Request $request)
    {
        $specialities = Speciality::all();
        $cities = Doctor::validated()->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city')->sort();

        $searchCity = $request->city;
        if ($searchCity) {
            $langCities = trans('cities');
            if (is_array($langCities)) {
                $matchedKeys = [];
                foreach ($langCities as $key => $translation) {
                    if (Str::contains($translation, $searchCity, true)) {
                        $matchedKeys[] = $key;
                    }
                }
                
                if (!empty($matchedKeys)) {
                    $matchedKeys[] = $searchCity;
                    $searchCity = $matchedKeys;
                }
            }
        }

        $doctors = Doctor::validated()
            ->with(['user', 'specialities', 'availabilities'])
            ->bySpeciality($request->speciality_id)
            ->byCity($searchCity)
            ->paginate(12);

        return view('patient.search', compact('doctors', 'specialities', 'cities'));
    }

    public function showDoctor(Doctor $doctor)
    {
        if (!$doctor->validated) {
            abort(404);
        }
        $doctor->load(['user', 'specialities', 'availabilities']);
        return view('patient.doctor-profile', compact('doctor'));
    }

    public function bookAppointment(BookAppointmentRequest $request, Doctor $doctor)
    {
        $patient = Auth::user()->patient;

        
        $isBooked = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($isBooked) {
             return back()->withErrors([
                 'date' => "Ce médecin a déjà un rendez-vous planifié à cette heure. Veuillez choisir un autre créneau.",
             ])->withInput();
        }

        
        $carbonDate = Carbon::parse($request->date);
        
        $dayNames = [
            1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi',
            4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'
        ];
        $requestedDay = $dayNames[$carbonDate->dayOfWeekIso];
        $startTime = $carbonDate->format('H:i:s');
        $endTime = $carbonDate->copy()->addMinutes(15)->format('H:i:s');

        $isAvailable = $doctor->availabilities()
             ->where('day', $requestedDay)
             ->where('start_time', '<=', $startTime)
             ->where('end_time', '>=', $endTime)
             ->exists();

        
        if ($doctor->availabilities()->count() > 0 && !$isAvailable) {
             return back()->withErrors([
                 'date' => "Le médecin ne consulte pas à cette heure-là le " . $requestedDay . ".",
             ])->withInput();
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id'  => $doctor->id,
            'date'       => $request->date,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return redirect()->route('patient.appointments')
            ->with('success', 'Rendez-vous demandé avec succès !');
    }

    public function appointments(Request $request)
    {
        $patient = Auth::user()->patient;

        $query = $patient->appointments()
            ->with(['doctor.user', 'doctor.specialities', 'consultation']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest('created_at')->paginate(10);

        return view('patient.appointments', compact('appointments'));
    }

    public function medicalHistory()
    {
        $patient = Auth::user()->patient;

        $history = $patient->appointments()
            ->with(['doctor.user', 'consultation.prescription'])
            ->where('status', 'completed')
            ->orderByDesc('date')
            ->paginate(10);

        return view('patient.history', compact('history'));
    }
}