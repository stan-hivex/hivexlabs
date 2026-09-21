<?php
require_once __DIR__ . "/../config/db.php";
$result = $conn->query("SELECT * FROM site_settings LIMIT 1");
$settings = $result && $result->num_rows ? $result->fetch_assoc() : null;
$headerLogoPath = trim($settings['logo'] ?? 'uploads/hive logo.png');
$headerScriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$headerAppBase = rtrim(dirname($headerScriptPath), '/');
if (basename($headerAppBase) === 'users') {
    $headerAppBase = dirname($headerAppBase);
}
if ($headerAppBase === '/' || $headerAppBase === '.') {
    $headerAppBase = '';
}

if (preg_match('#^(https?:)?//#i', $headerLogoPath)) {
    $headerLogoUrl = $headerLogoPath;
} else {
    $headerLogoPath = ltrim($headerLogoPath, '/');
    $headerLogoParts = array_map('rawurlencode', array_filter(explode('/', $headerLogoPath), 'strlen'));
    $headerLogoUrl = $headerAppBase . '/' . implode('/', $headerLogoParts);
}
?>

<header class="main-header">

    <div class="container">
        <div class="header-left">
              <img src="<?= htmlspecialchars($headerLogoUrl, ENT_QUOTES, 'UTF-8') ?>"
                  alt="HiveX Labs logo" class="site-logo">
            
        </div>

        <nav class="header-nav" id="navMenu">
            <a href="#about">About</a>
            <div class="dropdown">
                <a href="#softwares">Softwares</a>
                <div class="dropdown-menu">
                    <a href="/hivex-labs/users/product.php?type=pos">Hivex POS</a>
                    <a href="/hivex-labs/users/product.php?type=university">University/College Portal</a>
                    <a href="/hivex-labs/users/product.php?type=rental">HiveX Rental Management</a>
                    <a href="/hivex-labs/users/product.php?type=roi">HiveX Return on Investment</a>
                </div>
            </div>
            <a href="#portfolio">Projects</a>
            <a href="#services">Services</a>
            <a href="#contact">Contact</a>
            <a href="/hivex-labs/users/feedback.php">Feedback</a>
        </nav>

        <div class="hamburger" id="hamburger" role="button" aria-label="Toggle navigation menu" tabindex="0">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>

<style>
/* GLOBAL BG (visible on all sides) */
body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(120deg, #02061700, #0f172a00);
    color: #fff;
}

/* HEADER */
.main-header {
    position: fixed;
    top: 5px;
    left: 50%;
    transform: translateX(-50%);
    width: 85%;
    max-width: 1100px;
    background: rgba(0, 0, 0, 0);
    backdrop-filter: blur(6px);
    border-radius: 6px;
    border: 1px solid transparent;
    z-index: 9999;
    transition: all .35s ease;
    pointer-events: auto;
}

.main-header.scrolled {
    background: rgba(32, 32, 32, 0);
    border: 1px solid rgba(0, 255, 255, 0.6);
    
}


/* This is the container — the only part that is boxed */
.main-header .container {
    max-width: 1330px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    padding: 16px 20px;
}


/* Header content styles */
.site-logo { height: 90px; }
.site-title { font-size: 20px; font-weight: 700; color: var(--cyan); }
.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-nav { display: flex; 
    gap: 24px; 
    margin-left: auto;
    align-items: center;
}

.dropdown {
    position: relative;
}

.dropdown > a {
    display: flex;
    align-items: center;
    gap: 6px;
}

.dropdown > a::after {
    content: '▼';
    font-size: 10px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: rgba(32, 32, 32, 0.95);
    border: 1px solid rgba(0, 255, 136, 0.3);
    border-radius: 8px;
    min-width: 220px;
    margin-top: 10px;
    display: none;
    flex-direction: column;
    z-index: 1000;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.25s ease;
    pointer-events: none;
}

.dropdown:hover .dropdown-menu {
    display: flex;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.dropdown.open .dropdown-menu {
    display: flex;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.dropdown-menu a {
    padding: 12px 16px;
    color: #fcf9f9;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    border-bottom: 1px solid rgba(0, 255, 136, 0.1);
    transition: all 0.2s ease;
}

.dropdown-menu a:last-child {
    border-bottom: none;
}

.dropdown-menu a:hover {
    background: rgba(0, 255, 136, 0.15);
    color: var(--accent);
    padding-left: 20px;
}
.header-nav a {
    color: #fcf9f9;
    font-weight: 600;
    text-decoration: none;
    transition: color .25s;
}
.header-nav a:hover { color: var(--accent); }

.hamburger { display: none; flex-direction: column; gap: 6px; cursor: pointer; margin-left: auto; touch-action: manipulation; }
.hamburger span {
    width: 28px;
    height: 3px;
    background: var(--accent);
    border-radius: 3px;
}
body.menu-open {
    overflow: hidden;
}

/* Responsive */
@media(max-width:768px){
    .main-header {
        top: 4px;
        width: calc(100% - 16px);
        max-width: none;
    }

    .main-header .container {
        min-width: 0;
        padding: 8px 12px;
    }

    .site-logo {
        width: auto;
        max-width: min(58vw, 220px);
        height: auto;
        max-height: 58px;
        object-fit: contain;
    }

    .header-nav {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 10000;
        background: rgba(0, 0, 0, 0.94);
        padding: 20px 20px 24px;
        gap: 16px;
        max-height: calc(100vh - 82px);
        overflow-y: auto;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.25);
    }
    .header-nav.open { display: flex; }
    
    .dropdown-menu {
        position: static;
        background: rgba(0, 0, 0, 0.85);
        border: none;
        border-top: 1px solid rgba(0, 255, 136, 0.2);
        border-radius: 0;
        min-width: auto;
        margin-top: 8px;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .dropdown.open .dropdown-menu {
        display: flex;
        opacity: 1;
        max-height: 500px;
    }
    
    .hamburger {
        display: flex;
        padding: 12px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        position: relative;
        z-index: 10001;
        pointer-events: auto;
        touch-action: manipulation;
        transition: background 0.25s ease, transform 0.25s ease;
    }
    .hamburger:hover {
        background: rgba(255, 255, 255, 0.14);
        transform: translateY(-1px);
    }
    .hamburger span {
        width: 32px;
        height: 4px;
        background: var(--accent);
        border-radius: 999px;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .hamburger.open span:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }
    .hamburger.open span:nth-child(2) {
        opacity: 0;
    }
    .hamburger.open span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const hamburgerBtn = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const dropdown = document.querySelector('.dropdown');
    const dropdownLink = dropdown ? dropdown.querySelector('a') : null;

    if (hamburgerBtn && navMenu) {
        const toggleNav = () => {
            const isOpen = navMenu.classList.toggle('open');
            document.body.classList.toggle('menu-open', isOpen);
            hamburgerBtn.classList.toggle('open', isOpen);
            hamburgerBtn.setAttribute('aria-expanded', String(isOpen));
            if (!isOpen && dropdown) {
                dropdown.classList.remove('open');
            }
        };

        hamburgerBtn.setAttribute('aria-expanded', 'false');
        hamburgerBtn.addEventListener('click', toggleNav);
        hamburgerBtn.addEventListener('touchstart', (event) => {
            event.preventDefault();
            toggleNav();
        });
        hamburgerBtn.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleNav();
            }
        });

        document.querySelectorAll('#navMenu > a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('open');
                document.body.classList.remove('menu-open');
                hamburgerBtn.setAttribute('aria-expanded', 'false');
                if (dropdown) {
                    dropdown.classList.remove('open');
                }
            });
        });
    }

    if (dropdownLink) {
        dropdownLink.addEventListener('click', (e) => {
            e.preventDefault();
            dropdown.classList.toggle('open');
        });
    }

    if (dropdown) {
        document.querySelectorAll('.dropdown-menu a').forEach(link => {
            link.addEventListener('click', () => {
                dropdown.classList.remove('open');
                if (navMenu) {
                    navMenu.classList.remove('open');
                }
                document.body.classList.remove('menu-open');
            });
        });
    }

    document.addEventListener('click', (e) => {
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
});
</script>
