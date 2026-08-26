<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HiveX Labs – Creative Portfolio</title>

<!-- Favicon -->
 <link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#0f172a">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* =========================
   COLOR SYSTEM
========================= */
:root{
    --bg: radial-gradient(1200px 600px at 10% -10%, #060607 0%, #020202 45%);
    --muted: #c7d2fe;
    --accent: #3b82f6;
    --cyan: #93c5fd;
    scroll-behavior: smooth;
}

[data-theme="light"]{
    --bg: linear-gradient(180deg, #eaf2ff 0%, #f8fbff 60%);
    --muted:#1e3a8a;
    --accent:#1d4ed8;
    --cyan:#60a5fa;
}

/* =========================
   BASE
========================= */
*{margin:0;padding:0;box-sizing:border-box;font-family:Inter,sans-serif;}
/* =========================
   GLOBAL TECH NETWORK BG
========================= */

html, body {
    height:100%;
    width:100%;
    background: #05070dc4;
    color: var(--muted);
}

body{
    position:relative;
    overflow-x:hidden;
}


a{text-decoration:none;color:inherit;}

/* =========================
   CONTAINER (Transparent)
========================= */
.container{
    
    max-width:1200px;
    margin-left:auto;
    margin-right:auto;
    padding-left:24px;
    padding-right:24px;
}

.page-wrapper {
    position: relative;
    margin: 0 auto;
    max-width: 1200px;
    border-radius: 16px;

   

    border: 1px solid rgba(0, 235, 250, 0.3);
    

    transition: all 0.35s ease;
    overflow: hidden;
}


/* Remove glow on scroll */
.page-wrapper.scrolled {
    border: 1px solid transparent;
    box-shadow: none;
}




/* =========================
   SECTIONS
========================= */
section{padding:30px 0;}
h2,h3{color:var(--accent);text-align:center;}

/* =========================
   FOOTER
========================= */
.site-footer{
    padding:60px 0 30px;
    text-align:center;
    color:var(--muted);
}

/* RESPONSIVE */
@media(max-width:900px){
    .hero-content{grid-template-columns:1fr;text-align:center;padding-top:140px;}
    .hero-visual{display:none;}
}

.whatsapp-float{
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;

    display:flex;
    align-items:center;
    justify-content:center;

    background: #25D366;
    color:#fff;
    font-size:28px;

    border-radius:50%;
    text-decoration:none;

    box-shadow:
        0 6px 18px rgba(0,0,0,0.25),
        0 0 12px rgba(37,211,102,0.6);

    z-index:9999;
    transition: all 0.3s ease;
}

/* Hover Effect */
.whatsapp-float:hover{
    transform: scale(1.1);
    box-shadow:
        0 8px 22px rgba(0,0,0,0.35),
        0 0 20px rgba(37,211,102,0.9);
}

/* Pulse Animation */
.whatsapp-float::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    border-radius:50%;
    background:rgba(37,211,102,0.5);
    animation:pulse 2s infinite;
    z-index:-1;
}

@keyframes pulse{
    0% { transform: scale(1); opacity:0.7; }
    70% { transform: scale(1.5); opacity:0; }
    100% { transform: scale(1.5); opacity:0; }
}

/* =========================
   THIN FANCY DIVIDER
========================= */
.fancy-divider {
    width: 100%;
    height: 4px; /* thin line */
    margin: 50px 0;
    position: relative;
    overflow: hidden;
    border-radius: 2px;
    background: linear-gradient(
        90deg,
        rgba(0,235,250,0.4) 0%,
        rgba(59,130,246,0.6) 50%,
        rgba(0,235,250,0.4) 100%
    );
   
}

.fancy-divider::before {
    content: '';
    position: absolute;
    top: 0;
    left: -50%;
    width: 50%;
    height: 100%;
    background: rgba(255,255,255,0.3);
    filter: blur(6px);
    animation: shimmer 2s linear infinite;
}

@keyframes shimmer {
    0% { left: -50%; }
    100% { left: 100%; }
}

/* =========================
   REAL HEXAGON BACKGROUND
========================= */


/* HEX GRID LAYER */
.circuit-bg::before {
    content: "";
    position: absolute;
    inset: 0;

    background-image:
        linear-gradient(90deg, rgba(0,234,255,0.25) 1px, transparent 1px),
        linear-gradient(60deg, rgba(0,234,255,0.25) 1px, transparent 1px),
        linear-gradient(-60deg, rgba(0,234,255,0.25) 1px, transparent 1px);

    background-size: 80px 140px;
    opacity: 0.25;
}
.circuit-bg {
    position: fixed;
    inset: 0;
    z-index: -3;
    background: #05070d;

    background-image:
      radial-gradient(circle at 50% 50%, transparent 34px, rgba(0,234,255,0.35) 35px, rgba(0,234,255,0.35) 36px, transparent 37px);

    background-size: 70px 60.62px;
}


</style>
</head>
<body>
<div class="circuit-bg"></div>

    <!-- WhatsApp Floating Button -->
<a href="https://wa.me/254710484154?text=Hello%20HiveX%20Labs,%20I%20would%20like%20to%20inquire%20about%20your%20services."
   class="whatsapp-float"
   target="_blank"
   aria-label="Chat with us on WhatsApp">
   <i class="fab fa-whatsapp"></i>
</a>

    <?php include __DIR__ . "/users/header.php"; ?>
<div class="page-wrapper">
    <!-- HERO -->
    <section id="home">
      <div class="container">
        <?php include __DIR__ . "/users/hero.php"; ?>
      </div>
    </section>

<section id="hero-tools">
    <?php include __DIR__ . "/users/hero_tools.php"; ?>
</section>
<div class="fancy-divider"></div>

    <!-- ABOUT -->
    <section id="about">
      <div class="container">
        <?php include __DIR__ . "/users/about.php"; ?>
      </div>
    </section>
<div class="fancy-divider"></div>

    <!-- SOFTWARES -->
    <section id="softwares">
      <div class="container">
        <?php define('SOFTWARES_INCLUDED', true); include __DIR__ . "/users/softwares.php"; ?>
      </div>
    </section>
<div class="fancy-divider"></div>

    <!-- SERVICES -->
    <section id="services">
      <div class="container">
        <?php include __DIR__ . "/users/services.php"; ?>
      </div>
    </section>
<div class="fancy-divider"></div>

    <!-- PORTFOLIO -->
    <section id="products">
      <div class="container">
        <?php include __DIR__ . "/users/portfolio.php"; ?>
      </div>
    </section>
<div class="fancy-divider"></div>

    <!-- CONTACT -->
    <section id="contact">
      <div class="container">
        <?php include __DIR__ . "/users/contact.php"; ?>
      </div>
    </section>
<div class="fancy-divider"></div>

    <!-- FEEDBACK DISPLAY -->
    <section id="feedback">
      <div class="container">
        <?php include __DIR__ . "/users/feedback_display.php"; ?>
      </div>
    </section></div> 
<div class="fancy-divider"></div>

<footer class="footer-wrapper">
  <div class="footer-inner">

    <div class="footer-grid">
     
      <!-- BRAND -->
      <div class="footer-brand">
        <h3>HiveX Labs</h3>
        <p>Creative digital studio building modern web, design, and brand experiences.</p>
      </div>
      
      <!-- QUICK LINKS -->
      <div class="footer-links">
        <h3>Quick Links</h3>
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Our Solutions</a>
        <a href="#portfolio">Products</a>
        <a href="#contact">Contact</a>
        <a href="feedback.php">Feedback</a>
      </div>

      <!-- SOLUTIONS -->
      <div class="footer-links">
        <h3>Solutions</h3>
        <a href="#">Web Design</a>
        <a href="#">Graphic Design</a>
        <a href="#">Branding</a>
        <a href="#">UI/UX</a>
      </div>

      <!-- SOCIAL ICONS -->
<div class="footer-socials">
  <h3>Connect</h3>
  <div class="social-icons">
    <a href="https://twitter.com/staKknow" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-x-twitter"></i>
    </a>

    <a href="https://linkedin.com/in/hivexlabs" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-linkedin-in"></i>
    </a>

    <a href="https://instagram.com/cent.nast" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-instagram"></i>
    </a>

    <a href="https://github.com/hivex-labs" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-github"></i>
    </a>

    <a href="https://facebook.com/HiveX_Labs" target="_blank" rel="noopener noreferrer">
      <i class="fab fa-facebook-f"></i>
    </a>
  </div>
</div>


       <!-- Legal -->
       <div class="footer-legal">
        <h3>Legal</h3>
        <a href="/hivex-labs/users/privacy.php">Privacy Policy</a><br>
        <a href="/hivex-labs/users/terms.php">Terms of Service</a>
       </div>

    </div>

    <!-- BOTTOM -->
    <div class="footer-bottom">
      <p>© 2025 HiveX Labs. Designed by Malik. All rights reserved.</p>
    </div>
  </div>
</div>
</footer>

<style>
/* =========================
   MODERN FOOTER
========================= */
/* FOOTER WRAPPER (same width as page-wrapper) */
.footer-wrapper {
    display: flex;
    justify-content: center;
    padding: 60px 0 30px;
}

/* INNER FOOTER (Glass box like page-wrapper) */
.footer-inner {
    width: 100%;
    max-width: 1200px;
    padding: 60px 40px 30px;

    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    border-radius: 16px;
    border: 1px solid rgba(0, 235, 250, 0.3);
    

    color: #cbd5df;
}

.footer-inner h3 {
    color: #00ebfa;
    margin-bottom: 15px;
    font-weight: 700;
    letter-spacing: 1px;
    text-align: center;   /* centers h3 */
}

.footer-inner p {
    color: #cbd5df;
    line-height: 1.6;
    text-align: center;   /* centers paragraph */
}

/* Center EVERYTHING inside each footer column */
.footer-grid > div {
    text-align: center;
}

/* Center links properly */
.footer-links a {
    display: block;
    margin-bottom: 8px;
    color: #cbd5df;
    transition: all .3s ease;
}

/* Center social icons */
.footer-socials .social-icons {
    display: flex;
    justify-content: center; /* centers icons */
    gap: 12px;
    margin-top: 10px;
}

/* Fix paragraph alignment */
.footer-inner p {
    text-align: center;
}


.footer-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-links a{
    display:block;
    margin-bottom: 8px;
    color:#cbd5df;
    transition: all .3s ease;
}

.footer-links a:hover{
    color: #00ebfa;
    transform: translateX(4px);
}

.footer-socials .social-icons{
    display:flex;
    gap: 12px;
    margin-top: 10px;
}

.footer-socials .social-icons a{
    width: 40px;
    height: 40px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background: linear-gradient(135deg, #3b82f6, #93c5fd);
    color:#fff;
    font-size:16px;
    transition: all .3s ease;
}

.footer-socials .social-icons a:hover{
    transform: scale(1.2) rotate(10deg);
}

.footer-bottom{
    text-align:center;
    padding-top:20px;
    border-top:1px solid rgba(0,235,250,0.2);
    font-size:13px;
    color:#94a3b8;
}

/* RESPONSIVE */
@media(max-width:768px){
    .footer-grid{
        grid-template-columns:1fr 1fr;
        gap:30px;
    }
}

@media(max-width:480px){
    .footer-grid{
        grid-template-columns:1fr;
        text-align:center;
    }
    .footer-socials .social-icons{
        justify-content:center;
    }
}
</style>


<!-- JS SCRIPTS -->
<script>
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');
const header = document.querySelector('.main-header');
const pageWrapper = document.querySelector('.page-wrapper');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('open');
        document.body.classList.toggle('menu-open', isOpen);
    });
    document.querySelectorAll('#navMenu > a').forEach(link => link.addEventListener('click', () => {
        navMenu.classList.remove('open');
        document.body.classList.remove('menu-open');
    }));
    document.querySelectorAll('.dropdown-menu a').forEach(link => link.addEventListener('click', () => {
        navMenu.classList.remove('open');
        document.body.classList.remove('menu-open');
    }));
}

if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 80) { header.classList.add('scrolled'); }
        else { header.classList.remove('scrolled'); }
    });
}

if (pageWrapper) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            pageWrapper.classList.add('scrolled');
        } else {
            pageWrapper.classList.remove('scrolled');
        }
    });
}

</script>

</body>
</html>
