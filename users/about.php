<?php
require_once __DIR__ . "/../config/db.php";

// Fetch about content
$result = $conn->query("SELECT * FROM about LIMIT 1");
$about = $result->fetch_assoc();
?>

<section id="about" class="about-section">
    <div class="about-wrapper">
        
        <div class="about-card">
            
            <div class="about-header">
                <h2>About HiveX Labs</h2>
                <div class="divider"></div>
            </div>

            <div class="about-content">
                <p>
                    <?= htmlspecialchars($about['content'] ?? 
                    'At HiveX Labs, we transform ideas into powerful visuals and digital experiences.') ?>
                </p>
            </div>

        </div>

    </div>
</section>

<style>

/* ===== SECTION WRAPPER ===== */
.about-section{
    display:flex;
    justify-content:center;
}
/* ===== CARD CONTAINER ===== */
.about-card{
    position:relative;
    background: #111827;
    border-radius:18px;
    padding:60px 50px;
    max-width:950px;
    width:100%;
    color: #fff;
    overflow:hidden;
    transition:0.3s ease;
    align-content: center;
}

/* BLUE TOP ACCENT */
.about-card::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background: #00ebfa;
}



/* HOVER EFFECT */
.about-card:hover{
    transform:translateY(-6px);
}

/* HEADER */
.about-header{
    text-align:center;
    margin-bottom:35px;
}

.section-tag{
    display:inline-block;
    font-size:12px;
    letter-spacing:2px;
    text-transform:uppercase;
    color:#00ebfa;
    margin-bottom:10px;
}

.about-header h2{
    font-size:32px;
    font-weight:700;
    margin-bottom:15px;
}

/* DIVIDER */
.divider{
    width:50px;
    height:3px;
    background:#00ebfa;
    margin:0 auto;
    border-radius:5px;
}

/* CONTENT */
.about-content p{
    font-size:16px;
    line-height:1.9;
    color:#cbd5df;
    text-align:center;
}

/* RESPONSIVE */
@media(max-width:768px){
    .about-card{
        padding:40px 25px;
    }

    .about-header h2{
        font-size:24px;
    }
}

</style>
