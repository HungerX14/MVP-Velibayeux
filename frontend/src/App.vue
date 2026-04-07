<template>
  <div id="app">

    <!-- ══════════════════════════════════════════
         SIDEBAR — desktop uniquement
    ══════════════════════════════════════════ -->
    <aside class="sidebar">

      <!-- Logo -->
      <div class="sb-logo">
        <img src="/logo.png" alt="Roue Libre" class="sb-logo-img" />
        <div class="sb-logo-text">
          <span class="sb-title">Roue Libre</span>
          <span class="sb-tagline">la liberté de bouger au cœur de Bayeux</span>
        </div>
      </div>

      <!-- Tabs -->
      <div class="sb-tabs">
        <button class="sb-tab" :class="{ active: tab === 'map' }" @click="tab = 'map'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
          Carte
        </button>
        <button class="sb-tab" :class="{ active: tab === 'profile' }" @click="tab = 'profile'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Profil
        </button>
      </div>

      <!-- Corps de la sidebar -->
      <div class="sb-body">

        <!-- ── Onglet Carte ── -->
        <template v-if="tab === 'map'">

          <!-- Détail station sélectionnée -->
          <div v-if="selectedStation" class="sb-detail">
            <button class="back-btn" @click="closeStation">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
              Retour aux stations
            </button>
            <StationDetail :station="selectedStation" @close="closeStation" />
          </div>

          <!-- Liste des stations -->
          <div v-else class="sb-list-wrapper">
            <!-- Barre de recherche -->
            <div class="search-bar">
              <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
              </svg>
              <input
                v-model="search"
                type="text"
                placeholder="Rechercher une station…"
                class="search-input"
              />
              <button v-if="search" class="search-clear" @click="search = ''">✕</button>
            </div>

            <!-- Filtre disponibilité -->
            <div class="filter-row">
              <span class="filter-count">{{ filteredStations.length }} station{{ filteredStations.length > 1 ? 's' : '' }}</span>
              <button
                class="filter-pill"
                :class="{ 'filter-active': filterAvail }"
                @click="filterAvail = !filterAvail"
              >
                🚲 Disponibles
              </button>
            </div>

            <!-- Loader -->
            <div v-if="loading" class="sb-loading">
              <div class="loading-spinner"></div>
              <span>Chargement…</span>
            </div>

            <!-- Cards stations -->
            <div v-else class="station-list">
              <button
                v-for="s in filteredStations"
                :key="s.id"
                class="station-row"
                @click="selectStation(s)"
              >
                <div class="station-row-color" :style="{ background: getColor(s.availableBikes, s.totalSlots) }"></div>
                <div class="station-row-body">
                  <div class="station-row-name">{{ s.name }}</div>
                  <div class="station-row-meta">
                    <div class="mini-bar">
                      <div class="mini-bar-fill" :style="{ width: pct(s) + '%', background: getColor(s.availableBikes, s.totalSlots) }"></div>
                    </div>
                    <span class="station-row-count">{{ s.availableBikes }}/{{ s.totalSlots }}</span>
                  </div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 18l6-6-6-6"/>
                </svg>
              </button>

              <div v-if="!filteredStations.length" class="list-empty">
                Aucune station trouvée
              </div>
            </div>
          </div>
        </template>

        <!-- ── Onglet Profil ── -->
        <ProfileView v-else-if="tab === 'profile'" />

      </div>
    </aside>

    <!-- ══════════════════════════════════════════
         MAIN — header mobile + carte + bottom nav
    ══════════════════════════════════════════ -->
    <div class="main">

      <!-- Top bar (mobile seulement) -->
      <header class="top-bar">
        <img src="/logo.png" alt="Roue Libre" class="top-logo" />
        <div class="top-text">
          <span class="top-title">Roue Libre</span>
          <span class="top-sub">Bayeux</span>
        </div>
        <!-- Bouton profil mobile -->
        <button class="top-profile-btn" @click="tab = tab === 'profile' ? 'map' : 'profile'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </button>
      </header>

      <!-- Zone de contenu (carte ou profil mobile) -->
      <div class="content-area">
        <MapView
          v-show="tab === 'map' || !isMobile"
          :stations="stations"
          :selected-station-id="selectedStation?.id ?? null"
          @select-station="onMarkerClick"
        />
        <!-- Panneau profil mobile (par-dessus la carte) -->
        <div v-if="isMobile && tab === 'profile'" class="mobile-panel">
          <ProfileView />
        </div>
      </div>

      <!-- Bottom navigation (mobile seulement) -->
      <nav class="bottom-nav">
        <button class="nav-item" :class="{ active: tab === 'map' }" @click="tab = 'map'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
          </svg>
          <span>Carte</span>
        </button>
        <button class="nav-item" :class="{ active: tab === 'profile' }" @click="tab = 'profile'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <span>Profil</span>
        </button>
      </nav>

    </div>

    <!-- ══════════════════════════════════════════
         BOTTOM SHEET — mobile seulement
    ══════════════════════════════════════════ -->
    <Transition name="sheet">
      <div v-if="selectedStation && isMobile" class="sheet-overlay" @click.self="closeStation">
        <div class="sheet">
          <div class="sheet-handle"></div>
          <StationDetail :station="selectedStation" @close="closeStation" />
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import MapView from './components/MapView.vue'
import ProfileView from './components/ProfileView.vue'
import StationDetail from './components/StationDetail.vue'
import api from './services/api.js'

// ── État ──
const tab = ref('map')
const stations = ref([])
const selectedStation = ref(null)
const loading = ref(true)
const search = ref('')
const filterAvail = ref(false)
const isMobile = ref(window.innerWidth < 768)

// ── Responsive ──
function onResize() { isMobile.value = window.innerWidth < 768 }
onMounted(async () => {
  window.addEventListener('resize', onResize)
  try {
    const { data } = await api.getStations()
    stations.value = data
  } catch (e) {
    console.error('Erreur chargement stations', e)
  } finally {
    loading.value = false
  }
})
onUnmounted(() => window.removeEventListener('resize', onResize))

// ── Couleurs ──
function getColor(available, total) {
  if (available === 0) return '#EF4444'
  if (available / total <= 0.25) return '#F59E0B'
  return '#5DC93A'
}
function pct(s) { return Math.round((s.availableBikes / s.totalSlots) * 100) }

// ── Filtres ──
const filteredStations = computed(() => {
  let list = stations.value
  if (filterAvail.value) list = list.filter(s => s.availableBikes > 0)
  if (search.value.trim()) {
    const q = search.value.toLowerCase()
    list = list.filter(s => s.name.toLowerCase().includes(q))
  }
  return list
})

// ── Sélection ──
function selectStation(station) {
  selectedStation.value = { ...station }
  if (!isMobile.value) tab.value = 'map' // s'assurer que la carte est visible
}

function onMarkerClick(station) {
  selectedStation.value = { ...station }
  if (!isMobile.value) tab.value = 'map'
}

function closeStation() {
  selectedStation.value = null
}
</script>

<style>
/* ══ Variables ══ */
:root {
  --green:        #5DC93A;
  --green-dark:   #4AAD2E;
  --green-light:  #EDF9E8;
  --blue:         #4DC3E8;
  --blue-dark:    #2FA8CC;
  --blue-light:   #E3F6FC;
  --text:         #111827;
  --text-muted:   #6B7280;
  --bg:           #F4F6F8;
  --white:        #FFFFFF;
  --border:       #E5E7EB;
  --radius:       16px;
  --sidebar-w:    360px;
  --topbar-h:     58px;
  --nav-h:        62px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  height: 100%;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--text);
  -webkit-font-smoothing: antialiased;
}

/* ══ Root layout ══ */
#app {
  display: flex;
  flex-direction: row;   /* toujours flex-row, sidebar cachée sur mobile */
  height: 100dvh;
  width: 100%;
  overflow: hidden;
  background: var(--white);
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.sidebar {
  display: none;             /* caché sur mobile */
  flex-direction: column;
  width: var(--sidebar-w);
  flex-shrink: 0;
  height: 100dvh;
  border-right: 1px solid var(--border);
  background: var(--white);
  z-index: 10;
}

.sb-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 20px 16px;
  border-bottom: 1px solid var(--border);
}
.sb-logo-img {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  object-fit: cover;
  flex-shrink: 0;
}
.sb-title {
  display: block;
  font-size: 1.05rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1.2;
}
.sb-tagline {
  display: block;
  font-size: 0.65rem;
  color: var(--text-muted);
  line-height: 1.3;
  margin-top: 2px;
}

/* Tabs sidebar */
.sb-tabs {
  display: flex;
  padding: 12px 16px;
  gap: 8px;
  border-bottom: 1px solid var(--border);
  background: var(--bg);
}
.sb-tab {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 9px 12px;
  border: none;
  border-radius: 10px;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  color: var(--text-muted);
  background: transparent;
  transition: background 0.15s, color 0.15s;
}
.sb-tab svg { width: 16px; height: 16px; }
.sb-tab.active {
  background: var(--green-light);
  color: var(--green-dark);
}
.sb-tab.active svg { stroke: var(--green); }

/* Corps sidebar */
.sb-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
}

/* Détail station en sidebar */
.sb-detail {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.back-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text-muted);
  cursor: pointer;
  padding: 0;
  margin-bottom: 4px;
}
.back-btn:hover { color: var(--text); }

/* Liste stations */
.sb-list-wrapper { display: flex; flex-direction: column; }

.search-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  background: var(--white);
  z-index: 2;
}
.search-icon { width: 16px; height: 16px; color: var(--text-muted); flex-shrink: 0; }
.search-input {
  flex: 1;
  border: none;
  background: none;
  font-size: 0.88rem;
  color: var(--text);
  outline: none;
  min-width: 0;
}
.search-input::placeholder { color: var(--text-muted); }
.search-clear {
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  font-size: 0.8rem;
  padding: 2px 4px;
  border-radius: 4px;
}
.search-clear:hover { color: var(--text); background: var(--bg); }

.filter-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
}
.filter-count { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }
.filter-pill {
  font-size: 0.75rem;
  font-weight: 600;
  padding: 5px 12px;
  border-radius: 999px;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--text-muted);
  cursor: pointer;
  transition: all 0.15s;
}
.filter-pill.filter-active {
  background: var(--green-light);
  border-color: var(--green);
  color: var(--green-dark);
}

.sb-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  padding: 40px;
  color: var(--text-muted);
  font-size: 0.88rem;
}
.loading-spinner {
  width: 28px;
  height: 28px;
  border: 3px solid var(--border);
  border-top-color: var(--green);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.station-list { display: flex; flex-direction: column; }
.station-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  background: none;
  border: none;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  text-align: left;
  transition: background 0.12s;
  width: 100%;
}
.station-row:hover { background: var(--bg); }
.station-row:last-child { border-bottom: none; }

.station-row-color {
  width: 4px;
  height: 36px;
  border-radius: 999px;
  flex-shrink: 0;
}
.station-row-body { flex: 1; min-width: 0; }
.station-row-name {
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.station-row-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 5px;
}
.mini-bar {
  flex: 1;
  height: 4px;
  background: var(--border);
  border-radius: 999px;
  overflow: hidden;
}
.mini-bar-fill {
  height: 100%;
  border-radius: 999px;
  transition: width 0.3s;
}
.station-row-count {
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text-muted);
  white-space: nowrap;
}
.chevron {
  width: 16px;
  height: 16px;
  color: #D1D5DB;
  flex-shrink: 0;
}
.list-empty {
  padding: 32px;
  text-align: center;
  color: var(--text-muted);
  font-size: 0.88rem;
}

/* ══════════════════════════════════════════
   MAIN
══════════════════════════════════════════ */
.main {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  min-width: 0;
}

/* Top bar (mobile) */
.top-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  height: var(--topbar-h);
  padding: 0 16px;
  background: var(--white);
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
  z-index: 5;
}
.top-logo {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  object-fit: cover;
  flex-shrink: 0;
}
.top-text { flex: 1; min-width: 0; }
.top-title { display: block; font-size: 1rem; font-weight: 800; }
.top-sub   { display: block; font-size: 0.7rem; color: var(--text-muted); }
.top-profile-btn {
  background: var(--bg);
  border: none;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--text-muted);
  flex-shrink: 0;
}
.top-profile-btn:active { background: var(--border); }

/* Zone contenu */
.content-area {
  flex: 1;
  overflow: hidden;
  position: relative;
}

/* Panneau mobile profil (overlay sur la carte) */
.mobile-panel {
  position: absolute;
  inset: 0;
  background: var(--bg);
  overflow-y: auto;
  z-index: 4;
}

/* Bottom nav (mobile) */
.bottom-nav {
  display: flex;
  height: var(--nav-h);
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
  gap: 3px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-muted);
  font-size: 0.68rem;
  font-weight: 500;
  padding-bottom: env(safe-area-inset-bottom);
  transition: color 0.15s;
  -webkit-tap-highlight-color: transparent;
}
.nav-item svg { width: 22px; height: 22px; }
.nav-item.active { color: var(--green); }
.nav-item.active svg { stroke: var(--green); }

/* ══════════════════════════════════════════
   BOTTOM SHEET (mobile)
══════════════════════════════════════════ */
.sheet-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  z-index: 9999;
}
.sheet {
  background: var(--white);
  border-radius: 24px 24px 0 0;
  padding: 12px 20px 36px;
  width: 100%;
  max-height: 90dvh;
  overflow-y: auto;
}
.sheet-handle {
  width: 40px;
  height: 4px;
  background: var(--border);
  border-radius: 999px;
  margin: 0 auto 20px;
}

/* Transition bottom sheet */
.sheet-enter-active { transition: opacity 0.22s ease; }
.sheet-leave-active { transition: opacity 0.22s ease; }
.sheet-enter-active .sheet,
.sheet-leave-active .sheet { transition: transform 0.28s cubic-bezier(0.32, 0.72, 0, 1); }
.sheet-enter-from { opacity: 0; }
.sheet-enter-from .sheet { transform: translateY(100%); }
.sheet-leave-to { opacity: 0; }
.sheet-leave-to .sheet { transform: translateY(100%); }

/* ══════════════════════════════════════════
   DESKTOP — media query 768px+
══════════════════════════════════════════ */
@media (min-width: 768px) {
  .sidebar    { display: flex; }
  .top-bar    { display: none; }
  .bottom-nav { display: none; }
  .mobile-panel { display: none; }
}
</style>
