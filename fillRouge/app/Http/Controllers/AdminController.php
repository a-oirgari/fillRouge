<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Speciality;
use App\Http\Requests\StoreSpecialityRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'             => User::count(),
            'total_patients'          => Patient::count(),
            'total_doctors'           => Doctor::count(),
            'validated_doctors'       => Doctor::validated()->count(),
            'pending_doctors'         => Doctor::where('validated', false)->count(),
            'total_appointments'      => Appointment::count(),
            'pending_appointments'    => Appointment::where('status', 'pending')->count(),
            'completed_consultations' => Appointment::where('status', 'completed')->count(),
        ];

        $recentUsers    = User::latest()->take(5)->get();
        $pendingDoctors = Doctor::where('validated', false)->with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'pendingDoctors'));
    }

    public function doctors(Request $request)
    {
        $query = Doctor::with(['user', 'specialities']);

        if ($request->validated !== null) {
            $query->where('validated', $request->validated);
        }

        $doctors = $query->latest()->paginate(15);
        return view('admin.doctors', compact('doctors'));
    }

    public function validateDoctor(Doctor $doctor)
    {
        $doctor->update(['validated' => true]);
        return back()->with('success', "Dr. {$doctor->user->name} a été validé.");
    }

    public function invalidateDoctor(Doctor $doctor)
    {
        $doctor->update(['validated' => false]);
        return back()->with('success', "Dr. {$doctor->user->name} a été désactivé.");
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Impossible de supprimer un administrateur.']);
        }
        $user->delete();
        return back()->with('success', 'Compte supprimé avec succès.');
    }

    public function statistics()
    {
        $appointmentsByMonth = Appointment::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $specialityStats = Speciality::withCount(['doctors' => fn($q) => $q->validated()])->get();

        $stats = [
            'appointments_by_month' => $appointmentsByMonth,
            'speciality_stats'      => $specialityStats,
            'top_doctors'           => Doctor::withCount('appointments')
                                        ->validated()
                                        ->orderByDesc('appointments_count')
                                        ->take(5)
                                        ->with('user')
                                        ->get(),
        ];

        return view('admin.statistics', compact('stats'));
    }

    public function specialities()
    {
        $specialities = Speciality::withCount('doctors')->paginate(20);
        return view('admin.specialities', compact('specialities'));
    }

    public function storeSpeciality(StoreSpecialityRequest $request)
    {
        Speciality::create(['name' => $request->name]);
        return back()->with('success', 'Spécialité ajoutée.');
    }

    public function deleteSpeciality(Speciality $speciality)
    {
        $speciality->delete();
        return back()->with('success', 'Spécialité supprimée.');
    }
}