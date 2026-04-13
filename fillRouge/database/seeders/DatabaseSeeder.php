<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\Availability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        User::create([
            'name'     => 'Admin MediConnect',
            'email'    => 'admin@mediconnect.ma',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        
        $specialities = [
            'Médecine générale', 'Cardiologie', 'Dermatologie',
            'Pédiatrie', 'Gynécologie', 'Orthopédie',
            'Ophtalmologie', 'Neurologie', 'Psychiatrie', 'ORL',
        ];
        foreach ($specialities as $name) {
            Speciality::create(['name' => $name]);
        }

        
        $patientUser = User::create([
            'name'     => 'Ahmed Benali',
            'email'    => 'patient@mediconnect.ma',
            'password' => Hash::make('password'),
            'role'     => 'patient',
        ]);
        Patient::create([
            'user_id' => $patientUser->id,
            'phone'   => '0612345678',
            'address' => 'Casablanca, Maroc',
        ]);

        
        $doctorUser = User::create([
            'name'     => 'Dr. Sarah Khalil',
            'email'    => 'doctor@mediconnect.ma',
            'password' => Hash::make('password'),
            'role'     => 'doctor',
        ]);
        $doctor = Doctor::create([
            'user_id'   => $doctorUser->id,
            'city'      => 'Casablanca',
            'bio'       => 'Cardiologue avec 10 ans d\'expérience, spécialisée en maladies cardiovasculaires.',
            'validated' => true,
            'phone'     => '0622334455',
        ]);
        $doctor->specialities()->attach(2); 

        
        foreach (['Lundi', 'Mercredi', 'Vendredi'] as $day) {
            Availability::create([
                'doctor_id'  => $doctor->id,
                'day'        => $day,
                'start_time' => '09:00',
                'end_time'   => '17:00',
            ]);
        }

        $doctorsData = [
            [
                'name' => 'Dr. Amine El Fassi',
                'email' => 'amine@mediconnect.ma',
                'city' => 'Rabat',
                'bio' => 'Dermatologue passionné par les maladies de la peau et l\'esthétique.',
                'phone' => '0633445566',
                'speciality_id' => 3, 
                'days' => ['Mardi', 'Jeudi', 'Samedi']
            ],
            [
                'name' => 'Dr. Fatima Zohra',
                'email' => 'fatima@mediconnect.ma',
                'city' => 'Marrakech',
                'bio' => 'Pédiatre attentionnée, accompagnant le développement des enfants depuis plus de 15 ans.',
                'phone' => '0644556677',
                'speciality_id' => 4, 
                'days' => ['Lundi', 'Mardi', 'Jeudi', 'Vendredi']
            ],
            [
                'name' => 'Dr. Youssef Berrada',
                'email' => 'youssef@mediconnect.ma',
                'city' => 'Tanger',
                'bio' => 'Ophtalmologue chirurgien, spécialiste des cataractes et la chirurgie réfractive.',
                'phone' => '0655667788',
                'speciality_id' => 7, 
                'days' => ['Lundi', 'Mercredi']
            ],
            [
                'name' => 'Dr. Leila Mansouri',
                'email' => 'leila@mediconnect.ma',
                'city' => 'Casablanca',
                'bio' => 'Médecin généraliste assurant des consultations globales et le suivi des patients.',
                'phone' => '0666778899',
                'speciality_id' => 1, 
                'days' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi']
            ],
        ];

        foreach ($doctorsData as $data) {
            $docUser = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'doctor',
            ]);
            $doc = Doctor::create([
                'user_id'   => $docUser->id,
                'city'      => $data['city'],
                'bio'       => $data['bio'],
                'validated' => true,
                'phone'     => $data['phone'],
            ]);
            $doc->specialities()->attach($data['speciality_id']);

            foreach ($data['days'] as $day) {
                Availability::create([
                    'doctor_id'  => $doc->id,
                    'day'        => $day,
                    'start_time' => '09:00',
                    'end_time'   => '17:00',
                ]);
            }
        }

        $this->command->info(' Seeder terminé!');
        $this->command->info('Admin: admin@mediconnect.ma / password');
        $this->command->info('Patient: patient@mediconnect.ma / password');
        $this->command->info('Médecin: doctor@mediconnect.ma / password');
        $this->command->info('Autres médecins: amine@..., fatima@..., youssef@..., leila@... / password');
    }
}