<template>
  <div class="profile-view">

    <!-- Carte utilisateur -->
    <div class="profile-card">
      <div class="avatar">
        <span>{{ initials }}</span>
      </div>
      <div class="profile-info">
        <div v-if="!editing">
          <p class="profile-name">{{ storedName || 'Invité' }}</p>
          <p class="profile-sub">{{ reservations.length }} réservation{{ reservations.length > 1 ? 's' : '' }}</p>
        </div>
        <div v-else class="edit-inline">
          <input
            v-model="editName"
            type="text"
            placeholder="Votre prénom"
            class="input-inline"
            @keyup.enter="saveName"
          />
          <button class="btn-save" @click="saveName">✓</button>
        </div>
      </div>
      <button class="edit-btn" @click="startEdit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
      </button>
    </div>

    <!-- Stats rapides -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-value">{{ reservations.length }}</div>
        <div class="stat-label">Réservations</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ paidCount }}</div>
        <div class="stat-label">Payées</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">{{ (reservations.length * 1.5).toFixed(0) }}€</div>
        <div class="stat-label">Total dépensé</div>
      </div>
    </div>

    <!-- Historique -->
    <div class="section-title">
      <span>Historique</span>
      <button v-if="reservations.length" class="clear-btn" @click="clearHistory">Effacer</button>
    </div>

    <div v-if="reservations.length === 0" class="empty-state">
      <div class="empty-icon">🚲</div>
      <p class="empty-title">Aucune réservation</p>
      <p class="empty-sub">Vos réservations apparaîtront ici après votre première balade.</p>
    </div>

    <div v-else class="reservations-list">
      <div
        v-for="r in reservations"
        :key="r.id"
        class="reservation-card"
      >
        <div class="res-icon">🚲</div>
        <div class="res-info">
          <p class="res-station">{{ r.stationName }}</p>
          <p class="res-date">{{ formatDate(r.date) }}</p>
        </div>
        <div class="res-status" :class="r.status === 'paid' ? 'status-paid' : 'status-pending'">
          {{ r.status === 'paid' ? 'Payé' : 'En attente' }}
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { getReservations, clearReservations } from '../services/reservations.js'

const storedName = ref(localStorage.getItem('roulibre_username') || '')
const reservations = ref([])
const editing = ref(false)
const editName = ref('')

const initials = computed(() => {
  const name = storedName.value || 'I'
  return name.slice(0, 2).toUpperCase()
})

const paidCount = computed(() => reservations.value.filter(r => r.status === 'paid').length)

onMounted(() => {
  reservations.value = getReservations()
})

function startEdit() {
  editName.value = storedName.value
  editing.value = true
}

function saveName() {
  const name = editName.value.trim()
  if (name) {
    storedName.value = name
    localStorage.setItem('roulibre_username', name)
  }
  editing.value = false
}

function clearHistory() {
  clearReservations()
  reservations.value = []
}

function formatDate(iso) {
  const d = new Date(iso)
  return d.toLocaleDateString('fr-FR', {
    day: '2-digit', month: 'long', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}
</script>

<style scoped>
.profile-view {
  height: 100%;
  overflow-y: auto;
  padding: 16px 16px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  background: var(--bg);
}

/* Carte profil */
.profile-card {
  background: var(--white);
  border-radius: var(--radius);
  padding: 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.avatar {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--green), var(--blue));
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.1rem;
  font-weight: 800;
  flex-shrink: 0;
}
.profile-info { flex: 1; min-width: 0; }
.profile-name { font-size: 1rem; font-weight: 700; color: var(--text); }
.profile-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
.edit-btn {
  background: var(--bg);
  border: none;
  border-radius: 8px;
  padding: 8px;
  cursor: pointer;
  color: var(--text-muted);
  flex-shrink: 0;
}
.edit-btn:active { opacity: 0.7; }

.edit-inline { display: flex; gap: 8px; }
.input-inline {
  flex: 1;
  padding: 8px 12px;
  border: 2px solid var(--green);
  border-radius: 10px;
  font-size: 0.9rem;
  background: var(--bg);
  color: var(--text);
  min-width: 0;
}
.input-inline:focus { outline: none; }
.btn-save {
  background: var(--green);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 0 14px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
}

/* Stats */
.stats-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}
.stat-card {
  background: var(--white);
  border-radius: 12px;
  padding: 14px 8px;
  text-align: center;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.stat-value {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--green);
  line-height: 1;
  margin-bottom: 4px;
}
.stat-label {
  font-size: 0.7rem;
  color: var(--text-muted);
  font-weight: 500;
}

/* Section title */
.section-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0 2px;
}
.clear-btn {
  background: none;
  border: none;
  font-size: 0.8rem;
  color: #EF4444;
  cursor: pointer;
  font-weight: 600;
  text-transform: none;
  letter-spacing: 0;
}

/* Empty state */
.empty-state {
  background: var(--white);
  border-radius: var(--radius);
  padding: 40px 20px;
  text-align: center;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.empty-icon { font-size: 2.5rem; margin-bottom: 12px; }
.empty-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
.empty-sub { font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; }

/* Liste réservations */
.reservations-list { display: flex; flex-direction: column; gap: 10px; }
.reservation-card {
  background: var(--white);
  border-radius: 14px;
  padding: 14px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.res-icon {
  font-size: 1.4rem;
  background: var(--green-light);
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.res-info { flex: 1; min-width: 0; }
.res-station {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.res-date { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

.res-status {
  font-size: 0.72rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 999px;
  white-space: nowrap;
  flex-shrink: 0;
}
.status-paid    { background: var(--green-light);  color: var(--green-dark); }
.status-pending { background: #FEF3C7; color: #D97706; }
</style>
