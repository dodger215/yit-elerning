<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<footer class="public-footer">
    <div class="footer-texture"></div>
    <div class="footer-content">
        <div class="footer-top">
            <div class="footer-brand-column">
                <Link href="/" class="footer-logo-link">
                    <img src="/images/logo.png" alt="YIT" class="footer-logo-img" />
                </Link>
                <div class="footer-slogan">
                    <p>Tutor Platform For All</p>
                    <p>Works Of Life</p>
                </div>
                <div class="footer-contact-row">
                    <span>📞</span>
                    <span>1 (800) 410-8896</span>
                </div>
            </div>

            <div class="footer-links-grid">
                <div class="footer-col">
                    <h4 class="footer-col-title">Platform</h4>
                    <a href="#about" class="footer-link">About Us</a>
                    <a href="#courses" class="footer-link">Courses</a>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#faq" class="footer-link">FAQ</a>
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-title">Get Started</h4>
                    <Link href="/auth/register" class="footer-link">Create Account</Link>
                    <Link href="/auth/login" class="footer-link">Sign In</Link>
                    <Link href="/auth/register?role=instructor" class="footer-link">Become a Tutor</Link>
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-title">Connect</h4>
                    <a href="#" class="footer-link">LinkedIn</a>
                    <a href="#" class="footer-link">Instagram</a>
                    <a href="#" class="footer-link">Twitter / X</a>
                    <a href="#" class="footer-link">Facebook</a>
                </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <p>© {{ new Date().getFullYear() }} YIT™. All rights reserved.</p>
                <p class="powered-by">Powered by Youth In Tech</p>
            </div>
            <div class="footer-bottom-right">
                <a href="/terms">Terms & Conditions</a>
                <a href="/privacy">Privacy Policy</a>
                <a href="/cookies">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
</template>

<style scoped>
.public-footer {
    background: #060d1a;
    padding: 80px 0 40px;
    color: #ffffff;
    font-family: 'Inter', sans-serif;
    position: relative;
    border-top: 1px solid rgba(255,255,255,0.05);
}

.footer-texture {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at bottom, rgba(59,130,246,0.05) 0%, transparent 60%);
    pointer-events: none;
    z-index: 1;
}

.footer-content {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px;
    position: relative;
    z-index: 2;
}

.footer-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 60px;
    padding-bottom: 56px;
}

.footer-brand-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 220px;
}

.footer-logo-link {
    display: inline-block;
    transition: transform 0.3s ease;
}
.footer-logo-link:hover { transform: scale(1.05); }

.footer-logo-img {
    height: 48px;
    width: auto;
    filter: brightness(0) invert(1);
    opacity: 0.9;
}

.footer-slogan p {
    margin: 0;
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    line-height: 1.5;
}

.footer-contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    padding: 8px 12px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 8px;
    width: fit-content;
}

.footer-links-grid {
    display: flex;
    gap: 80px;
}

.footer-col {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.footer-col-title {
    font-size: 11px;
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin: 0 0 8px;
}

.footer-link {
    font-size: 14px;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-block;
}

.footer-link:hover { 
    color: #60a5fa; 
    transform: translateX(4px);
}

.footer-divider {
    height: 1px;
    background: rgba(255,255,255,0.08);
    width: 100%;
    margin-bottom: 32px;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-size: 13px;
    color: rgba(255,255,255,0.3);
}

.footer-bottom-left {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.footer-bottom-left p { margin: 0; }
.powered-by { color: rgba(255,255,255,0.2); }

.footer-bottom-right {
    display: flex;
    gap: 24px;
}

.footer-bottom-right a {
    color: rgba(255,255,255,0.3);
    text-decoration: none;
    transition: color 0.2s ease;
}

.footer-bottom-right a:hover { color: #ffffff; }

@media (max-width: 900px) {
    .footer-top { flex-direction: column; align-items: center; text-align: center; }
    .footer-brand-column { align-items: center; }
    .footer-links-grid { flex-direction: row; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 20px; }
    .footer-col { align-items: center; }
}

@media (max-width: 600px) {
    .footer-content { padding: 0 24px; }
    .footer-links-grid { gap: 28px; }
    .footer-bottom { flex-direction: column; align-items: center; gap: 16px; text-align: center; }
    .footer-bottom-right { flex-direction: column; align-items: center; gap: 12px; }
}
</style>
