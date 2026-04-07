const KEY = 'roulibre_reservations'

export function saveReservation(reservation) {
  const existing = getReservations()
  existing.unshift(reservation) // plus récent en premier
  localStorage.setItem(KEY, JSON.stringify(existing.slice(0, 20))) // garder les 20 dernières
}

export function getReservations() {
  try {
    return JSON.parse(localStorage.getItem(KEY)) || []
  } catch {
    return []
  }
}

export function clearReservations() {
  localStorage.removeItem(KEY)
}
