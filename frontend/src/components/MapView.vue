<template>
  <div ref="mapContainer" class="map-container"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import api from '../services/api.js'

// Fix icônes Leaflet avec Vite
import markerIconUrl from 'leaflet/dist/images/marker-icon.png'
import markerIcon2xUrl from 'leaflet/dist/images/marker-icon-2x.png'
import markerShadowUrl from 'leaflet/dist/images/marker-shadow.png'

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconUrl: markerIconUrl,
  iconRetinaUrl: markerIcon2xUrl,
  shadowUrl: markerShadowUrl,
})

const emit = defineEmits(['reserve'])

const mapContainer = ref(null)
let map = null

// Icônes colorées selon disponibilité
function createIcon(availableBikes) {
  const color = availableBikes === 0 ? '#e53e3e' : availableBikes <= 2 ? '#dd6b20' : '#38a169'
  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 50" width="40" height="50">
      <ellipse cx="20" cy="46" rx="8" ry="3" fill="rgba(0,0,0,0.2)"/>
      <path d="M20 2 C10 2 4 10 4 18 C4 30 20 44 20 44 C20 44 36 30 36 18 C36 10 30 2 20 2Z"
            fill="${color}" stroke="white" stroke-width="2"/>
      <text x="20" y="23" text-anchor="middle" fill="white" font-size="14" font-weight="bold" font-family="sans-serif">${availableBikes}</text>
    </svg>
  `
  return L.divIcon({
    html: svg,
    className: '',
    iconSize: [40, 50],
    iconAnchor: [20, 50],
    popupAnchor: [0, -52],
  })
}

onMounted(async () => {
  // Centré sur Bayeux
  map = L.map(mapContainer.value).setView([49.2748, -0.7034], 14)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(map)

  try {
    const { data: stations } = await api.getStations()

    stations.forEach((station) => {
      const marker = L.marker([station.latitude, station.longitude], {
        icon: createIcon(station.availableBikes),
      }).addTo(map)

      const available = station.availableBikes
      const statusLabel = available === 0
        ? '<span style="color:#e53e3e;font-weight:bold;">Aucun vélo disponible</span>'
        : `<span style="color:#38a169;font-weight:bold;">${available} vélo${available > 1 ? 's' : ''} disponible${available > 1 ? 's' : ''}</span>`

      const btnDisabled = available === 0 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''

      marker.bindPopup(`
        <div style="font-family:sans-serif;min-width:200px;padding:4px">
          <h3 style="margin:0 0 8px;font-size:1rem;color:#2d3748">${station.name}</h3>
          <p style="margin:0 0 12px">${statusLabel}</p>
          <button
            ${btnDisabled}
            onclick="window.__reserveStation(${JSON.stringify(station).replace(/"/g, '&quot;')})"
            style="
              width:100%;padding:10px;background:#4299e1;color:white;
              border:none;border-radius:8px;font-size:0.95rem;font-weight:600;
              cursor:pointer;
            "
          >
            🚲 Réserver
          </button>
        </div>
      `, { maxWidth: 280 })
    })

    // Bridge entre Leaflet popup et Vue
    window.__reserveStation = (station) => {
      map.closePopup()
      emit('reserve', station)
    }
  } catch (e) {
    console.error('Erreur chargement stations', e)
  }
})

onUnmounted(() => {
  if (map) {
    map.remove()
    map = null
  }
  delete window.__reserveStation
})
</script>

<style scoped>
.map-container {
  width: 100%;
  height: 100%;
}
</style>
