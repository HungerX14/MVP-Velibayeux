<template>
  <div class="station-detail">

    <!-- Étape 1 : infos + formulaire -->
    <template v-if="step === 'form'">
      <div class="sd-header">
        <div>
          <h2 class="sd-name">{{ station.name }}</h2>
          <p class="sd-sub">Station de vélos libre-service</p>
        </div>
        <span class="avail-badge" :class="station.availableBikes > 0 ? 'avail-ok' : 'avail-none'">
          {{ station.availableBikes }}/{{ station.totalSlots }}
        </span>
      </div>

      <div class="grid-label">
        <span>Disponibilité</span>
        <span class="legend">
          <span class="dot dot-green"></span>Libre
          <span class="dot dot-gray"></span>Réservé
        </span>
      </div>
      <div class="bike-grid">
        <span
          v-for="i in station.totalSlots"
          :key="i"
          class="bike-slot"
          :class="i <= station.availableBikes ? 'slot-avail' : 'slot-reserved'"
        >🚲</span>
      </div>

      <div class="tarif-label-title">Choisir une durée</div>
      <div class="tarif-options">
        <button
          v-for="(t, i) in tarifs"
          :key="t.label"
          class="tarif-option"
          :class="{ selected: selectedIndex === i }"
          @click="selectedIndex = i"
        >
          <span class="tarif-option-duration">{{ t.label }}</span>
          <span class="tarif-option-price">{{ t.price }} €</span>
        </button>
      </div>

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
        :disabled="station.availableBikes === 0 || isLoading"
        @click="reserve"
      >
        <span v-if="isLoading" class="btn-spinner"></span>
        <span v-else-if="station.availableBikes === 0">Aucun vélo disponible</span>
        <span v-else>Réserver — {{ selectedTarif.price }} €</span>
      </button>
    </template>

    <!-- Étape 2 : paiement -->
    <template v-else-if="step === 'payment'">
      <div class="step-center">
        <div class="step-icon">✅</div>
        <h2 class="step-title">Vélo réservé !</h2>
        <p class="step-desc">Votre vélo à <strong>{{ station.name }}</strong> est prêt.</p>
      </div>
      <div class="payment-summary">
        <span class="payment-duration">{{ selectedTarif.label }}</span>
        <span class="payment-price">{{ selectedTarif.price }} €</span>
      </div>
      <p v-if="error" class="error-msg">{{ error }}</p>
      <button class="btn btn-success" :disabled="isLoading" @click="pay">
        <span v-if="isLoading" class="btn-spinner"></span>
        <span v-else>💳 Payer {{ selectedTarif.price }} €</span>
      </button>
    </template>

    <!-- Étape 3 : confirmation -->
    <template v-else-if="step === 'done'">
      <div class="step-center">
        <div class="step-icon">🎉</div>
        <h2 class="step-title">Paiement validé !</h2>
        <p class="step-desc">
          Bonne balade, <strong>{{ userName }}</strong> !<br>Votre vélo vous attend.
        </p>
        <div class="confetti-card">
          <span>📍</span>
          <span>{{ station.name }}</span>
        </div>
        <button class="btn btn-primary" @click="emit('close')">Fermer</button>
      </div>
    </template>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import api from '../services/api.js'
import { saveReservation } from '../services/reservations.js'

const props = defineProps({
  station: { type: Object, required: true },
})
const emit = defineEmits(['close', 'reserved'])

const tarifs = [
  { label: '1 heure',      price: 4  },
  { label: 'Demi-journée', price: 10 },
  { label: 'Journée',      price: 18 },
  { label: '2 jours',      price: 35 },
]

const userName = ref(localStorage.getItem('roulibre_username') || '')
const step = ref('form')
const isLoading = ref(false)
const error = ref('')
const reservationResult = ref(null)
const selectedIndex = ref(0)
const selectedTarif = computed(() => tarifs[selectedIndex.value])

async function reserve() {
  if (!userName.value.trim()) {
    error.value = 'Veuillez entrer votre prénom.'
    return
  }
  error.value = ''
  isLoading.value = true
  try {
    const { data } = await api.reserveBike(props.station.id, userName.value.trim())
    reservationResult.value = data
    props.station.availableBikes = data.availableBikes
    localStorage.setItem('roulibre_username', userName.value.trim())
    emit('reserved', data)
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
      stationName: props.station.name,
      userName: userName.value,
      date: new Date().toISOString(),
      status: 'paid',
      duration: selectedTarif.value.label,
      price: selectedTarif.value.price,
    })
    step.value = 'done'
  } catch {
    error.value = 'Erreur lors du paiement.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.station-detail { display: flex; flex-direction: column; gap: 0; }

.sd-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}
.sd-name { font-size: 1.1rem; font-weight: 800; color: var(--text); line-height: 1.3; }
.sd-sub  { font-size: 0.78rem; color: var(--text-muted); margin-top: 3px; }

.avail-badge {
  font-size: 0.82rem;
  font-weight: 700;
  padding: 5px 11px;
  border-radius: 999px;
  white-space: nowrap;
  flex-shrink: 0;
  margin-left: 10px;
}
.avail-ok   { background: var(--green-light); color: var(--green-dark); }
.avail-none { background: #FEE2E2; color: #DC2626; }

.grid-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text-muted);
  margin-bottom: 8px;
}
.legend { display: flex; align-items: center; gap: 6px; font-weight: 500; }
.dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 2px; }
.dot-green { background: var(--green); }
.dot-gray  { background: #D1D5DB; }

.bike-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  background: var(--bg);
  border-radius: 12px;
  padding: 12px;
  margin-bottom: 16px;
}
.bike-slot { font-size: 1.15rem; line-height: 1; }
.slot-reserved { opacity: 0.18; filter: grayscale(1); }

.tarif-label-title {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text-muted);
  margin-bottom: 8px;
}

.tarif-options {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
  margin-bottom: 16px;
}

.tarif-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 12px 8px;
  border: 2px solid var(--border);
  border-radius: 12px;
  background: var(--white);
  cursor: pointer;
  transition: border-color 0.15s, background 0.15s;
  -webkit-tap-highlight-color: transparent;
}
.tarif-option:active { transform: scale(0.97); }
.tarif-option.selected {
  border: 3px solid var(--green);
  background: var(--green);
}
.tarif-option-duration {
  font-size: 0.8rem;
  color: var(--text-muted);
  font-weight: 500;
}
.tarif-option.selected .tarif-option-duration {
  color: rgba(255, 255, 255, 0.85);
}
.tarif-option-price {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--text);
}
.tarif-option.selected .tarif-option-price {
  color: white;
}

.payment-summary {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--blue-light);
  border-radius: 12px;
  padding: 14px 16px;
  margin-bottom: 16px;
}
.payment-duration { font-size: 0.95rem; font-weight: 600; color: var(--text); }
.payment-price    { font-size: 1.3rem; font-weight: 800; color: var(--blue-dark); }

.form-group { margin-bottom: 14px; }
.form-group label {
  display: block;
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 7px;
}
.input {
  width: 100%;
  padding: 12px 14px;
  border: 2px solid var(--border);
  border-radius: 12px;
  font-size: 0.95rem;
  background: var(--bg);
  color: var(--text);
  transition: border-color 0.2s;
  -webkit-appearance: none;
}
.input:focus { outline: none; border-color: var(--green); background: var(--white); }

.btn {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: transform 0.1s, opacity 0.2s, background 0.2s;
  -webkit-tap-highlight-color: transparent;
}
.btn:disabled { opacity: 0.45; cursor: not-allowed; }
.btn:not(:disabled):active { transform: scale(0.97); }
.btn-primary { background: var(--green); color: white; }
.btn-primary:not(:disabled):hover { background: var(--green-dark); }
.btn-success { background: var(--blue); color: white; }
.btn-success:not(:disabled):hover { background: var(--blue-dark); }

.btn-spinner {
  width: 17px; height: 17px;
  border: 3px solid rgba(255,255,255,0.35);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.step-center { text-align: center; }
.step-icon  { font-size: 3rem; margin-bottom: 10px; }
.step-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 8px; }
.step-desc  { color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 18px; }

.confetti-card {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--green-light);
  border-radius: 12px;
  padding: 12px 16px;
  font-weight: 600;
  color: var(--green-dark);
  font-size: 0.88rem;
  margin-bottom: 16px;
  text-align: left;
}

.error-msg {
  color: #DC2626;
  font-size: 0.82rem;
  margin-bottom: 12px;
  text-align: center;
  background: #FEF2F2;
  padding: 8px 12px;
  border-radius: 8px;
}
</style>
