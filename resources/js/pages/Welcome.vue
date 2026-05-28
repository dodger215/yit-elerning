<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
  videos: {
    type: Array,
    default: () => [],
  },
  appName: {
    type: String,
    default: 'EduConnect',
  },
});

const isScrolled = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
  <Head :title="'Welcome to ' + appName" />
  
  <div class="home-container">
    <!-- Navigation -->
    <nav class="navigation" :class="{ 'nav-scrolled': isScrolled }">
      <div class="nav-content">
        <!-- Logo section -->
        <div class="nav-logo">
          <Link href="/" class="logo-link">
            <img src="/images/logo.png" alt="Youth In Tech" class="logo-img" />
            <span class="logo-text">{{ appName }}</span>
          </Link>
        </div>

        <!-- Navigation links -->
        <div class="nav-links">
          <Link href="/" class="nav-link active">Home</Link>
          <!-- <Link href="/dashboard" class="nav-link">Login</Link> -->
          <Link href="/faqs" class="nav-link">FAQs</Link>
          <Link href="/terms" class="nav-link">Terms</Link>
        </div>

        <!-- Buttons section -->
        <div class="nav-buttons">
          <Link href="/auth/login" class="nav-link sign-in">Log in</Link>
          <Link href="/auth/register" class="btn-primary">Get Started</Link>
        </div>
      </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
      </div>
      
      <div class="hero-content-wrapper">
        <div class="hero-text">
          <div class="badge">Premium E-Learning Platform</div>
          <h1 class="page-title">
            Unlock Your <br/>
            <span class="text-blue">Potential</span> Today
          </h1>
          <p class="page-subtitle">
            A comprehensive platform connecting students with vetted tutors for personalized, high-quality learning experiences.
          </p>
          <div class="hero-actions">
            <Link href="/register" class="btn-primary btn-lg">Start Learning</Link>
            <Link href="/courses" class="btn-secondary btn-lg">Explore Courses</Link>
          </div>
        </div>
        
        <div class="hero-media">
          <div class="media-glass-panel">
            <div v-if="videos && videos.length > 0" class="video-grid">
               <div v-for="video in videos.slice(0, 4)" :key="video.id" class="video-item group">
                 <div class="video-thumb-wrapper">
                   <img :src="video.thumbnail_url || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop'" :alt="video.title" />
                   <div class="play-button">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                   </div>
                 </div>
                 <div class="video-info">
                   <h4>{{ video.title }}</h4>
                 </div>
               </div>
            </div>
            <div v-else class="media-placeholder">
              <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="placeholder-icon"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
              <p>Exciting content coming soon</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Platform Features Section -->
    <section class="platform-features">
      <div class="features-container">
        <div class="section-header">
          <h2 class="section-title">Platform Features</h2>
          <p class="section-description">Everything you need to succeed in your educational journey.</p>
        </div>
        
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon bg-blue-light">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <h3 class="feature-title">AI-Powered Content</h3>
            <p class="feature-description">
              Advanced learning experiences and interactive quizzes with cutting-edge AI assistance.
            </p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon bg-blue-light">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
            </div>
            <h3 class="feature-title">Flexible Payments</h3>
            <p class="feature-description">
              Multiple secure payment options designed for accessibility and peace of mind.
            </p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon bg-blue-light">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="feature-title">Quality Assurance</h3>
            <p class="feature-description">
              Rigorous tutor verification processes ensure you receive the highest quality education.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Cards Section -->
    <section class="cards-section">
      <div class="section-header">
        <h2 class="section-title text-white">Join Our Community</h2>
        <p class="section-description text-blue-100">Whether you want to learn or teach, we have a place for you.</p>
      </div>
      
      <div class="card-container">
        <!-- Tutor Card -->
        <div class="action-card">
          <div class="card-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
          </div>
          <h2 class="card-title">Become a Tutor</h2>
          <p class="card-text">
            Share your knowledge and earn by teaching students worldwide. Join our network of experts.
          </p>
          
          <ul class="card-features">
            <li>
              <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              Create and sell courses
            </li>
            <li>
              <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              Flexible payment options
            </li>
            <li>
              <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              AI-powered content creation
            </li>
          </ul>
          
          <div class="card-action">
            <Link href="/register?role=instructor" class="btn-primary w-full">Get Started as a Tutor</Link>
          </div>
        </div>

        <!-- Student Card -->
        <div class="action-card student">
          <div class="card-icon-wrapper student-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
          </div>
          <h2 class="card-title">Start Learning</h2>
          <p class="card-text">
            Discover your potential through tailored learning paths and expert mentorship.
          </p>
          
          <ul class="card-features">
            <li>
              <svg class="check-icon student-check" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              Personalized learning paths
            </li>
            <li>
              <svg class="check-icon student-check" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              Transparent payment plans
            </li>
            <li>
              <svg class="check-icon student-check" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
              Progress tracking
            </li>
          </ul>
          
          <div class="card-action">
            <Link href="/register?role=student" class="btn-primary w-full">Get Started as a Student</Link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.home-container {
  --blue-50: #eff6ff;
  --blue-100: #dbeafe;
  --blue-200: #bfdbfe;
  --blue-300: #93c5fd;
  --blue-500: #3b82f6;
  --blue-600: #2563eb;
  --blue-700: #1d4ed8;
  --blue-900: #1e3a8a;
  --blue-950: #172554;
  --white: #ffffff;
  --slate-50: #f8fafc;
  --slate-100: #f1f5f9;
  --slate-200: #e2e8f0;
  --slate-400: #94a3b8;
  --slate-600: #475569;
  --slate-700: #334155;
  --slate-800: #1e293b;
  --slate-900: #0f172a;
  
  min-height: 100vh;
  background-color: var(--white);
  color: var(--slate-800);
  overflow-x: hidden;
}

* {
  box-sizing: border-box;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Navigation */
.navigation {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  transition: all 0.3s ease;
  background-color: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid transparent;
}

.navigation.nav-scrolled {
  background-color: rgba(255, 255, 255, 0.98);
  border-bottom: 1px solid var(--slate-100);
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
}

.nav-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1.25rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.nav-logo {
  display: flex;
  align-items: center;
}

.logo-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
}

.logo-img {
  width: 40px;
  height: 40px;
  object-fit: contain;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--slate-900);
  letter-spacing: -0.02em;
}

.nav-links {
  display: flex;
  gap: 2.5rem;
  align-items: center;
}

.nav-link {
  color: var(--slate-600);
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.2s;
}

.nav-link:hover, .nav-link.active {
  color: var(--blue-600);
}

.nav-buttons {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.sign-in {
  color: var(--slate-900);
}

/* Buttons */
.btn-primary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: var(--blue-600);
  color: white !important;
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 1px solid transparent;
  box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.25);
}

.btn-primary:hover {
  background-color: var(--blue-700);
  color: white !important;
  box-shadow: 0 6px 20px 0 rgba(37, 99, 235, 0.4);
  transform: translateY(-2px);
}

.btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: var(--slate-800);
  color: white !important;
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 1px solid var(--slate-700);
  box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
  background-color: var(--slate-900);
  color: white !important;
  border-color: var(--slate-800);
  box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.btn-outline-blue {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: transparent;
  color: var(--blue-600);
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 2px solid var(--blue-200);
}

.btn-outline-blue:hover {
  border-color: var(--blue-600);
  background-color: var(--blue-50);
}

.btn-lg {
  padding: 1rem 2rem;
  font-size: 1.05rem;
}

.w-full {
  width: 100%;
}

/* Hero Section */
.hero-section {
  position: relative;
  padding: 12rem 2rem 8rem;
  background-color: var(--blue-50);
  overflow: hidden;
}

.hero-bg-shapes {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 0;
  pointer-events: none;
}

.shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
}

.shape-1 {
  top: -10%;
  right: -5%;
  width: 600px;
  height: 600px;
  background: rgba(59, 130, 246, 0.2);
}

.shape-2 {
  bottom: -20%;
  left: -10%;
  width: 500px;
  height: 500px;
  background: rgba(96, 165, 250, 0.15);
}

.hero-content-wrapper {
  max-width: 1300px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 4rem;
  position: relative;
  z-index: 1;
}

.hero-text {
  flex: 1;
  max-width: 600px;
}

.badge {
  display: inline-block;
  padding: 0.5rem 1.25rem;
  background-color: var(--blue-100);
  color: var(--blue-700);
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.page-title {
  font-size: 4rem;
  font-weight: 800;
  line-height: 1.1;
  color: var(--slate-900);
  margin-bottom: 1.5rem;
  letter-spacing: -0.03em;
}

.text-blue {
  color: var(--blue-600);
}

.page-subtitle {
  font-size: 1.25rem;
  color: var(--slate-600);
  margin-bottom: 2.5rem;
  line-height: 1.6;
}

.hero-actions {
  display: flex;
  gap: 1rem;
}

.hero-media {
  flex: 1;
  display: flex;
  justify-content: flex-end;
}

.media-glass-panel {
  background: rgba(255, 255, 255, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(20px);
  padding: 1.5rem;
  border-radius: 24px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 600px;
}

.video-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.video-item {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
  cursor: pointer;
}

.video-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.video-thumb-wrapper {
  position: relative;
  aspect-ratio: 16/10;
  overflow: hidden;
}

.video-thumb-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.video-item:hover .video-thumb-wrapper img {
  transform: scale(1.05);
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--blue-600);
  opacity: 0;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.play-button svg {
  width: 20px;
  height: 20px;
  margin-left: 2px;
}

.video-item:hover .play-button {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1.1);
}

.video-info {
  padding: 1rem;
}

.video-info h4 {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--slate-800);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.media-placeholder {
  aspect-ratio: 4/3;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: var(--slate-50);
  border-radius: 16px;
  border: 2px dashed var(--blue-200);
  color: var(--slate-400);
}

.placeholder-icon {
  color: var(--blue-300);
  margin-bottom: 1rem;
}

/* Platform Features Section */
.platform-features {
  padding: 8rem 2rem;
  background-color: var(--white);
}

.features-container {
  max-width: 1300px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 5rem;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--slate-900);
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
}

.section-description {
  font-size: 1.125rem;
  color: var(--slate-600);
  max-width: 600px;
  margin: 0 auto;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 3rem;
}

.feature-card {
  padding: 2.5rem;
  background-color: var(--white);
  border-radius: 24px;
  border: 1px solid var(--slate-100);
  box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.1);
  border-color: var(--blue-100);
}

.feature-icon {
  width: 60px;
  height: 60px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.feature-icon svg {
  width: 28px;
  height: 28px;
  color: var(--blue-600);
}

.bg-blue-light {
  background-color: var(--blue-50);
}

.feature-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--slate-900);
  margin-bottom: 1rem;
}

.feature-description {
  color: var(--slate-600);
  line-height: 1.6;
}

/* Cards Section */
.cards-section {
  padding: 8rem 2rem;
  background-color: var(--blue-950);
  position: relative;
  overflow: hidden;
}

.cards-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: radial-gradient(circle at top right, rgba(37, 99, 235, 0.3), transparent 50%),
                    radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.2), transparent 50%);
  pointer-events: none;
}

.text-white { color: white; }
.text-blue-100 { color: var(--blue-100); }

.card-container {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 3rem;
  position: relative;
  z-index: 1;
}

.action-card {
  background: var(--white);
  border-radius: 32px;
  padding: 3rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  display: flex;
  flex-direction: column;
}

.card-icon-wrapper {
  width: 72px;
  height: 72px;
  background-color: var(--blue-50);
  color: var(--blue-600);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}

.card-icon-wrapper svg {
  width: 36px;
  height: 36px;
}

.student-icon {
  background-color: var(--slate-100);
  color: var(--slate-700);
}

.card-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--slate-900);
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
}

.card-text {
  font-size: 1.05rem;
  color: var(--slate-600);
  line-height: 1.6;
  margin-bottom: 2.5rem;
}

.card-features {
  list-style: none;
  padding: 0;
  margin: 0 0 3rem 0;
}

.card-features li {
  display: flex;
  align-items: center;
  gap: 1rem;
  color: var(--slate-700);
  margin-bottom: 1rem;
  font-weight: 500;
}

.check-icon {
  width: 24px;
  height: 24px;
  color: var(--blue-600);
  background-color: var(--blue-50);
  border-radius: 50%;
  padding: 4px;
}

.student-check {
  color: var(--slate-700);
  background-color: var(--slate-100);
}

.card-action {
  margin-top: auto;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .hero-content-wrapper {
    flex-direction: column;
    text-align: center;
  }
  
  .hero-text {
    max-width: 100%;
  }
  
  .page-title {
    font-size: 3.5rem;
  }
  
  .hero-actions {
    justify-content: center;
  }
  
  .hero-media {
    justify-content: center;
  }
  
  .features-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .nav-links, .nav-buttons {
    display: none;
  }
  
  .page-title {
    font-size: 2.5rem;
  }
  
  .hero-section {
    padding: 8rem 1.5rem 5rem;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
  }
  
  .card-container {
    grid-template-columns: 1fr;
  }
  
  .action-card {
    padding: 2rem;
  }
  
  .hero-actions {
    flex-direction: column;
  }
  
  .btn-lg {
    width: 100%;
  }
}
</style>
