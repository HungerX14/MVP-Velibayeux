<template>
  <div id="app">
    <header class="header">
      <div class="header-content">
        <span class="header-icon">🚲</span>
        <h1>VéliBayeux</h1>
        <p class="header-subtitle">Réservez votre vélo en libre-service</p>
      </div>
    </header>

    <main class="main">
      <MapView @reserve="openReservationModal" />
    </main>

    <!-- Modal réservation -->
    <div v-if="selectedStation" class="modal-overlay" @click.self="closeModals">
      <div class="modal">
        <button class="modal-close" @click="closeModals">✕</button>

        <!-- Étape 1 : formulaire -->
        <div v-if="!reservationResult">
          <h2>{{ selectedStation.name }}</h2>
          <p class="modal-info">
            <span class="badge" :class="selectedStation.availableBikes > 0 ? 'badge-green' : 'badge-red'">
              🚲 {{ selectedStation.availableBikes }} vélo{{ selectedStation.availableBikes > 1 ? 's' : '' }} disponible{{ selectedStation.availableBikes > 1 ? 's' : '' }}
            </span>
          </p>
          <p class="modal-price">Tarif : <strong>1,50 € / 30 min</strong></p>

          <div class="form-group">
            <label>Votre prénom</label>
            <input
              v-model="userName"
              type="text"
              placeholder="Ex : Marie"
              class="input"
              @keyup.enter="reserve"
            />
          </div>

          <button
            class="btn btn-primary"
            :disabled="selectedStation.availableBikes === 0 || isLoading"
            @click="reserve"
          >
            <span v-if="isLoading">⏳ En cours...</span>
            <span v-else-if="selectedStation.availableBikes === 0">Aucun vélo disponible</span>
            <span v-else>Réserver un vélo</span>
          </button>

          <p v-if="error" class="error">{{ error }}</p>
        </div>

        <!-- Étape 2 : paiement -->
        <div v-else-if="!paymentDone">
          <div class="success-icon">✅</div>
          <h2>Vélo réservé !</h2>
          <p>{{ reservationResult.message }}</p>
          <p class="modal-price">Montant à régler : <strong>1,50 €</strong></p>

          <button class="btn btn-success" :disabled="isLoading" @click="pay">
            <span v-if="isLoading">⏳ Traitement...</span>
            <span v-else>💳 Payer 1,50 €</span>
          </button>
        </div>

        <!-- Étape 3 : confirmation finale -->
        <div v-else class="text-center">
          <div class="success-icon">🎉</div>
          <h2>Paiement validé !</h2>
          <p>Votre vélo vous attend à <strong>{{ selectedStation.name }}</strong>.</p>
          <p>Bonne balade, {{ userName }} ! 🚲</p>
          <button class="btn btn-primary" @click="closeModals">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import MapView from './components/MapView.vue'
import api from './services/api.js'

const selectedStation = ref(null)
const userName = ref('')
const reservationResult = ref(null)
const paymentDone = ref(false)
const isLoading = ref(false)
const error = ref('')

function openReservationModal(station) {
  selectedStation.value = station
  userName.value = ''
  reservationResult.value = null
  paymentDone.value = false
  error.value = ''
}

function closeModals() {
  selectedStation.value = null
}

async function reserve() {
  if (!userName.value.trim()) {
    error.value = 'Veuillez entrer votre prénom.'
    return
  }
  error.value = ''
  isLoading.value = true
  try {
    const { data } = await api.reserveBike(selectedStation.value.id, userName.value)
    reservationResult.value = data
    selectedStation.value.availableBikes = data.availableBikes
  } catch (e) {
    error.value = e.response?.data?.error || 'Une erreur est survenue.'
  } finally {
    isLoading.value = false
  }
}

async function pay() {
  isLoading.value = true
  try {
    await api.payReservation(reservationResult.value.reservationId)
    paymentDone.value = true
  } catch {
    error.value = 'Erreur lors du paiement.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f0f4f8;
  color: #1a202c;
}

#app {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.header {
  background: linear-gradient(135deg, #2b6cb0, #4299e1);
  color: white;
  padding: 14px 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.header-content {
  display: flex;
  align-items: center;
  gap: 12px;
}
.header-icon { font-size: 2rem; }
.header h1 { font-size: 1.6rem; font-weight: 700; }
.header-subtitle { font-size: 0.85rem; opacity: 0.85; margin-left: 4px; }

.main { flex: 1; overflow: hidden; }

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 16px;
}

.modal {
  background: white;
  border-radius: 16px;
  padding: 32px;
  width: 100%;
  max-width: 440px;
  position: relative;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: #718096;
}
.modal-close:hover { color: #1a202c; }

.modal h2 {
  font-size: 1.3rem;
  margin-bottom: 16px;
  color: #2d3748;
}

.modal-info { margin-bottom: 12px; }
.modal-price { margin-bottom: 20px; color: #4a5568; }

.badge {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 0.9rem;
  font-weight: 600;
}
.badge-green { background: #c6f6d5; color: #276749; }
.badge-red   { background: #fed7d7; color: #c53030; }

.form-group { margin-bottom: 20px; }
.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
  color: #4a5568;
}
.input {
  width: 100%;
  padding: 10px 14px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
}
.input:focus {
  outline: none;
  border-color: #4299e1;
}

.btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s, transform 0.1s;
}
.btn:disabled { opacity: 0.5; cursor: not-allowed; }
.btn:not(:disabled):active { transform: scale(0.98); }

.btn-primary { background: #4299e1; color: white; }
.btn-primary:not(:disabled):hover { background: #3182ce; }

.btn-success { background: #48bb78; color: white; }
.btn-success:not(:disabled):hover { background: #38a169; }

.success-icon { font-size: 3rem; text-align: center; margin-bottom: 12px; }
.text-center { text-align: center; }

.error {
  color: #c53030;
  font-size: 0.9rem;
  margin-top: 10px;
  text-align: center;
}
</style>
