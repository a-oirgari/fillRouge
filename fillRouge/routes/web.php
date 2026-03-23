<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/doctors', [PatientController::class, 'searchDoctors'])->name('doctors.search');
Route::get('/doctors/{doctor}', [PatientController::class, 'showDoctor'])->name('doctors.show');


Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
    Route::get('/history', [PatientController::class, 'medicalHistory'])->name('history');
    Route::post('/doctors/{doctor}/book', [PatientController::class, 'bookAppointment'])->name('book');
});


Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::patch('/appointments/{appointment}/accept', [DoctorController::class, 'acceptAppointment'])->name('appointments.accept');
    Route::patch('/appointments/{appointment}/refuse', [DoctorController::class, 'refuseAppointment'])->name('appointments.refuse');
    Route::get('/appointments/{appointment}/consultation', [DoctorController::class, 'showConsultation'])->name('consultation.show');
    Route::post('/appointments/{appointment}/consultation', [DoctorController::class, 'saveDiagnostic'])->name('consultation.save');
    Route::get('/profile', [DoctorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');
    Route::post('/availabilities', [DoctorController::class, 'saveAvailabilities'])->name('availabilities.save');
    Route::get('/patients', [DoctorController::class, 'patientHistory'])->name('patients');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::patch('/doctors/{doctor}/validate', [AdminController::class, 'validateDoctor'])->name('doctors.validate');
    Route::patch('/doctors/{doctor}/invalidate', [AdminController::class, 'invalidateDoctor'])->name('doctors.invalidate');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/specialities', [AdminController::class, 'specialities'])->name('specialities');
    Route::post('/specialities', [AdminController::class, 'storeSpeciality'])->name('specialities.store');
    Route::delete('/specialities/{speciality}', [AdminController::class, 'deleteSpeciality'])->name('specialities.delete');
});


Route::middleware('auth')->prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('index');
    Route::get('/{contact}', [MessageController::class, 'conversation'])->name('conversation');
    Route::post('/{contact}', [MessageController::class, 'send'])->name('send');
    Route::get('/{contact}/fetch', [MessageController::class, 'getMessages'])->name('fetch');
});