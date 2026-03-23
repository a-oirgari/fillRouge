<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($user->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);
        } elseif ($user->role === 'doctor') {
            $doctor = Doctor::create([
                'user_id'   => $user->id,
                'city'      => $request->city,
                'bio'       => $request->bio,
                'validated' => false,
            ]);

            if ($request->specialities) {
                $doctor->specialities()->sync($request->specialities);
            }
        }

        Auth::login($user);

        return redirect()->route($this->redirectByRole($user->role));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route($this->redirectByRole(Auth::user()->role));
        }

        return back()
            ->withErrors(['email' => 'Identifiants incorrects.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function redirectByRole(string $role): string
    {
        return match($role) {
            'admin'  => 'admin.dashboard',
            'doctor' => 'doctor.dashboard',
            default  => 'patient.dashboard',
        };
    }
}