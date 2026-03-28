# MediConnect — Plateforme de Téléconsultation Médicale

## Installation rapide

```bash
# 1. Créer le projet Laravel
composer create-project laravel/laravel mediconnect
cd mediconnect

# 2. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mediconnect
DB_USERNAME=root
DB_PASSWORD=

# 3. Lancer les migrations et le seeder
php artisan migrate
php artisan db:seed

# 4. Démarrer le serveur
php artisan serve
```

## Comptes de test

| Rôle       | Email                      | Mot de passe |
|------------|----------------------------|--------------|
| Admin      | admin@mediconnect.ma       | password     |
| Patient    | patient@mediconnect.ma     | password     |
| Médecin    | doctor@mediconnect.ma      | password     |

---

## Structure complète du projet

```
mediconnect/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Inscription, connexion, déconnexion
│   │   │   ├── PatientController.php       ← Dashboard patient, recherche, RDV, historique
│   │   │   ├── DoctorController.php        ← Dashboard médecin, RDV, consultations, profil
│   │   │   ├── AdminController.php         ← Dashboard admin, validation médecins, stats
│   │   │   └── MessageController.php       ← Chat entre utilisateurs
│   │   │
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php          ← Protège les routes selon le rôle (patient/doctor/admin)
│   │   │
│   │   └── Requests/                       ← Validation des formulaires (Form Requests)
│   │       ├── RegisterRequest.php         ← Validation inscription
│   │       ├── LoginRequest.php            ← Validation connexion
│   │       ├── BookAppointmentRequest.php  ← Validation prise de RDV
│   │       ├── SaveDiagnosticRequest.php   ← Validation diagnostic médecin
│   │       ├── UpdateDoctorProfileRequest.php ← Validation profil médecin
│   │       ├── SaveAvailabilitiesRequest.php  ← Validation disponibilités
│   │       ├── SendMessageRequest.php      ← Validation envoi message
│   │       └── StoreSpecialityRequest.php  ← Validation ajout spécialité (admin)
│   │
│   └── Models/
│       ├── User.php            ← Utilisateur (patient / doctor / admin)
│       ├── Patient.php         ← Profil patient (phone, address)
│       ├── Doctor.php          ← Profil médecin (city, bio, validated)
│       ├── Speciality.php      ← Spécialité médicale
│       ├── Availability.php    ← Disponibilités du médecin (jour, heure)
│       ├── Appointment.php     ← Rendez-vous (pending/accepted/refused/completed)
│       ├── Consultation.php    ← Consultation (diagnostic)
│       ├── Prescription.php    ← Ordonnance
│       └── Message.php         ← Messages entre utilisateurs
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_specialities_table.php
│   │   ├── 2024_01_01_000003_create_patients_table.php
│   │   ├── 2024_01_01_000004_create_doctors_table.php
│   │   ├── 2024_01_01_000005_create_doctor_speciality_table.php
│   │   ├── 2024_01_01_000006_create_availabilities_table.php
│   │   ├── 2024_01_01_000007_create_appointments_table.php
│   │   ├── 2024_01_01_000008_create_consultations_table.php
│   │   ├── 2024_01_01_000009_create_prescriptions_table.php
│   │   └── 2024_01_01_000010_create_messages_table.php
│   │
│   └── seeders/
│       └── DatabaseSeeder.php  ← Données de test (admin + patient + médecin validé)
│
├── routes/
│   └── web.php                 ← Toutes les routes (protégées par rôle)
│
├── bootstrap/
│   └── app.php                 ← Enregistrement du RoleMiddleware (remplace le fichier original)
│
└── resources/
    └── views/                  ← TOUS LES FICHIERS BLADE ICI
        │
        ├── welcome.blade.php   ← Page d'accueil publique (REMPLACE le fichier Laravel par défaut)
        │
        ├── layouts/
        │   └── app.blade.php   ← Layout principal avec navbar (utilisé par toutes les autres vues)
        │
        ├── auth/
        │   ├── login.blade.php     ← Page de connexion
        │   └── register.blade.php  ← Page d'inscription (patient ou médecin)
        │
        ├── patient/            ← Vues accessibles uniquement au patient connecté
        │   ├── dashboard.blade.php     ← Dashboard patient : prochains RDV + historique récent
        │   ├── search.blade.php        ← Recherche de médecins (filtre spécialité + ville)
        │   ├── doctor-profile.blade.php← Profil d'un médecin + formulaire de prise de RDV
        │   ├── appointments.blade.php  ← Liste de tous ses rendez-vous avec filtre par statut
        │   └── history.blade.php       ← Historique médical complet (diagnostics + ordonnances)
        │
        ├── doctor/             ← Vues accessibles uniquement au médecin connecté
        │   ├── dashboard.blade.php     ← Dashboard médecin : stats + demandes en attente + RDV du jour
        │   ├── appointments.blade.php  ← Liste de ses RDV avec actions (accepter/refuser/consulter)
        │   ├── consultation.blade.php  ← Formulaire diagnostic + ordonnance pour un RDV
        │   ├── profile.blade.php       ← Gestion profil médecin + disponibilités
        │   └── patient-history.blade.php ← Historique de tous les patients consultés
        │
        ├── admin/              ← Vues accessibles uniquement à l'administrateur
        │   ├── dashboard.blade.php     ← Dashboard admin : stats globales + médecins à valider
        │   ├── doctors.blade.php       ← Liste des médecins avec validation/désactivation
        │   ├── users.blade.php         ← Liste des utilisateurs avec recherche et suppression
        │   ├── statistics.blade.php    ← Graphiques : RDV par mois, top médecins, spécialités
        │   └── specialities.blade.php  ← CRUD des spécialités médicales
        │
        └── messages/           ← Vues accessibles à tous les utilisateurs connectés
            ├── index.blade.php         ← Liste de toutes les conversations
            └── conversation.blade.php  ← Chat avec un contact (polling Vue.js toutes les 3s)
```

---

## Correspondance Controllers → Vues

### AuthController
| Méthode           | Vue retournée              |
|-------------------|----------------------------|
| showRegister()    | auth/register.blade.php    |
| showLogin()       | auth/login.blade.php       |
| login() réussi    | Redirige vers dashboard selon rôle |

### PatientController  (route prefix: /patient)
| Méthode           | URL                        | Vue retournée                    |
|-------------------|----------------------------|----------------------------------|
| dashboard()       | /patient/dashboard         | patient/dashboard.blade.php      |
| searchDoctors()   | /doctors                   | patient/search.blade.php         |
| showDoctor()      | /doctors/{doctor}          | patient/doctor-profile.blade.php |
| appointments()    | /patient/appointments      | patient/appointments.blade.php   |
| medicalHistory()  | /patient/history           | patient/history.blade.php        |

### DoctorController  (route prefix: /doctor)
| Méthode              | URL                               | Vue retournée                      |
|----------------------|-----------------------------------|------------------------------------|
| dashboard()          | /doctor/dashboard                 | doctor/dashboard.blade.php         |
| appointments()       | /doctor/appointments              | doctor/appointments.blade.php      |
| showConsultation()   | /doctor/appointments/{id}/consultation | doctor/consultation.blade.php |
| profile()            | /doctor/profile                   | doctor/profile.blade.php           |
| patientHistory()     | /doctor/patients                  | doctor/patient-history.blade.php   |

### AdminController  (route prefix: /admin)
| Méthode           | URL                        | Vue retournée                  |
|-------------------|----------------------------|--------------------------------|
| dashboard()       | /admin/dashboard           | admin/dashboard.blade.php      |
| doctors()         | /admin/doctors             | admin/doctors.blade.php        |
| users()           | /admin/users               | admin/users.blade.php          |
| statistics()      | /admin/statistics          | admin/statistics.blade.php     |
| specialities()    | /admin/specialities        | admin/specialities.blade.php   |

### MessageController  (route prefix: /messages)
| Méthode           | URL                        | Vue retournée                  |
|-------------------|----------------------------|--------------------------------|
| index()           | /messages                  | messages/index.blade.php       |
| conversation()    | /messages/{contact}        | messages/conversation.blade.php|

---

## Ce que fait chaque dashboard (pour ne pas les confondre)

### patient/dashboard.blade.php
- Affiche "Bonjour, [nom du patient]"
- Section "Prochains rendez-vous" : liste les RDV pending/accepted à venir
- Section "Historique récent" : 5 dernières consultations terminées
- Bouton "Prendre un rendez-vous" → redirige vers /doctors

### doctor/dashboard.blade.php
- Affiche "Bonjour, Dr. [nom du médecin]"
- Bandeau orange si le compte n'est pas encore validé par l'admin
- 3 cartes stats : Total RDV / En attente / Consultations terminées
- Section "Demandes en attente" : boutons Accepter / Refuser
- Section "Rendez-vous d'aujourd'hui" : bouton "Démarrer consultation"

### admin/dashboard.blade.php
- Affiche "Tableau de bord Admin"
- 4 cartes stats : Utilisateurs / Patients / Médecins validés / En attente
- Section "Médecins à valider" : bouton Valider
- Section "Nouveaux inscrits" : 5 derniers comptes créés
- 4 raccourcis rapides : Gérer médecins / Gérer utilisateurs / Statistiques / Spécialités

---

