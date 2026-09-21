<?php
require_once __DIR__ . "/../config/db.php";
$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>

<section id="services">
    <div class="section-container">

        <h2>Our Solutions</h2>

        <div class="services-list">
            <?php if($services->num_rows):
                while($s=$services->fetch_assoc()): ?>
                <?php
                $serviceName = strtolower(trim($s['service_name'] ?? ''));
                $iconClass = 'fa-solid fa-code';
                $iconTone = 'icon--code';

                if (strpos($serviceName, 'brand') !== false) {
                    $iconClass = 'fa-solid fa-bullhorn';
                    $iconTone = 'icon--brand';
                } elseif (strpos($serviceName, 'motion') !== false || strpos($serviceName, 'animation') !== false) {
                    $iconClass = 'fa-solid fa-wand-magic-sparkles';
                    $iconTone = 'icon--motion';
                } elseif (strpos($serviceName, 'graphic') !== false || strpos($serviceName, 'design') !== false) {
                    $iconClass = 'fa-solid fa-pen-nib';
                    $iconTone = 'icon--design';
                } elseif (strpos($serviceName, 'web') !== false || strpos($serviceName, 'software') !== false || strpos($serviceName, 'program') !== false || strpos($serviceName, 'coding') !== false) {
                    $iconClass = 'fa-solid fa-code';
                    $iconTone = 'icon--code';
                }
                ?>
                <div class="service">
                    <div class="icon <?= $iconTone ?>" aria-hidden="true">
                        <i class="<?= htmlspecialchars($iconClass, ENT_QUOTES, 'UTF-8') ?>"></i>
                    </div>

                    <div>
                        <h4><?= htmlspecialchars($s['service_name']) ?></h4>
                        <p><?= htmlspecialchars($s['description']) ?></p>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <p>No services added yet.</p>
            <?php endif; ?>
        </div>

    </div>
</section>

<style>
.icon{
    position:relative;
    flex:0 0 54px;
    width:54px;
    height:54px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid rgba(147,197,253,.32);
    border-radius:14px;
    color:#fff;
    font-size:21px;
    overflow:hidden;
    isolation:isolate;
}

.icon::before{
    content:"";
    position:absolute;
    inset:0;
    z-index:-1;
    background:linear-gradient(145deg,rgba(0,235,250,.95),rgba(0,132,255,.72));
}

.icon::after{
    content:"";
    position:absolute;
    width:34px;
    height:34px;
    right:-14px;
    bottom:-18px;
    border:1px solid rgba(255,255,255,.35);
    border-radius:50%;
}

.icon--brand::before{background:linear-gradient(145deg,#f59e0b,#ef4444);}
.icon--motion::before{background:linear-gradient(145deg,#a855f7,#6366f1);}
.icon--design::before{background:linear-gradient(145deg,#14b8a6,#0ea5e9);}


    .service:hover{
        border-color:#00ebfa;
        background:linear-gradient(135deg,rgba(0,235,250,0.05),rgba(0,132,255,0.05));
        transform:translateY(-4px);
        box-shadow:0 8px 24px rgba(0,235,250,0.1);
        transition:all 0.3s ease;
    }

    .service h4{
        color:#00ebfa;
        margin-bottom:8px;
    }
    
#services{
    padding:80px 6%;
    display:flex;
    justify-content:center;
}

#services .section-container{
    max-width:1180px;
    width:100%;
    border-radius:20px;
    padding:44px;
    border:1px solid #00ebfa;
}

#services h2{
    color:#00ebfa;
    margin-bottom:30px;
}

.services-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:24px;
}

.service{
    background:#020617;
    border:1px solid rgba(147,197,253,0.2);
    border-radius:16px;
    min-height:150px;
    padding:26px;
    display:flex;
    align-items:flex-start;
    gap:14px;
}

.service:hover .icon{
    transform:rotate(-4deg) scale(1.04);
}

.icon{transition:transform .25s ease, box-shadow .25s ease;}

.service h4{color:#93c5fd;}
.service p{color:#c7d2fe;font-size:14px;}

@media (max-width: 600px){
    #services{padding:52px 14px;}
    #services .section-container{padding:26px 14px; border-radius:14px;}
    .services-list{grid-template-columns:1fr; gap:14px;}
    .service{min-height:0; padding:18px 16px;}
}
</style>
