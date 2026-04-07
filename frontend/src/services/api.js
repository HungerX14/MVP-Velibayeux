import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
})

export default {
  getStations() {
    return api.get('/stations')
  },

  reserveBike(stationId, userName) {
    return api.post(`/stations/${stationId}/reserve`, { userName })
  },

  payReservation(reservationId) {
    return api.post(`/reservations/${reservationId}/pay`)
  },
}
