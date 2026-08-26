<?php
require_once __DIR__ . "/../config/db.php";

/* LIMIT TO 3 SLIDES FOR CLEAN UI */
$slides = $conn
    ->query("SELECT * FROM hero_section ORDER BY id ASC LIMIT 3")
    ->fetch_all(MYSQLI_ASSOC);
?>

<div class="hero-text">
    <h1>
        <span class="line1"></span><br>
        <span class="line2"></span>
    </h1>
    <p></p>
    <a class="hero-btn" href="#"></a>
</div>

<style>
/* HERO FULL BACKGROUND */
#home {
    min-height: 75vh;
    width: 100%;
    margin: 0;
    position: relative;
    display: flex;
    justify-content: flex-start; /* push left */
    align-items: center;
    padding: 0 6%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    overflow: hidden;
}

/* Gradient overlay so text remains legible */
#home::before {
    content:"";
    position:absolute;
    inset:0;
   
    z-index:1;
    pointer-events: none; /* fixes button */
}

/* Hero text */
.hero-text {
    position: relative;
    z-index: 2;
}

.hero-text h1 {
    font-size: clamp(48px, 6vw, 70px);
    color: #fff;
    line-height: 1.1;
    margin-bottom: 20px;
}

/* Line 1 stays left */
.hero-text h1 .line1 {
    text-align: center;
}

/* Line 2 drops and is centered relative to line1 */
.hero-text h1 .line2 {
    display: block;
    text-align: center;
    margin-top: 0.3em;
    font-size: clamp(50px, 7vw, 80px);
    color: #fa8500;
    font-weight: 700;
}

/* Paragraph */
.hero-text p {
    font-size: clamp(16px, 2vw, 20px);
    color: #fa8500;
    margin: 0 auto 32px auto; /* centers the block horizontally */
    max-width: 550px;
    text-align: center; /* centers the text inside the paragraph */
}

/* Button */
.hero-btn {
    display: block; /* ensures it behaves like a button */
    padding: 16px 32px;
    border-radius: 909px;
    background: linear-gradient(135deg, var(--accent), var(--cyan));
    color: #0a0a0a;
    font-weight: 800;
    letter-spacing: 0.5px;
    transition: 0.3s ease;
    text-align: center; /* centers text inside button */
}


.hero-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(59,130,246,.4);
}

/* Hero orbs */
.hero-visual { position: relative; height: 450px; }
.orb {
    position: absolute;
    border-radius:50%;
    filter: blur(80px);
    opacity:.45;
    animation: float 10s ease-in-out infinite alternate;
}
@keyframes float { from{transform:translate(0,0);} to{transform:translate(20px,-30px);} }

@media (max-width: 768px) {

    #home {
        justify-content: flex-start;   /* keep same as desktop */
        text-align: left;              /* keep same as desktop */
        padding: 0 6%;                 /* keep same as desktop */
    }

    .hero-text {
        width: 100%;
    }

    .hero-text h1 {
        text-align: left;              /* keep same as desktop */
        font-size: 26px;               /* smaller on mobile */
        text-transform: uppercase;     /* capital letters */
    }

    .hero-text h1 .line2 {
        font-size: 32px;               /* smaller on mobile */
    }

    .hero-text p {
        font-size: 14px;               /* smaller paragraph on mobile */
        text-align: left;              /* align left like title */
        margin-left: 0;
    }

    .hero-btn {
        display: inline-block;         /* keep same as desktop */
    }
}

</style>

<script>
if (typeof heroSlides === 'undefined') {
    var heroSlides = <?= json_encode($slides) ?>;
}

let current = 0;

function changeHero() {
    const slide = heroSlides[current];

    // ✅ FIXED IMAGE PATH
    document.querySelector("#home").style.backgroundImage = `url('/${slide.image}')`;

    /* Safety fallback if image missing */
    if (!slide.image) {
        document.querySelector("#home").style.backgroundImage = `url('/uploads/default.jpg')`;
    }

    /* Split main_title into two lines */
    const title = slide.main_title || 'We Design . We Build . You Grow';
    const parts = title.split('You Grow');

    document.querySelector(".hero-text .line1").textContent =
        (parts[0] || 'We Design . We Build .').trim();

    document.querySelector(".hero-text .line2").textContent = 'You Grow';

    document.querySelector(".hero-text p").textContent = slide.sub_title || '';
    document.querySelector(".hero-btn").textContent = slide.button_text || '';
    document.querySelector(".hero-btn").href = slide.button_link || '#';

    current = (current + 1) % heroSlides.length;
}

changeHero();
setInterval(changeHero, 6000);
</script>