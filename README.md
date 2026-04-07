# 🚲 VéliBayeux

Application de réservation de vélos en libre-service — Projet scolaire STMG.

---

## 🎯 Présentation

VéliBayeux permet de :
- Voir les stations de vélos de Bayeux sur une carte interactive
- Consulter le nombre de vélos disponibles dans chaque station
- Réserver un vélo en quelques clics
- Simuler un paiement en ligne

---

## 🖥️ Démo rapide (recommandé)

**Pré-requis :** PHP 8.1+, Composer, Node.js 18+

```bash
# 1. Cloner le projet
git clone https://github.com/HungerX14/MVP-Velibayeux.git
cd MVP-Velibayeux

# 2. Lancer l'application en une seule commande
./start.sh
```

Puis ouvrir **http://localhost:5173** dans le navigateur.

> Pour arrêter : `Ctrl+C`

---

## 🔧 Installation manuelle (étape par étape)

### Backend (Symfony)

```bash
# Installer les dépendances
composer install

# Créer la base de données et les tables
php bin/console doctrine:migrations:migrate --no-interaction

# Charger les données de démonstration
php bin/console doctrine:fixtures:load --no-interaction

# Lancer le serveur PHP
php -S localhost:8000 -t public
```

### Frontend (Vue 3)

```bash
# Dans un autre terminal
cd frontend
npm install
npm run dev
```

Ouvrir **http://localhost:5173**

---

## 🔄 Réinitialiser les données

Pour remettre les stations à leur état initial :

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

---

## 📡 API disponibles

| Méthode | URL | Description |
|---------|-----|-------------|
| `GET` | `/api/stations` | Liste toutes les stations |
| `GET` | `/api/stations/{id}` | Détail d'une station |
| `POST` | `/api/stations/{id}/reserve` | Réserver un vélo |
| `POST` | `/api/reservations/{id}/pay` | Simuler un paiement |

**Exemple de réservation :**
```bash
curl -X POST http://localhost:8000/api/stations/1/reserve \
  -H "Content-Type: application/json" \
  -d '{"userName": "Marie"}'
```

---

## 🗂️ Structure du projet

```
MVP-Velibayeux/
├── src/                     # Code Symfony (PHP)
│   ├── Entity/              # Modèles de données
│   │   ├── Station.php      # Une station de vélos
│   │   ├── Bike.php         # Un vélo (disponible ou réservé)
│   │   └── Reservation.php  # Une réservation
│   ├── Controller/Api/      # Endpoints de l'API REST
│   └── DataFixtures/        # Données de démonstration
├── frontend/                # Application Vue 3
│   └── src/
│       ├── App.vue          # Composant principal + modals
│       ├── components/
│       │   └── MapView.vue  # Carte Leaflet + marqueurs
│       └── services/
│           └── api.js       # Communication avec le backend
├── start.sh                 # Script de démarrage tout-en-un
└── README.md                # Ce fichier
```

---

## 🛠️ Stack technique

| Rôle | Technologie |
|------|------------|
| Backend | Symfony 8 (PHP) |
| Frontend | Vue 3 + Vite |
| Carte | Leaflet + OpenStreetMap |
| Base de données | SQLite (fichier `var/data.db`) |
| Requêtes HTTP | Axios |

---

## 🎨 Légende des marqueurs

| Couleur | Signification |
|---------|--------------|
| 🟢 Vert | Plusieurs vélos disponibles |
| 🟠 Orange | 1 ou 2 vélos restants |
| 🔴 Rouge | Aucun vélo disponible |

---

*Projet réalisé dans le cadre d'un projet STMG.*
