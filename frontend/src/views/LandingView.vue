<template>
  <div class="landing">

    <!-- ══ NAV ══ -->
    <nav class="nav" :class="{ scrolled: isScrolled }">
      <a href="/" class="nav-logo">
        <div class="logo-icon">🚲</div>
        <span>Roue Libre</span>
      </a>
      <div class="nav-links">
        <a href="#features">Le service</a>
        <a href="#tarifs">Tarifs</a>
        <RouterLink to="/app" class="nav-cta">Réserver →</RouterLink>
      </div>
    </nav>

    <!-- ══ HERO ══ -->
    <section class="hero">
      <div class="hero-bg">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/9/91/Cath%C3%A9drale_Notre-Dame_de_Bayeux._Vue_depuis_l%27h%C3%B4tel_du_doyen.jpg"
          alt="Cathédrale de Bayeux"
          class="hero-img"
        />
        <div class="hero-overlay"></div>
      </div>
      <div class="hero-content">
        <div class="hero-badge">📍 Bayeux, Normandie</div>
        <h1>La liberté de<br><span>bouger à vélo</span></h1>
        <p>Location de vélos électriques en libre-service.<br>Simple, rapide, écologique.</p>
        <div class="hero-actions">
          <RouterLink to="/app" class="btn-green">🚲 Louer maintenant</RouterLink>
          <a href="#screenshots" class="btn-ghost">Voir l'application ↓</a>
        </div>
        <div class="hero-pills">
          <span class="pill">⚡ 100% électrique</span>
          <span class="pill">🕐 Disponible 24h/24</span>
          <span class="pill">📍 3 stations</span>
        </div>
      </div>
      <a href="#screenshots" class="scroll-hint">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      </a>
    </section>

    <!-- ══ SCREENSHOTS ══ -->
    <section id="screenshots" class="screenshots-section">
      <div class="container">
        <div class="section-tag">L'application</div>
        <h2>Réservez en quelques secondes</h2>
        <p class="section-sub">Une interface pensée pour être utilisée sur mobile, partout dans Bayeux.</p>

        <div class="phones-row">

          <!-- Téléphone 1 : carte -->
          <div class="phone-wrap phone-left">
            <div class="phone-label">Carte interactive</div>
            <div class="phone">
              <div class="phone-notch"></div>
              <div class="phone-screen">
                <!-- Topbar -->
                <div class="app-topbar">
                  <div class="app-topbar-logo">🚲</div>
                  <div>
                    <div class="app-topbar-title">Roue Libre</div>
                    <div class="app-topbar-sub">Bayeux</div>
                  </div>
                </div>
                <!-- Carte simulée -->
                <div class="app-map">
                  <div class="map-bg"></div>
                  <!-- Marqueurs -->
                  <div class="map-marker marker-green" style="top:38%;left:42%">
                    <div class="marker-bubble">🚲 8</div>
                  </div>
                  <div class="map-marker marker-green" style="top:55%;left:62%">
                    <div class="marker-bubble">🚲 6</div>
                  </div>
                  <div class="map-marker marker-orange" style="top:28%;left:55%">
                    <div class="marker-bubble">🚲 2</div>
                  </div>
                  <!-- Station card flottante -->
                  <div class="map-station-card">
                    <div class="map-station-name">Mairie de Bayeux</div>
                    <div class="map-station-avail">8 vélos disponibles</div>
                  </div>
                </div>
                <!-- Bottom nav -->
                <div class="app-bottomnav">
                  <div class="bn-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
                    <span>Carte</span>
                  </div>
                  <div class="bn-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Profil</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Téléphone 2 : réservation (central, plus grand) -->
          <div class="phone-wrap phone-center">
            <div class="phone-label phone-label-main">Réservation</div>
            <div class="phone phone-main">
              <div class="phone-notch"></div>
              <div class="phone-screen">
                <div class="app-sheet">
                  <div class="sheet-handle-bar"></div>
                  <div class="sheet-station-name">Mairie de Bayeux</div>
                  <div class="sheet-station-sub">Station de vélos libre-service</div>
                  <div class="sheet-badge">8/14</div>
                  <!-- Grille vélos -->
                  <div class="sheet-bikes">
                    <span v-for="i in 14" :key="i" :class="i <= 8 ? 'bike-avail' : 'bike-taken'">🚲</span>
                  </div>
                  <!-- Tarifs -->
                  <div class="sheet-tarifs">
                    <div class="tarif-btn tarif-selected">
                      <div class="tarif-btn-dur">1 heure</div>
                      <div class="tarif-btn-price">4 €</div>
                    </div>
                    <div class="tarif-btn">
                      <div class="tarif-btn-dur">Demi-journée</div>
                      <div class="tarif-btn-price">10 €</div>
                    </div>
                    <div class="tarif-btn">
                      <div class="tarif-btn-dur">Journée</div>
                      <div class="tarif-btn-price">18 €</div>
                    </div>
                    <div class="tarif-btn">
                      <div class="tarif-btn-dur">2 jours</div>
                      <div class="tarif-btn-price">35 €</div>
                    </div>
                  </div>
                  <!-- Input -->
                  <div class="sheet-input">Marie</div>
                  <div class="sheet-reserve-btn">Réserver — 4 €</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Téléphone 3 : confirmation -->
          <div class="phone-wrap phone-right">
            <div class="phone-label">Confirmation</div>
            <div class="phone">
              <div class="phone-notch"></div>
              <div class="phone-screen">
                <div class="app-confirm">
                  <div class="confirm-icon">🎉</div>
                  <div class="confirm-title">Paiement validé !</div>
                  <div class="confirm-sub">Bonne balade, <strong>Marie</strong> !<br>Votre vélo vous attend.</div>
                  <div class="confirm-station">
                    <span>📍</span>
                    <span>Mairie de Bayeux</span>
                  </div>
                  <div class="confirm-close-btn">Fermer</div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ══ FEATURES ══ -->
    <section id="features" class="features-section">
      <div class="container features-layout">
        <div class="features-text">
          <div class="section-tag">Pourquoi nous choisir</div>
          <h2>Pensé pour Bayeux<br>et ses visiteurs</h2>
          <p>Que vous soyez touriste ou habitant, Roue Libre vous offre la meilleure façon de découvrir la ville.</p>
          <RouterLink to="/app" class="btn-green" style="margin-top:8px;display:inline-flex">Essayer maintenant →</RouterLink>
        </div>
        <div class="features-cards">
          <div class="feat">
            <div class="feat-icon">⚡</div>
            <div>
              <h3>Vélos électriques</h3>
              <p>Assistance électrique pour parcourir la ville sans effort.</p>
            </div>
          </div>
          <div class="feat">
            <div class="feat-icon">📍</div>
            <div>
              <h3>3 stations en ville</h3>
              <p>Mairie, Gare SNCF et Saint-Patrice — toujours à portée.</p>
            </div>
          </div>
          <div class="feat">
            <div class="feat-icon">📱</div>
            <div>
              <h3>Sans inscription</h3>
              <p>Réservez en 30 secondes, juste avec votre prénom.</p>
            </div>
          </div>
          <div class="feat">
            <div class="feat-icon">🌿</div>
            <div>
              <h3>Zéro émission</h3>
              <p>Un transport propre pour une ville plus respirable.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══ TARIFS ══ -->
    <section id="tarifs" class="tarifs-section">
      <div class="container">
        <div class="section-tag">Tarifs</div>
        <h2>Une formule pour chaque besoin</h2>
        <p class="section-sub">Pas d'abonnement — payez uniquement ce que vous utilisez.</p>
        <div class="tarifs-grid">
          <div class="tarif-card">
            <div class="tarif-dur">1 heure</div>
            <div class="tarif-price">4 <span>€</span></div>
            <div class="tarif-note">Balade express</div>
          </div>
          <div class="tarif-card tarif-featured">
            <div class="tarif-popular">Populaire</div>
            <div class="tarif-dur">Demi-journée</div>
            <div class="tarif-price">10 <span>€</span></div>
            <div class="tarif-note">Visite de la ville</div>
          </div>
          <div class="tarif-card">
            <div class="tarif-dur">Journée</div>
            <div class="tarif-price">18 <span>€</span></div>
            <div class="tarif-note">Explorer la région</div>
          </div>
          <div class="tarif-card">
            <div class="tarif-dur">2 jours</div>
            <div class="tarif-price">35 <span>€</span></div>
            <div class="tarif-note">Week-end complet</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══ CTA ══ -->
    <section class="cta-section">
      <div class="cta-inner">
        <h2>Prêt à explorer Bayeux ?</h2>
        <p>Réservez votre vélo en quelques secondes, sans créer de compte.</p>
        <RouterLink to="/app" class="btn-green btn-lg">🚲 Louer maintenant</RouterLink>
      </div>
    </section>

    <!-- ══ FOOTER ══ -->
    <footer>
      <div class="footer-inner">
        <div class="footer-logo">
          <div class="logo-icon">🚲</div>
          <div>
            <strong>Roue Libre</strong>
            <span>Bayeux, Normandie</span>
          </div>
        </div>
        <div class="footer-links">
          <a href="#features">Le service</a>
          <a href="#tarifs">Tarifs</a>
          <RouterLink to="/app">L'application</RouterLink>
        </div>
        <div class="footer-contact">02 31 XX XX XX · 5 rue Saint-Patrice, 14400 Bayeux</div>
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'

const isScrolled = ref(false)
function onScroll() { isScrolled.value = window.scrollY > 40 }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.landing {
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  color: #111827;
  background: #fff;
  line-height: 1.6;
}

/* ── NAV ── */
.nav {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 200;
  height: 64px;
  padding: 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: background 0.3s, box-shadow 0.3s;
}
.nav.scrolled {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(12px);
  box-shadow: 0 1px 0 rgba(0,0,0,0.08);
}
.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.1rem;
  font-weight: 800;
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}
.nav.scrolled .nav-logo { color: #111827; }
.logo-icon {
  width: 34px; height: 34px;
  background: #5DC93A;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 6px;
}
.nav-links a {
  text-decoration: none;
  color: rgba(255,255,255,0.85);
  font-size: 0.9rem;
  font-weight: 500;
  padding: 8px 14px;
  border-radius: 8px;
  transition: color 0.2s, background 0.2s;
}
.nav.scrolled .nav-links a { color: #6B7280; }
.nav-links a:hover { color: white; background: rgba(255,255,255,0.12); }
.nav.scrolled .nav-links a:hover { color: #111827; background: #F4F6F8; }
.nav-cta {
  background: #5DC93A !important;
  color: white !important;
  font-weight: 700 !important;
  border-radius: 10px !important;
  padding: 9px 18px !important;
}
.nav-cta:hover { background: #4AAD2E !important; }

/* ── HERO ── */
.hero {
  position: relative;
  height: 100dvh;
  min-height: 600px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.hero-bg {
  position: absolute;
  inset: 0;
  overflow: hidden;
}
.hero-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center 20%;
  filter: brightness(0.75);
}
.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0,0,0,0.35) 0%,
    rgba(17,35,12,0.55) 60%,
    rgba(10,40,8,0.75) 100%
  );
}
.hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 0 24px;
  max-width: 780px;
}
.hero-badge {
  display: inline-block;
  background: rgba(93,201,58,0.2);
  border: 1px solid rgba(93,201,58,0.5);
  color: #9EE87A;
  font-size: 0.82rem;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 999px;
  margin-bottom: 24px;
  backdrop-filter: blur(4px);
}
.hero-content h1 {
  font-size: clamp(2.8rem, 8vw, 5.5rem);
  font-weight: 900;
  color: white;
  line-height: 1.05;
  letter-spacing: -1.5px;
  margin-bottom: 20px;
  text-shadow: 0 2px 20px rgba(0,0,0,0.3);
}
.hero-content h1 span { color: #5DC93A; }
.hero-content p {
  font-size: clamp(1rem, 2.5vw, 1.25rem);
  color: rgba(255,255,255,0.8);
  margin-bottom: 36px;
}
.hero-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 36px;
}
.btn-green {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #5DC93A;
  color: white;
  font-weight: 700;
  font-size: 1rem;
  padding: 15px 30px;
  border-radius: 14px;
  text-decoration: none;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 4px 20px rgba(93,201,58,0.4);
}
.btn-green:hover { background: #4AAD2E; transform: translateY(-2px); box-shadow: 0 8px 28px rgba(93,201,58,0.45); }
.btn-lg { font-size: 1.1rem; padding: 17px 36px; }
.btn-ghost {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,0.12);
  color: white;
  font-weight: 600;
  font-size: 1rem;
  padding: 15px 28px;
  border-radius: 14px;
  text-decoration: none;
  border: 1px solid rgba(255,255,255,0.25);
  backdrop-filter: blur(4px);
  transition: background 0.2s;
}
.btn-ghost:hover { background: rgba(255,255,255,0.2); }
.hero-pills {
  display: flex;
  gap: 10px;
  justify-content: center;
  flex-wrap: wrap;
}
.pill {
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.9);
  font-size: 0.8rem;
  font-weight: 500;
  padding: 6px 14px;
  border-radius: 999px;
  backdrop-filter: blur(4px);
}
.scroll-hint {
  position: absolute;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
  color: rgba(255,255,255,0.5);
  animation: bounce 2s infinite;
  text-decoration: none;
}
.scroll-hint svg { width: 24px; height: 24px; }
@keyframes bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50% { transform: translateX(-50%) translateY(6px); }
}

/* ── CONTAINER & TAGS ── */
.container { max-width: 1040px; margin: 0 auto; }
.section-tag {
  display: inline-block;
  background: #EDF9E8;
  color: #4AAD2E;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  padding: 5px 12px;
  border-radius: 999px;
  margin-bottom: 14px;
}
section h2 {
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  font-weight: 900;
  line-height: 1.15;
  letter-spacing: -0.5px;
  margin-bottom: 12px;
}
.section-sub {
  color: #6B7280;
  font-size: 1rem;
  margin-bottom: 52px;
  max-width: 480px;
}

/* ── SCREENSHOTS ── */
.screenshots-section {
  padding: 96px 24px 80px;
  background: #F8FFF5;
  text-align: center;
}
.phones-row {
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 20px;
  margin-top: 56px;
}
.phone-wrap { display: flex; flex-direction: column; align-items: center; gap: 14px; }
.phone-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #6B7280;
  background: white;
  border: 1px solid #E5E7EB;
  padding: 4px 12px;
  border-radius: 999px;
}
.phone-label-main { background: #5DC93A; color: white; border-color: #5DC93A; }
.phone-left, .phone-right { opacity: 0.82; transform: scale(0.88); transform-origin: bottom center; }

.phone {
  width: 200px;
  background: #1a1a1a;
  border-radius: 36px;
  padding: 10px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.22), 0 8px 20px rgba(0,0,0,0.12);
  position: relative;
}
.phone-main { width: 230px; }
.phone-notch {
  width: 60px; height: 10px;
  background: #1a1a1a;
  border-radius: 999px;
  margin: 0 auto 8px;
  position: relative;
  z-index: 2;
}
.phone-screen {
  background: #F4F6F8;
  border-radius: 28px;
  overflow: hidden;
  min-height: 380px;
  display: flex;
  flex-direction: column;
}
.phone-main .phone-screen { min-height: 440px; }

/* Topbar mockup */
.app-topbar {
  background: white;
  padding: 10px 12px;
  display: flex;
  align-items: center;
  gap: 8px;
  border-bottom: 1px solid #E5E7EB;
  flex-shrink: 0;
}
.app-topbar-logo { font-size: 1.2rem; }
.app-topbar-title { font-size: 0.72rem; font-weight: 800; line-height: 1; color: #111827; }
.app-topbar-sub { font-size: 0.6rem; color: #6B7280; }

/* Carte mockup */
.app-map {
  flex: 1;
  position: relative;
  overflow: hidden;
  min-height: 240px;
}
.map-bg {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px),
    linear-gradient(135deg, #e8f5e0 0%, #dce8f5 50%, #e5ead0 100%);
  background-size: 20px 20px, 20px 20px, 100% 100%;
}
/* Fake roads */
.app-map::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    linear-gradient(rgba(255,255,255,0.6) 2px, transparent 2px),
    linear-gradient(90deg, rgba(255,255,255,0.6) 2px, transparent 2px);
  background-size: 40px 40px;
}
.map-marker {
  position: absolute;
  transform: translate(-50%, -50%);
  z-index: 3;
}
.marker-bubble {
  background: #5DC93A;
  color: white;
  font-size: 0.55rem;
  font-weight: 700;
  padding: 3px 6px;
  border-radius: 999px;
  white-space: nowrap;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
.marker-orange .marker-bubble { background: #F59E0B; }
.map-station-card {
  position: absolute;
  bottom: 8px; left: 8px; right: 8px;
  background: white;
  border-radius: 10px;
  padding: 8px 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  z-index: 4;
}
.map-station-name { font-size: 0.65rem; font-weight: 800; color: #111827; }
.map-station-avail { font-size: 0.58rem; color: #5DC93A; font-weight: 600; margin-top: 2px; }

/* Bottom nav mockup */
.app-bottomnav {
  background: white;
  border-top: 1px solid #E5E7EB;
  display: flex;
  flex-shrink: 0;
  padding: 6px 0;
}
.bn-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
  color: #9CA3AF;
  font-size: 0.5rem;
  font-weight: 500;
}
.bn-item svg { width: 14px; height: 14px; }
.bn-item.active { color: #5DC93A; }
.bn-item.active svg { stroke: #5DC93A; }

/* Sheet mockup */
.app-sheet {
  flex: 1;
  background: white;
  padding: 10px 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.sheet-handle-bar {
  width: 28px; height: 3px;
  background: #E5E7EB;
  border-radius: 999px;
  margin: 0 auto 4px;
}
.sheet-station-name { font-size: 0.75rem; font-weight: 900; color: #111827; }
.sheet-station-sub  { font-size: 0.58rem; color: #6B7280; margin-top: -4px; }
.sheet-badge {
  display: inline-block;
  background: #EDF9E8;
  color: #4AAD2E;
  font-size: 0.6rem;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 999px;
  align-self: flex-end;
  margin-top: -24px;
}
.sheet-bikes {
  display: flex;
  flex-wrap: wrap;
  gap: 2px;
  background: #F4F6F8;
  border-radius: 8px;
  padding: 6px;
}
.sheet-bikes span { font-size: 0.7rem; line-height: 1; }
.bike-taken { opacity: 0.15; filter: grayscale(1); }
.sheet-tarifs {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4px;
}
.tarif-btn {
  border: 1.5px solid #E5E7EB;
  border-radius: 8px;
  padding: 5px 4px;
  text-align: center;
  background: white;
}
.tarif-selected {
  border-color: #5DC93A;
  background: #EDF9E8;
}
.tarif-btn-dur { font-size: 0.52rem; color: #6B7280; font-weight: 500; }
.tarif-btn-price { font-size: 0.75rem; font-weight: 900; color: #111827; }
.tarif-selected .tarif-btn-price { color: #4AAD2E; }
.sheet-input {
  border: 1.5px solid #E5E7EB;
  border-radius: 8px;
  padding: 6px 8px;
  font-size: 0.65rem;
  color: #111827;
  background: #F4F6F8;
}
.sheet-reserve-btn {
  background: #5DC93A;
  color: white;
  text-align: center;
  border-radius: 8px;
  padding: 8px;
  font-size: 0.65rem;
  font-weight: 800;
}

/* Confirm mockup */
.app-confirm {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 16px 12px;
  gap: 8px;
  background: white;
}
.confirm-icon { font-size: 2.2rem; }
.confirm-title { font-size: 0.9rem; font-weight: 900; color: #111827; }
.confirm-sub { font-size: 0.62rem; color: #6B7280; line-height: 1.5; }
.confirm-station {
  display: flex;
  align-items: center;
  gap: 5px;
  background: #EDF9E8;
  color: #4AAD2E;
  font-size: 0.62rem;
  font-weight: 700;
  padding: 5px 10px;
  border-radius: 8px;
}
.confirm-close-btn {
  background: #5DC93A;
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 7px 20px;
  border-radius: 8px;
  margin-top: 4px;
}

/* ── FEATURES ── */
.features-section {
  padding: 96px 24px;
  background: white;
}
.features-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 64px;
  align-items: center;
}
.features-text h2 { margin-bottom: 16px; }
.features-text p { color: #6B7280; margin-bottom: 28px; }
.features-cards { display: flex; flex-direction: column; gap: 16px; }
.feat {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 18px 20px;
  border: 1px solid #E5E7EB;
  border-radius: 14px;
  background: #FAFAFA;
  transition: box-shadow 0.2s, transform 0.2s;
}
.feat:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); transform: translateY(-2px); }
.feat-icon {
  width: 42px; height: 42px;
  background: #EDF9E8;
  border-radius: 11px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.feat h3 { font-size: 0.95rem; font-weight: 800; margin-bottom: 3px; }
.feat p  { font-size: 0.82rem; color: #6B7280; }

/* ── TARIFS ── */
.tarifs-section {
  padding: 96px 24px;
  background: #F8FFF5;
  text-align: center;
}
.tarifs-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-top: 8px;
}
.tarif-card {
  background: white;
  border: 2px solid #E5E7EB;
  border-radius: 20px;
  padding: 32px 20px;
  transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
  position: relative;
}
.tarif-card:hover { border-color: #5DC93A; transform: translateY(-4px); box-shadow: 0 12px 36px rgba(93,201,58,0.12); }
.tarif-card.tarif-featured { border-color: #5DC93A; background: linear-gradient(160deg, #EDF9E8 0%, white 100%); }
.tarif-popular {
  position: absolute;
  top: -12px; left: 50%;
  transform: translateX(-50%);
  background: #5DC93A;
  color: white;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 4px 14px;
  border-radius: 999px;
  white-space: nowrap;
}
.tarif-dur { font-size: 0.88rem; color: #6B7280; font-weight: 600; margin-bottom: 12px; }
.tarif-price { font-size: 3rem; font-weight: 900; color: #111827; line-height: 1; }
.tarif-price span { font-size: 1.4rem; color: #6B7280; }
.tarif-note { font-size: 0.78rem; color: #9CA3AF; margin-top: 10px; }

/* ── CTA ── */
.cta-section {
  padding: 100px 24px;
  background: linear-gradient(135deg, #0f2b0a 0%, #1a4a10 50%, #1e5a13 100%);
  text-align: center;
}
.cta-inner h2 { color: white; margin-bottom: 14px; }
.cta-inner p  { color: rgba(255,255,255,0.65); margin-bottom: 36px; }

/* ── FOOTER ── */
footer {
  background: #0d1f09;
  padding: 32px 40px;
}
.footer-inner {
  max-width: 1040px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}
.footer-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  color: white;
  font-size: 0.95rem;
}
.footer-logo strong { display: block; font-size: 1rem; }
.footer-logo span { font-size: 0.72rem; color: rgba(255,255,255,0.45); }
.footer-links { display: flex; gap: 24px; }
.footer-links a {
  color: rgba(255,255,255,0.5);
  text-decoration: none;
  font-size: 0.85rem;
  transition: color 0.2s;
}
.footer-links a:hover { color: white; }
.footer-contact { font-size: 0.78rem; color: rgba(255,255,255,0.35); }

/* ── RESPONSIVE ── */
@media (max-width: 860px) {
  .features-layout { grid-template-columns: 1fr; gap: 40px; }
  .tarifs-grid { grid-template-columns: 1fr 1fr; }
  .phones-row { gap: 8px; }
  .phone-main { width: 200px; }
  .phone { width: 160px; }
}
@media (max-width: 600px) {
  .nav { padding: 0 16px; }
  .nav-links a:not(.nav-cta) { display: none; }
  .phones-row { flex-direction: column; align-items: center; }
  .phone-left, .phone-right { display: none; }
  .phone-main { width: 240px; }
  .tarifs-grid { grid-template-columns: 1fr 1fr; }
  .hero-content h1 { letter-spacing: -1px; }
  .footer-inner { flex-direction: column; text-align: center; }
  .footer-links { justify-content: center; }
}
</style>
