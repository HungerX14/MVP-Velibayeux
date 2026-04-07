#!/bin/bash

# ============================================
#  VéliBayeux - Script de démarrage
# ============================================

set -e

BACKEND_DIR="$(cd "$(dirname "$0")" && pwd)"
FRONTEND_DIR="$BACKEND_DIR/frontend"

echo ""
echo "🚲  Démarrage de VéliBayeux..."
echo ""

# --- Backend Symfony ---
echo "📦 Installation des dépendances PHP..."
cd "$BACKEND_DIR"
composer install --quiet

echo "🗄️  Création de la base de données..."
php bin/console doctrine:migrations:migrate --no-interaction --quiet

echo "🌱 Chargement des données de démonstration..."
php bin/console doctrine:fixtures:load --no-interaction --quiet

echo ""
echo "✅ Backend prêt ! Lancement du serveur Symfony sur http://localhost:8000"
php -S localhost:8000 -t public &
BACKEND_PID=$!

# --- Frontend Vue ---
echo ""
echo "📦 Installation des dépendances Node..."
cd "$FRONTEND_DIR"
npm install --silent

echo ""
echo "✅ Frontend prêt ! Lancement de Vue sur http://localhost:5173"
npm run dev &
FRONTEND_PID=$!

echo ""
echo "============================================"
echo "  🚲  VéliBayeux est lancé !"
echo "============================================"
echo ""
echo "  👉 Ouvrez votre navigateur sur :"
echo "     http://localhost:5173"
echo ""
echo "  API disponible sur :"
echo "     http://localhost:8000/api/stations"
echo ""
echo "  Pour arrêter : appuyez sur Ctrl+C"
echo "============================================"
echo ""

# Attendre et gérer l'arrêt propre
trap "echo ''; echo '🛑 Arrêt...'; kill $BACKEND_PID $FRONTEND_PID 2>/dev/null; exit 0" INT TERM
wait
