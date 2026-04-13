<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\Appointment;
use App\Http\Requests\BookAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $doctors = Doctor::validated()
            ->with(['user', 'specialities', 'availabilities'])
            ->bySpeciality($request->speciality_id)
            ->byCity($request->city)
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

        
        $exists = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'date' => 'Vous avez déjà un rendez-vous à cette date avec ce médecin.',
            ]);
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

    public function appointments()
    {
        $patient = Auth::user()->patient;

        $appointments = $patient->appointments()
            ->with(['doctor.user', 'doctor.specialities', 'consultation'])
            ->orderByDesc('date')
            ->paginate(10);

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