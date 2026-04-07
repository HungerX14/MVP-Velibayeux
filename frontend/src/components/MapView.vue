<template>
  <div ref="mapContainer" class="map-container"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import api from '../services/api.js'

const emit = defineEmits(['reserve'])
const mapContainer = ref(null)
let map = null
const markers = []

// Couleurs du logo Roue Libre
const COLOR_GREEN  = '#5DC93A'
const COLOR_ORANGE = '#F59E0B'
const COLOR_RED    = '#EF4444'
const COLOR_BLUE   = '#4DC3E8'

function getColor(available, total) {
  if (available === 0) return COLOR_RED
  const ratio = available / total
  if (ratio <= 0.25) return COLOR_ORANGE
  return COLOR_GREEN
}

function createMarkerIcon(available, total) {
  const color = getColor(available, total)
  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 54" width="44" height="54">
      <filter id="shadow">
        <feDropShadow dx="0" dy="2" stdDeviation="2" flood-opacity="0.25"/>
      </filter>
      <path d="M22 2C12.6 2 5 9.6 5 19C5 31.5 22 52 22 52C22 52 39 31.5 39 19C39 9.6 31.4 2 22 2Z"
        fill="${color}" filter="url(#shadow)"/>
      <circle cx="22" cy="19" r="11" fill="white" opacity="0.9"/>
      <text x="22" y="24" text-anchor="middle" fill="${color}"
        font-size="13" font-weight="800" font-family="system-ui,sans-serif">${available}</text>
    </svg>
  `
  return L.divIcon({
    html: svg,
    className: '',
    iconSize: [44, 54],
    iconAnchor: [22, 54],
    popupAnchor: [0, -56],
  })
}

function buildPopupHtml(station) {
  const color = getColor(station.availableBikes, station.totalSlots)
  const reserved = station.totalSlots - station.availableBikes
  const pct = Math.round((station.availableBikes / station.totalSlots) * 100)
  const disabled = station.availableBikes === 0 ? 'disabled' : ''
  const btnStyle = station.availableBikes === 0
    ? `background:#D1D5DB;cursor:not-allowed;`
    : `background:${COLOR_GREEN};cursor:pointer;`

  // Grille vélos miniature
  const slots = Array.from({ length: station.totalSlots }, (_, i) => {
    const avail = i < station.availableBikes
    return `<span style="font-size:14px;opacity:${avail ? '1' : '0.2'};filter:${avail ? 'none' : 'grayscale(1)'}">🚲</span>`
  }).join('')

  return `
    <div style="font-family:system-ui,sans-serif;width:240px;padding:4px 0">
      <div style="font-weight:800;font-size:0.95rem;color:#111827;margin-bottom:4px;line-height:1.3">${station.name}</div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <span style="font-size:0.78rem;color:#6B7280">${station.availableBikes} dispo · ${reserved} réservé${reserved > 1 ? 's' : ''}</span>
        <span style="font-size:0.75rem;font-weight:700;color:${color}">${pct}%</span>
      </div>

      <!-- Barre de progression -->
      <div style="background:#E5E7EB;border-radius:999px;height:6px;margin-bottom:12px;overflow:hidden">
        <div style="width:${pct}%;height:100%;background:${color};border-radius:999px;transition:width 0.3s"></div>
      </div>

      <!-- Mini grille vélos -->
      <div style="display:flex;flex-wrap:wrap;gap:3px;background:#F4F6F8;border-radius:10px;padding:8px;margin-bottom:14px">
        ${slots}
      </div>

      <button
        ${disabled}
        onclick="window.__reserveStation(${JSON.stringify(station).replace(/"/g, '&quot;')})"
        style="
          width:100%;padding:11px 0;
          ${btnStyle}
          color:white;border:none;border-radius:10px;
          font-size:0.9rem;font-weight:700;
          display:flex;align-items:center;justify-content:center;gap:6px;
        "
      >
        🚲 ${station.availableBikes === 0 ? 'Aucun vélo disponible' : 'Réserver'}
      </button>
    </div>
  `
}

onMounted(async () => {
  map = L.map(mapContainer.value, { zoomControl: false }).setView([49.2748, -0.7034], 15)

  // Zoom control en haut à droite (mobile-friendly)
  L.control.zoom({ position: 'bottomright' }).addTo(map)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(map)

  try {
    const { data: stations } = await api.getStations()

    stations.forEach((station) => {
      const marker = L.marker([station.latitude, station.longitude], {
        icon: createMarkerIcon(station.availableBikes, station.totalSlots),
      }).addTo(map)

      marker.bindPopup(buildPopupHtml(station), {
        maxWidth: 260,
        className: 'custom-popup',
      })

      markers.push({ marker, station })
    })

    // Bridge Leaflet → Vue
    window.__reserveStation = (station) => {
      map.closePopup()
      emit('reserve', { ...station })
    }
  } catch (e) {
    console.error('Erreur chargement stations', e)
  }
})

onUnmounted(() => {
  if (map) { map.remove(); map = null }
  delete window.__reserveStation
})
</script>

<style>
.map-container {
  width: 100%;
  height: 100%;
}

/* Popup Leaflet custom */
.custom-popup .leaflet-popup-content-wrapper {
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
  border: none;
  padding: 0;
}
.custom-popup .leaflet-popup-content {
  margin: 16px;
}
.custom-popup .leaflet-popup-tip {
  box-shadow: none;
}

/* Zoom control */
.leaflet-control-zoom a {
  border-radius: 10px !important;
  font-size: 1.1rem !important;
  width: 36px !important;
  height: 36px !important;
  line-height: 36px !important;
  color: #111827 !important;
}
</style>
