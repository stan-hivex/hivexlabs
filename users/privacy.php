<?php
require_once __DIR__ . "/../config/db.php";

// Fetch policies from DB
$policies = $conn->query("SELECT * FROM privacy_policy ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="circuit-bg"></div>
<section id="privacy-policy">
    <div class="container">
        <h1>Privacy Policy</h1><br>
        [AS OF DATE: 01-09-2025]

        <?php foreach($policies as $policy): ?>
            <div class="policy">
                <h2><?= htmlspecialchars($policy['title']) ?></h2>
                <h3><?= nl2br(htmlspecialchars($policy['content'])) ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<style>
#privacy-policy{
    padding:80px 6%;
    background: #0a0f1a00;
    color:#cbd5df;
}

#privacy-policy h1{
    text-align:center;
    color: #00ebfa;
    margin-bottom:40px;
}

.policy{
    margin-bottom:30px;
    padding:20px;
    background: rgba(15,23,42,0.85);
    border-radius:12px;
    border:1px solid rgba(0,235,250,0.2);
}

.policy h2{
    color:#3b82f6;
    margin-bottom:10px;
}

.policy h3{
    line-height:1.7;
    color:#cbd5df;
}
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
