<?php
require_once __DIR__ . "/../config/db.php";

$tools = $conn
    ->query("SELECT * FROM hero_tools WHERE status='active' ORDER BY id ASC")
    ->fetch_all(MYSQLI_ASSOC);
?>

<div class="marquee-container">
    <div class="marquee-track">
        <?php foreach($tools as $tool): ?>
            <img src="/hivex-labs/<?= htmlspecialchars($tool['logo']) ?>" 
                 alt="<?= htmlspecialchars($tool['name']) ?>">
        <?php endforeach; ?>

        <!-- Duplicate for seamless loop -->
        <?php foreach($tools as $tool): ?>
            <img src="/hivex-labs/<?= htmlspecialchars($tool['logo']) ?>" 
                 alt="<?= htmlspecialchars($tool['name']) ?>">
        <?php endforeach; ?>
    </div>
</div>

<style> 
#hero-tools {
    width: 100%;
    padding: 59px 0;
    background: rgba(30, 59, 126, 0);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.marquee-container {
    width: 100%;
    overflow: hidden;
    border-radius: 14px;
    padding: 20px 0;

    background: rgba(15, 23, 42, 0);
    backdrop-filter: blur(15px);

    border: 1px solid rgba(0, 235, 250, 0.35);

    box-shadow:
        0 0 10px rgba(0, 235, 250, 0.25);
}
.marquee-container {
    mask-image: linear-gradient(
        to right,
        transparent,
        black 10%,
        black 90%,
        transparent
    );
}

.marquee-track {
    display: flex;
    gap: 60px;
    align-items: center;
    width: fit-content;

    animation: marquee 45s linear infinite;
    
}

/* TRUE CONTINUOUS SCROLL */
@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.marquee-track {
    will-change: transform;
}



.marquee-track img {
    height: 49px;
    width: auto;
    object-fit: contain;
    opacity: 0.8;
    transition: 0.3s ease;
}

.marquee-track img:hover {
    opacity: 1;
    transform: scale(1.1);
}

@keyframes marquee {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
</style>