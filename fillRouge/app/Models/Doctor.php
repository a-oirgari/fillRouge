<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'city', 'bio', 'validated', 'phone', 'photo'];

    protected $casts = [
        'validated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialities()
    {
        return $this->belongsToMany(Speciality::class, 'doctor_speciality');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function pendingAppointments()
    {
        return $this->appointments()->where('status', 'pending');
    }

    public function scopeValidated($query)
    {
        return $query->where('validated', true);
    }

    public function scopeByCity($query, $city)
    {
        if (!$city) {
            return $query;
        }

        if (is_array($city)) {
            return $query->where(function($q) use ($city) {
                foreach ($city as $c) {
                    $q->orWhere('city', 'like', "%{$c}%");
                }
            });
        }

        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeBySpeciality($query, $specialityId)
    {
        return $specialityId ? $query->whereHas('specialities', fn($q) => $q->where('specialities.id', $specialityId)) : $query;
    }
}