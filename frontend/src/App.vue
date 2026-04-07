<template>
  <div id="app">

    <!-- Header -->
    <header class="header">
      <div class="header-inner">
        <img src="/logo.png" alt="Roue Libre" class="header-logo" />
        <div class="header-text">
          <h1>Roue Libre</h1>
          <p>la liberté de bouger au cœur de Bayeux</p>
        </div>
      </div>
    </header>

    <!-- Contenu principal -->
    <main class="main-content">
      <MapView v-if="currentTab === 'map'" @reserve="openReservationSheet" />
      <ProfileView v-else-if="currentTab === 'profile'" />
    </main>

    <!-- Bottom navigation -->
    <nav class="bottom-nav">
      <button
        class="nav-item"
        :class="{ active: currentTab === 'map' }"
        @click="currentTab = 'map'"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
        </svg>
        <span>Carte</span>
      </button>
      <button
        class="nav-item"
        :class="{ active: currentTab === 'profile' }"
        @click="currentTab = 'profile'"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <span>Profil</span>
      </button>
    </nav>

    <!-- Bottom sheet réservation -->
    <Transition name="sheet">
      <div v-if="selectedStation" class="sheet-overlay" @click.self="closeSheet">
        <div class="sheet">
          <div class="sheet-handle"></div>

          <!-- Étape 1 : détail station + formulaire -->
          <template v-if="step === 'form'">
            <div class="sheet-header">
              <div>
                <h2>{{ selectedStation.name }}</h2>
                <p class="sheet-subtitle">Station de vélos</p>
              </div>
              <span class="avail-badge" :class="selectedStation.availableBikes > 0 ? 'avail-ok' : 'avail-none'">
                {{ selectedStation.availableBikes }}/{{ selectedStation.totalSlots }}
              </span>
            </div>

            <!-- Grille visuelle des vélos -->
            <div class="bike-grid-label">
              <span>Disponibilité des vélos</span>
              <span class="legend">
                <span class="dot dot-green"></span>Libre
                <span class="dot dot-gray"></span>Réservé
              </span>
            </div>
            <div class="bike-grid">
              <span
                v-for="i in selectedStation.totalSlots"
                :key="i"
                class="bike-slot"
                :class="i <= selectedStation.availableBikes ? 'slot-available' : 'slot-reserved'"
              >🚲</span>
            </div>

            <!-- Tarif -->
            <div class="tarif-card">
              <div class="tarif-icon">💶</div>
              <div>
                <div class="tarif-price">1,50 €</div>
                <div class="tarif-label">par 30 minutes</div>
              </div>
            </div>

            <!-- Prénom -->
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

            <p v-if="error" class="error-msg">{{ error }}</p>

            <button
              class="btn btn-primary"
              :disabled="selectedStation.availableBikes === 0 || isLoading"
              @click="reserve"
            >
              <span v-if="isLoading" class="btn-spinner"></span>
              <span v-else-if="selectedStation.availableBikes === 0">Aucun vélo disponible</span>
              <span v-else>Réserver un vélo</span>
            </button>
          </template>

          <!-- Étape 2 : paiement -->
          <template v-else-if="step === 'payment'">
            <div class="step-icon">✅</div>
            <h2 class="step-title">Vélo réservé !</h2>
            <p class="step-desc">Votre vélo à <strong>{{ selectedStation.name }}</strong> est prêt.</p>

            <div class="tarif-card">
              <div class="tarif-icon">💶</div>
              <div>
                <div class="tarif-price">1,50 €</div>
                <div class="tarif-label">à régler maintenant</div>
              </div>
            </div>

            <p v-if="error" class="error-msg">{{ error }}</p>

            <button class="btn btn-success" :disabled="isLoading" @click="pay">
              <span v-if="isLoading" class="btn-spinner"></span>
              <span v-else>💳 Payer 1,50 €</span>
            </button>
          </template>

          <!-- Étape 3 : confirmation -->
          <template v-else-if="step === 'done'">
            <div class="step-icon">🎉</div>
            <h2 class="step-title">Paiement validé !</h2>
            <p class="step-desc">Bonne balade, <strong>{{ userName }}</strong> !<br>Votre vélo vous attend.</p>
            <div class="confetti-card">
              <span>📍</span>
              <span>{{ selectedStation.name }}</span>
            </div>
            <button class="btn btn-primary" @click="closeSheet">Fermer</button>
          </template>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import MapView from './components/MapView.vue'
import ProfileView from './components/ProfileView.vue'
import api from './services/api.js'
import { saveReservation } from './services/reservations.js'

const currentTab = ref('map')
const selectedStation = ref(null)
const userName = ref(localStorage.getItem('roulibre_username') || '')
const reservationResult = ref(null)
const step = ref('form')
const isLoading = ref(false)
const error = ref('')

function openReservationSheet(station) {
  selectedStation.value = station
  reservationResult.value = null
  step.value = 'form'
  error.value = ''
}

function closeSheet() {
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
    const { data } = await api.reserveBike(selectedStation.value.id, userName.value.trim())
    reservationResult.value = data
    selectedStation.value.availableBikes = data.availableBikes
    localStorage.setItem('roulibre_username', userName.value.trim())
    step.value = 'payment'
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
    saveReservation({
      id: reservationResult.value.reservationId,
      stationName: selectedStation.value.name,
      userName: userName.value,
      date: new Date().toISOString(),
      status: 'paid',
    })
    step.value = 'done'
  } catch {
    error.value = 'Erreur lors du paiement.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style>
:root {
  --green:       #5DC93A;
  --green-dark:  #4AAD2E;
  --green-light: #EDF9E8;
  --blue:        #4DC3E8;
  --blue-dark:   #2FA8CC;
  --blue-light:  #E3F6FC;
  --text:        #111827;
  --text-muted:  #6B7280;
  --bg:          #F4F6F8;
  --white:       #FFFFFF;
  --border:      #E5E7EB;
  --radius:      16px;
  --nav-h:       64px;
  --header-h:    62px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  height: 100%;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--text);
  -webkit-font-smoothing: antialiased;
}

#app {
  display: flex;
  flex-direction: column;
  height: 100dvh;
  max-width: 480px;
  margin: 0 auto;
  background: var(--white);
  position: relative;
  box-shadow: 0 0 40px rgba(0,0,0,0.08);
}

/* ── Header ── */
.header {
  height: var(--header-h);
  background: var(--white);
  border-bottom: 1px solid var(--border);
  padding: 0 16px;
  display: flex;
  align-items: center;
  flex-shrink: 0;
  z-index: 10;
}
.header-inner {
  display: flex;
  align-items: center;
  gap: 10px;
}
.header-logo {
  height: 44px;
  width: 44px;
  border-radius: 12px;
  object-fit: cover;
  flex-shrink: 0;
}
.header-text h1 {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1.2;
}
.header-text p {
  font-size: 0.68rem;
  color: var(--text-muted);
  line-height: 1.2;
}

/* ── Main ── */
.main-content {
  flex: 1;
  overflow: hidden;
  position: relative;
}

/* ── Bottom nav ── */
.bottom-nav {
  height: var(--nav-h);
  display: flex;
  border-top: 1px solid var(--border);
  background: var(--white);
  flex-shrink: 0;
}
.nav-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 4px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-muted);
  font-size: 0.7rem;
  font-weight: 500;
  transition: color 0.2s;
  padding-bottom: env(safe-area-inset-bottom);
}
.nav-item svg {
  width: 22px;
  height: 22px;
}
.nav-item.active {
  color: var(--green);
}
.nav-item.active svg {
  stroke: var(--green);
}

/* ── Bottom sheet ── */
.sheet-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  z-index: 9999;
}
.sheet {
  background: var(--white);
  border-radius: 24px 24px 0 0;
  padding: 12px 20px 32px;
  width: 100%;
  max-width: 480px;
  max-height: 88dvh;
  overflow-y: auto;
}
.sheet-handle {
  width: 40px;
  height: 4px;
  background: var(--border);
  border-radius: 999px;
  margin: 0 auto 20px;
}

/* ── Sheet header ── */
.sheet-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}
.sheet-header h2 {
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--text);
  line-height: 1.3;
}
.sheet-subtitle {
  font-size: 0.8rem;
  color: var(--text-muted);
  margin-top: 2px;
}
.avail-badge {
  font-size: 0.85rem;
  font-weight: 700;
  padding: 6px 12px;
  border-radius: 999px;
  white-space: nowrap;
}
.avail-ok   { background: var(--green-light); color: var(--green-dark); }
.avail-none { background: #FEE2E2; color: #DC2626; }

/* ── Bike grid ── */
.bike-grid-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-muted);
  margin-bottom: 10px;
}
.legend {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
}
.dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 2px;
}
.dot-green { background: var(--green); }
.dot-gray  { background: #D1D5DB; }

.bike-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 20px;
  background: var(--bg);
  border-radius: 12px;
  padding: 12px;
}
.bike-slot {
  font-size: 1.2rem;
  line-height: 1;
  transition: opacity 0.2s;
}
.slot-reserved { opacity: 0.2; filter: grayscale(1); }
.slot-available { opacity: 1; }

/* ── Tarif card ── */
.tarif-card {
  display: flex;
  align-items: center;
  gap: 14px;
  background: var(--blue-light);
  border-radius: 12px;
  padding: 14px 16px;
  margin-bottom: 20px;
}
.tarif-icon { font-size: 1.6rem; }
.tarif-price { font-size: 1.2rem; font-weight: 800; color: var(--blue-dark); }
.tarif-label { font-size: 0.78rem; color: var(--text-muted); }

/* ── Form ── */
.form-group { margin-bottom: 16px; }
.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 8px;
}
.input {
  width: 100%;
  padding: 13px 16px;
  border: 2px solid var(--border);
  border-radius: 12px;
  font-size: 1rem;
  background: var(--bg);
  color: var(--text);
  transition: border-color 0.2s;
  -webkit-appearance: none;
}
.input:focus {
  outline: none;
  border-color: var(--green);
  background: var(--white);
}

/* ── Buttons ── */
.btn {
  width: 100%;
  padding: 15px;
  border: none;
  border-radius: 14px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: transform 0.1s, opacity 0.2s;
  -webkit-tap-highlight-color: transparent;
}
.btn:disabled { opacity: 0.45; cursor: not-allowed; }
.btn:not(:disabled):active { transform: scale(0.97); }

.btn-primary { background: var(--green); color: white; }
.btn-primary:not(:disabled):hover { background: var(--green-dark); }

.btn-success { background: var(--blue); color: white; }
.btn-success:not(:disabled):hover { background: var(--blue-dark); }

/* ── Spinner ── */
.btn-spinner {
  width: 18px; height: 18px;
  border: 3px solid rgba(255,255,255,0.4);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Step screens ── */
.step-icon { font-size: 3.5rem; text-align: center; margin-bottom: 12px; }
.step-title { font-size: 1.3rem; font-weight: 800; text-align: center; margin-bottom: 8px; }
.step-desc { text-align: center; color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px; line-height: 1.5; }

.confetti-card {
  background: var(--green-light);
  border-radius: 12px;
  padding: 14px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  color: var(--green-dark);
  margin-bottom: 20px;
  font-size: 0.9rem;
}

/* ── Error ── */
.error-msg {
  color: #DC2626;
  font-size: 0.85rem;
  margin-bottom: 12px;
  text-align: center;
  background: #FEF2F2;
  padding: 8px 12px;
  border-radius: 8px;
}

/* ── Sheet transition ── */
.sheet-enter-active, .sheet-leave-active {
  transition: opacity 0.25s ease;
}
.sheet-enter-active .sheet, .sheet-leave-active .sheet {
  transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
}
.sheet-enter-from { opacity: 0; }
.sheet-enter-from .sheet { transform: translateY(100%); }
.sheet-leave-to { opacity: 0; }
.sheet-leave-to .sheet { transform: translateY(100%); }
</style>
