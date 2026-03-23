<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Speciality;
use App\Http\Requests\SaveDiagnosticRequest;
use App\Http\Requests\UpdateDoctorProfileRequest;
use App\Http\Requests\SaveAvailabilitiesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;

        $pendingAppointments = $doctor->appointments()
            ->pending()
            ->with(['patient.user'])
            ->orderBy('date')
            ->get();

        $todayAppointments = $doctor->appointments()
            ->accepted()
            ->whereDate('date', today())
            ->with(['patient.user'])
            ->orderBy('date')
            ->get();

        $stats = [
            'total_appointments' => $doctor->appointments()->count(),
            'pending'            => $doctor->appointments()->where('status', 'pending')->count(),
            'completed'          => $doctor->appointments()->where('status', 'completed')->count(),
        ];

        return view('doctor.dashboard', compact('pendingAppointments', 'todayAppointments', 'stats'));
    }

    public function appointments(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $query  = $doctor->appointments()->with(['patient.user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderByDesc('date')->paginate(15);

        return view('doctor.appointments', compact('appointments'));
    }

    public function acceptAppointment(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->accept();
        return back()->with('success', 'Rendez-vous accepté.');
    }

    public function refuseAppointment(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->refuse();
        return back()->with('success', 'Rendez-vous refusé.');
    }

    public function showConsultation(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->load(['patient.user', 'consultation.prescription']);
        return view('doctor.consultation', compact('appointment'));
    }

    public function saveDiagnostic(SaveDiagnosticRequest $request, Appointment $appointment)
    {
        
        $consultation = $appointment->consultation()->updateOrCreate(
            ['appointment_id' => $appointment->id],
            ['diagnostic' => $request->diagnostic]
        );

        if ($request->filled('prescription')) {
            $consultation->prescription()->updateOrCreate(
                ['consultation_id' => $consultation->id],
                ['content' => $request->prescription]
            );
        }

        $appointment->complete();

        return redirect()->route('doctor.appointments')
            ->with('success', 'Consultation enregistrée avec succès.');
    }

    public function profile()
    {
        $doctor          = Auth::user()->doctor->load(['specialities', 'availabilities']);
        $allSpecialities = Speciality::all();
        return view('doctor.profile', compact('doctor', 'allSpecialities'));
    }

    public function updateProfile(UpdateDoctorProfileRequest $request)
    {
        $doctor = Auth::user()->doctor;

        $doctor->update([
            'city'  => $request->city,
            'bio'   => $request->bio,
            'phone' => $request->phone,
        ]);

        if ($request->has('specialities')) {
            $doctor->specialities()->sync($request->specialities);
        }

        Auth::user()->update(['name' => $request->name]);

        return back()->with('success', 'Profil mis à jour.');
    }

    public function saveAvailabilities(SaveAvailabilitiesRequest $request)
    {
        $doctor = Auth::user()->doctor;
        $doctor->availabilities()->delete();

        foreach ($request->availabilities ?? [] as $avail) {
            $doctor->availabilities()->create($avail);
        }

        return back()->with('success', 'Disponibilités mises à jour.');
    }

    public function patientHistory()
    {
        $doctor = Auth::user()->doctor;

        $patients = $doctor->appointments()
            ->where('status', 'completed')
            ->with(['patient.user', 'consultation.prescription'])
            ->orderByDesc('date')
            ->paginate(15);

        return view('doctor.patient-history', compact('patients'));
    }

    private function authorizeAppointment(Appointment $appointment): void
    {
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }
    }
}