<template>
  <div ref="mapContainer" class="map-container"></div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
  stations: { type: Array, default: () => [] },
  selectedStationId: { type: Number, default: null },
})
const emit = defineEmits(['select-station'])

const mapContainer = ref(null)
let map = null
const markerMap = {} // id → { marker, station }

const COLOR_GREEN  = '#5DC93A'
const COLOR_ORANGE = '#F59E0B'
const COLOR_RED    = '#EF4444'

function getColor(available, total) {
  if (available === 0) return COLOR_RED
  if (available / total <= 0.25) return COLOR_ORANGE
  return COLOR_GREEN
}

function buildIcon(available, total, selected = false) {
  const color = getColor(available, total)
  const ring = selected
    ? `<circle cx="22" cy="22" r="20" fill="none" stroke="${color}" stroke-width="3" opacity="0.4"/>`
    : ''
  const size = selected ? [52, 64] : [44, 54]
  const offset = selected ? [26, 64] : [22, 54]
  const vb = selected ? '0 0 52 68' : '0 0 44 54'

  const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="${vb}" width="${size[0]}" height="${size[1]}">
      ${ring}
      <filter id="sh${available}">
        <feDropShadow dx="0" dy="2" stdDeviation="${selected ? 3 : 2}" flood-opacity="${selected ? 0.35 : 0.2}"/>
      </filter>
      <path d="M22 2C12.6 2 5 9.6 5 19C5 31.5 22 52 22 52C22 52 39 31.5 39 19C39 9.6 31.4 2 22 2Z"
        fill="${color}" filter="url(#sh${available})" ${selected ? 'transform="translate(5,6)"' : ''}/>
      <circle cx="${selected ? 27 : 22}" cy="${selected ? 25 : 19}" r="11" fill="white" opacity="0.92"/>
      <text x="${selected ? 27 : 22}" y="${selected ? 30 : 24}" text-anchor="middle" fill="${color}"
        font-size="13" font-weight="800" font-family="system-ui,sans-serif">${available}</text>
    </svg>
  `
  return L.divIcon({
    html: svg,
    className: '',
    iconSize: size,
    iconAnchor: offset,
    popupAnchor: [0, -(offset[1] + 4)],
  })
}

watch(() => props.stations, (stations) => {
  if (!map || !stations.length) return
  // Clear existing markers
  Object.values(markerMap).forEach(({ marker }) => marker.remove())
  Object.keys(markerMap).forEach(k => delete markerMap[k])
  addMarkers(stations)
}, { deep: false })

watch(() => props.selectedStationId, (id) => {
  // Reset all markers to normal
  Object.values(markerMap).forEach(({ marker, station }) => {
    marker.setIcon(buildIcon(station.availableBikes, station.totalSlots, false))
  })
  // Highlight selected
  if (id && markerMap[id]) {
    const { marker, station } = markerMap[id]
    marker.setIcon(buildIcon(station.availableBikes, station.totalSlots, true))
    map.flyTo([station.latitude, station.longitude], Math.max(map.getZoom(), 16), {
      animate: true, duration: 0.6,
    })
  }
})

function addMarkers(stations) {
  stations.forEach((station) => {
    const marker = L.marker([station.latitude, station.longitude], {
      icon: buildIcon(station.availableBikes, station.totalSlots, false),
    }).addTo(map)

    marker.on('click', () => {
      emit('select-station', { ...station })
    })

    markerMap[station.id] = { marker, station }
  })
}

onMounted(() => {
  map = L.map(mapContainer.value, { zoomControl: false }).setView([49.2748, -0.7034], 15)

  L.control.zoom({ position: 'bottomright' }).addTo(map)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
  }).addTo(map)

  if (props.stations.length) {
    addMarkers(props.stations)
  }
})

onUnmounted(() => {
  if (map) { map.remove(); map = null }
})
</script>

<style>
.map-container { width: 100%; height: 100%; }

.leaflet-control-zoom {
  border: none !important;
  box-shadow: 0 2px 12px rgba(0,0,0,0.12) !important;
  border-radius: 12px !important;
  overflow: hidden;
}
.leaflet-control-zoom a {
  width: 36px !important;
  height: 36px !important;
  line-height: 36px !important;
  font-size: 1.1rem !important;
  color: #111827 !important;
  border: none !important;
  background: white !important;
}
.leaflet-control-zoom a:hover { background: #F4F6F8 !important; }
</style>
