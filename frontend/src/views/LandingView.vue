<template>
  <div class="landing">

    <!-- NAV -->
    <nav class="nav">
      <a href="/" class="nav-logo">
        <div class="nav-logo-icon">🚲</div>
        Roue Libre
      </a>
      <div class="nav-links">
        <a href="#comment">Comment ça marche</a>
        <a href="#tarifs">Tarifs</a>
        <a href="#stations">Stations</a>
        <RouterLink to="/app" class="btn-nav">Accéder à l'app</RouterLink>
      </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
      <div class="hero-badge">🌿 100% électrique · 100% écologique</div>
      <h1>Explorez Bayeux<br>à <span>vélo électrique</span></h1>
      <p>Location de vélos et trottinettes électriques en libre-service. Simple, rapide et écologique.</p>
      <div class="hero-cta">
        <RouterLink to="/app" class="btn-primary">🚲 Louer maintenant</RouterLink>
        <a href="#comment" class="btn-secondary">Découvrir le service</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat">
          <div class="hero-stat-value">3</div>
          <div class="hero-stat-label">Stations en ville</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-value">4<span>€</span></div>
          <div class="hero-stat-label">À partir de / heure</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-value">24<span>/7</span></div>
          <div class="hero-stat-label">Disponible à toute heure</div>
        </div>
      </div>
    </section>

    <!-- AVANTAGES -->
    <section class="features">
      <div class="container">
        <div class="section-label">Pourquoi Roue Libre ?</div>
        <h2 class="section-title">La mobilité douce<br>au cœur de Bayeux</h2>
        <p class="section-desc">Un service pensé pour vous simplifier la vie, que vous soyez habitant ou touriste.</p>
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon icon-green">⚡</div>
            <h3>100% Électrique</h3>
            <p>Vélos et trottinettes à assistance électrique pour parcourir la ville sans effort, même les côtes.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon icon-blue">📍</div>
            <h3>3 Stations stratégiques</h3>
            <p>Mairie, Gare SNCF et Place Saint-Patrice — des points de retrait au plus proche de vos besoins.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon icon-green">📱</div>
            <h3>Réservation en ligne</h3>
            <p>Réservez votre vélo depuis votre smartphone en quelques secondes, sans inscription.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon icon-blue">💚</div>
            <h3>Zéro émission</h3>
            <p>Déplacez-vous de manière responsable et contribuez à réduire la pollution en centre-ville.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon icon-green">💶</div>
            <h3>Tarifs accessibles</h3>
            <p>Des formules adaptées à tous les usages, de la balade d'une heure au week-end complet.</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon icon-blue">🕐</div>
            <h3>Disponible 24h/24</h3>
            <p>Récupérez votre vélo quand vous voulez, les stations sont accessibles à toute heure.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- COMMENT ÇA MARCHE -->
    <section id="comment">
      <div class="container">
        <div class="section-label">Simple comme bonjour</div>
        <h2 class="section-title">Comment ça marche ?</h2>
        <p class="section-desc">En 3 étapes, votre vélo est prêt à partir.</p>
        <div class="steps-grid">
          <div class="step-card">
            <div class="step-number">1</div>
            <h3>Choisissez une station</h3>
            <p>Sélectionnez la station la plus proche de vous sur la carte interactive.</p>
          </div>
          <div class="step-card">
            <div class="step-number">2</div>
            <h3>Réservez votre vélo</h3>
            <p>Choisissez votre durée, entrez votre prénom et confirmez la réservation.</p>
          </div>
          <div class="step-card">
            <div class="step-number">3</div>
            <h3>Payez &amp; partez !</h3>
            <p>Réglez en ligne et récupérez votre vélo directement à la station. Bonne balade !</p>
          </div>
        </div>
      </div>
    </section>

    <!-- TARIFS -->
    <section id="tarifs" class="pricing">
      <div class="container">
        <div class="section-label">Nos tarifs</div>
        <h2 class="section-title">Une formule pour chaque besoin</h2>
        <p class="section-desc">Vélos &amp; trottinettes électriques — pas d'abonnement, payez uniquement ce que vous utilisez.</p>
        <div class="pricing-grid">
          <div class="pricing-card">
            <div class="pricing-duration">1 heure</div>
            <div class="pricing-price"><sup>€</sup>4</div>
            <div class="pricing-note">Idéal pour une balade rapide</div>
          </div>
          <div class="pricing-card featured">
            <div class="featured-badge">Populaire</div>
            <div class="pricing-duration">Demi-journée</div>
            <div class="pricing-price"><sup>€</sup>10</div>
            <div class="pricing-note">Parfait pour visiter la ville</div>
          </div>
          <div class="pricing-card">
            <div class="pricing-duration">Journée</div>
            <div class="pricing-price"><sup>€</sup>18</div>
            <div class="pricing-note">Explorez la région à votre rythme</div>
          </div>
          <div class="pricing-card">
            <div class="pricing-duration">2 jours</div>
            <div class="pricing-price"><sup>€</sup>35</div>
            <div class="pricing-note">Pour un week-end inoubliable</div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATIONS -->
    <section id="stations">
      <div class="container">
        <div class="section-label">Où nous trouver</div>
        <h2 class="section-title">Nos stations à Bayeux</h2>
        <p class="section-desc">3 points de retrait stratégiquement placés dans la ville.</p>
        <div class="stations-grid">
          <div class="station-card">
            <div class="station-pin">🏛️</div>
            <div class="station-info">
              <h3>Mairie de Bayeux</h3>
              <p>Centre-ville — accès facile depuis la place</p>
            </div>
          </div>
          <div class="station-card">
            <div class="station-pin">🚉</div>
            <div class="station-info">
              <h3>Gare de Bayeux</h3>
              <p>Idéal pour les voyageurs arrivant en train</p>
            </div>
          </div>
          <div class="station-card">
            <div class="station-pin">📍</div>
            <div class="station-info">
              <h3>Saint-Patrice</h3>
              <p>Quartier résidentiel — proche des commerces</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA FINAL -->
    <section class="cta-section">
      <h2>Prêt à prendre la route ?</h2>
      <p>Réservez votre vélo électrique en quelques secondes.</p>
      <RouterLink to="/app" class="btn-white">🚲 Réserver maintenant</RouterLink>
    </section>

    <!-- FOOTER -->
    <footer>
      <p><strong>Roue Libre</strong> — Location vélo &amp; trottinette électrique à Bayeux</p>
      <p style="margin-top:6px">5 rue Saint-Patrice, 14400 Bayeux · 02 31 XX XX XX</p>
    </footer>

  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
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
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid #E5E7EB;
  padding: 0 24px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.2rem;
  font-weight: 800;
  color: #111827;
  text-decoration: none;
}
.nav-logo-icon {
  width: 36px;
  height: 36px;
  background: #5DC93A;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 8px;
}
.nav-links a {
  text-decoration: none;
  color: #6B7280;
  font-size: 0.9rem;
  font-weight: 500;
  padding: 8px 14px;
  border-radius: 8px;
  transition: background 0.15s, color 0.15s;
}
.nav-links a:hover { background: #F4F6F8; color: #111827; }
.btn-nav {
  background: #5DC93A !important;
  color: white !important;
  font-weight: 700 !important;
  border-radius: 8px;
}
.btn-nav:hover { background: #4AAD2E !important; }

/* ── HERO ── */
.hero {
  background: linear-gradient(135deg, #f0fde9 0%, #e3f6fc 100%);
  padding: 80px 24px 100px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 400px; height: 400px;
  background: radial-gradient(circle, rgba(93,201,58,0.15) 0%, transparent 70%);
  pointer-events: none;
}
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #EDF9E8;
  color: #4AAD2E;
  font-size: 0.82rem;
  font-weight: 700;
  padding: 6px 14px;
  border-radius: 999px;
  margin-bottom: 24px;
  border: 1px solid rgba(93,201,58,0.25);
}
.hero h1 {
  font-size: clamp(2.2rem, 6vw, 3.8rem);
  font-weight: 900;
  line-height: 1.15;
  margin-bottom: 20px;
  letter-spacing: -0.5px;
}
.hero h1 span { color: #5DC93A; }
.hero p {
  font-size: clamp(1rem, 2.5vw, 1.2rem);
  color: #6B7280;
  max-width: 520px;
  margin: 0 auto 36px;
}
.hero-cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #5DC93A;
  color: white;
  font-weight: 700;
  font-size: 1rem;
  padding: 14px 28px;
  border-radius: 14px;
  text-decoration: none;
  transition: background 0.2s, transform 0.1s;
}
.btn-primary:hover { background: #4AAD2E; transform: translateY(-1px); }
.btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: white;
  color: #111827;
  font-weight: 600;
  font-size: 1rem;
  padding: 14px 28px;
  border-radius: 14px;
  text-decoration: none;
  border: 2px solid #E5E7EB;
  transition: border-color 0.2s, transform 0.1s;
}
.btn-secondary:hover { border-color: #5DC93A; transform: translateY(-1px); }
.hero-stats { display: flex; justify-content: center; gap: 40px; margin-top: 60px; flex-wrap: wrap; }
.hero-stat { text-align: center; }
.hero-stat-value { font-size: 2rem; font-weight: 900; color: #111827; }
.hero-stat-value span { color: #5DC93A; }
.hero-stat-label { font-size: 0.82rem; color: #6B7280; font-weight: 500; }

/* ── SECTIONS COMMUNES ── */
section { padding: 80px 24px; }
.container { max-width: 1000px; margin: 0 auto; }
.section-label {
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #4AAD2E;
  margin-bottom: 10px;
}
.section-title {
  font-size: clamp(1.6rem, 4vw, 2.4rem);
  font-weight: 900;
  line-height: 1.2;
  margin-bottom: 14px;
}
.section-desc { color: #6B7280; font-size: 1rem; max-width: 500px; margin-bottom: 48px; }

/* ── AVANTAGES ── */
.features { background: #F4F6F8; }
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
.feature-card {
  background: white;
  border-radius: 16px;
  padding: 28px 24px;
  border: 1px solid #E5E7EB;
  transition: box-shadow 0.2s, transform 0.2s;
}
.feature-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-3px); }
.feature-icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
  margin-bottom: 16px;
}
.icon-green { background: #EDF9E8; }
.icon-blue  { background: #E3F6FC; }
.feature-card h3 { font-size: 1.05rem; font-weight: 800; margin-bottom: 8px; }
.feature-card p  { font-size: 0.88rem; color: #6B7280; line-height: 1.6; }

/* ── COMMENT ÇA MARCHE ── */
.steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
.step-card {
  background: white;
  border-radius: 16px;
  padding: 28px 24px;
  border: 1px solid #E5E7EB;
  text-align: center;
}
.step-number {
  width: 40px; height: 40px;
  background: #5DC93A;
  color: white;
  font-size: 1rem; font-weight: 900;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}
.step-card h3 { font-size: 1rem; font-weight: 800; margin-bottom: 8px; }
.step-card p  { font-size: 0.85rem; color: #6B7280; }

/* ── TARIFS ── */
.pricing { background: #F4F6F8; }
.pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
.pricing-card {
  background: white;
  border-radius: 16px;
  padding: 28px 20px;
  border: 2px solid #E5E7EB;
  text-align: center;
  transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
}
.pricing-card:hover { border-color: #5DC93A; box-shadow: 0 8px 32px rgba(93,201,58,0.12); transform: translateY(-3px); }
.pricing-card.featured {
  border-color: #5DC93A;
  background: linear-gradient(135deg, #EDF9E8, white);
}
.featured-badge {
  display: inline-block;
  background: #5DC93A;
  color: white;
  font-size: 0.72rem; font-weight: 700;
  padding: 3px 10px;
  border-radius: 999px;
  margin-bottom: 12px;
}
.pricing-duration { font-size: 0.9rem; color: #6B7280; font-weight: 600; margin-bottom: 10px; }
.pricing-price { font-size: 2.4rem; font-weight: 900; color: #111827; line-height: 1; }
.pricing-price sup { font-size: 1.2rem; vertical-align: super; }
.pricing-note { font-size: 0.78rem; color: #6B7280; margin-top: 8px; }

/* ── STATIONS ── */
.stations-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
.station-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  border: 1px solid #E5E7EB;
  display: flex; align-items: center; gap: 16px;
  transition: box-shadow 0.2s, transform 0.2s;
}
.station-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
.station-pin {
  width: 48px; height: 48px;
  border-radius: 14px;
  background: #EDF9E8;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; flex-shrink: 0;
}
.station-info h3 { font-size: 0.98rem; font-weight: 800; margin-bottom: 4px; }
.station-info p  { font-size: 0.8rem; color: #6B7280; }

/* ── CTA FINAL ── */
.cta-section {
  background: linear-gradient(135deg, #4AAD2E 0%, #5DC93A 100%);
  text-align: center;
  padding: 80px 24px;
}
.cta-section h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; color: white; margin-bottom: 14px; }
.cta-section p  { color: rgba(255,255,255,0.8); font-size: 1rem; margin-bottom: 36px; }
.btn-white {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: white;
  color: #4AAD2E;
  font-weight: 700;
  font-size: 1rem;
  padding: 14px 32px;
  border-radius: 14px;
  text-decoration: none;
  transition: transform 0.1s, box-shadow 0.2s;
}
.btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

/* ── FOOTER ── */
footer {
  background: #111827;
  color: rgba(255,255,255,0.6);
  text-align: center;
  padding: 32px 24px;
  font-size: 0.85rem;
}
footer strong { color: white; }

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
  .nav { padding: 0 16px; }
  .nav-links a:not(.btn-nav) { display: none; }
  section { padding: 56px 16px; }
  .hero { padding: 56px 16px 72px; }
  .hero-stats { gap: 24px; }
}
</style>
