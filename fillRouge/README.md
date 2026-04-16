# MediConnect — Plateforme de Téléconsultation Médicale 🩺

**MediConnect** est une solution complète de gestion médicale, permettant de fluidifier la mise en relation, la prise de rendez-vous et le dialogue à distance entre les patients et les médecins. Construit avec des technologies web de pointe, il propose une expérience temps-réel (messagerie et vidéo) dans un flux métier strict et sécurisé.

---

## 🛠 Technologies & Packages Utilisés

- **Backend** : Laravel 11, PHP 8.2+
- **Frontend** : Tailwind CSS, Alpine.js, Blade Templates
- **Base de données** : MySQL
- **Temps-réel (WebSockets)** : Laravel Reverb & Pusher-JS (Messagerie instantanée)
- **Appels Audio / Vidéo** : ZegoCloud UIKit Prebuilt (WebRTC)
- **Framework JS (Composants isolés)** : Vue.js 3 (utilisé pour l'interface de chat fluide)

---

## ✨ Fonctionnalités Principales

- **Système de Réservation Intelligent** : Contrôle strict des créneaux de 30 minutes, vérifications sécurisées anti-doublons, et croisement des créneaux choisis avec l'agenda ("Disponibilités") du médecin sélectionné.
- **Messagerie Instantanée (Laravel Reverb)** : Chat en temps réel, notifications de saisie, accusés de lecture, et intégration WebSockets pour éliminer le besoin de rafraîchir.
- **Téléconsultation Audio/Vidéo** : Solution vidéo 1-à-1 HD ultra-basse latence embarquée directement dans le chat, générée grâce à ZegoCloud.
- **Dossiers & Historique Médical** : Génération d'ordonnances (par les médecins) et conservation sécurisée de l'historique chirurgical et des prescriptions.
- **Interfaces Personnalisées Multi-Rôles** : Tableaux de bord dynamiques spécifiques aux administrateurs (gestion et validations), docteurs (calendrier et patients) et patients.
- **Recherche Instantanée Live** : Auto-complétion et tri instantané dynamique pour chercher des villes ou des spécialistes.

---

## 🚀 Installation & Déploiement

```bash
# 1. Cloner ou initialiser le projet Laravel
git clone <votre_depot>

# 2. Installer les dépendances
composer install
npm install && npm run build

# 3. Configurer l'environnement environnement
cp .env.example .env
php artisan key:generate

# 4. Bases de données & Services tiers (Mettre à jour .env)
# Assurez-vous de modifier ces valeurs :
DB_CONNECTION=mysql
DB_DATABASE=mediconnect

# Configurer Reverb pour le chat temps réel
BROADCAST_CONNECTION=reverb

# Configurer ZegoCloud pour la visio
ZEGOCLOUD_APP_ID=VOTRE_APP_ID
ZEGOCLOUD_SERVER_SECRET=VOTRE_SERVER_SECRET

# 5. Lancer les migrations et le seeder
php artisan migrate --seed

# 6. Démarrer les serveurs (nécessite 2 terminaux)
php artisan serve
php artisan reverb:start
```

### Comptes de test (Via Seeders)

| Rôle       | Email                      | Mot de passe |
|------------|----------------------------|--------------|
| Admin      | admin@mediconnect.ma       | password     |
| Patient    | patient@mediconnect.ma     | password     |
| Médecin    | doctor@mediconnect.ma      | password     |

---

## 📂 Structure Complète du Projet

Voici l'architecture MVC simplifiée des dossiers vitaux de l'application :

```
mediconnect/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Authentification & routing de session
│   │   │   ├── PatientController.php       ← Logique de réservation, dashboard patient
│   │   │   ├── DoctorController.php        ← Logique de disponibilité, consultations
│   │   │   ├── AdminController.php         ← Back-office admin, KPIs
│   │   │   └── MessageController.php       ← Messagerie et génération des rooms vidéos
│   │   │
│   │   ├── Requests/                       ← Middlewares de validation des requêtes formelles
│   │   │   ├── BookAppointmentRequest.php  ← Règles d'incréments de 30min
│   │   │   └── ... (SaveDiagnosticRequest, etc.)
│   │   │
│   └── Models/                             
│       ├── User.php 
│       ├── Patient.php, Doctor.php, Appointment.php
│       └── Message.php, Availability.php, Consultation.php
│
├── bootstrap/app.php                   ← Routes middlewares par autorisation/rôle
│
├── config/
│   ├── services.php                    ← Mapping des providers tiers (Zegocloud, etc)
│   └── reverb.php                      ← Configuration WebSockets locaux
│
└── resources/
    └── views/                          ← ARCHITECTURE FRONT-END (Blade + Tailwind)
        ├── welcome.blade.php           ← Landing page ultra-dynamique
        ├── layouts/app.blade.php       ← Header/Footer globaux
        ├── patient/                    
        │   ├── search.blade.php        ← Algorithme Auto-complete JS
        │   └── doctor-profile.blade.php← Réservation liée au calendrier
        ├── messages/           
        │   ├── index.blade.php         
        │   ├── conversation.blade.php  ← Vue.js Websockets hook (anti-doublons Reverb)
        │   └── call.blade.php          ← Interface intégration ZegoCloud UIKit
        ├── doctor/             
        ├── admin/
        └── auth/
```

---

## 🔗 Flux Métiers et Contrôleurs

| Actions Clés | Flux Travaillé | Impact Bases de données / Services |
|---|---|---|
| **Verrouillage RDV** | `PatientController@bookAppointment` | Contrôle d'Availability, empêche le double-booking en BDD en temps réel, impose tranches :00/:30. |
| **Envoi Message** | `MessageController@send` | Enregistrement BDD + Broadcast Websocket Laravel Reverb en `MessageSent`. |
| **Lancement Vidéo** | `MessageController@videoCall` | Génération dynamique identifiant de canal `chat_{min_id}_{max_id}` -> Interface ZegoCloud. |
| **Tri Tableaux** | `PatientController@appointments` | Algorithme `latest()` pour afficher le trafic chronologiquement à jour. |
