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
                <div class="service">
                    <?php
$iconClass = 'fa-code'; // default

switch(strtolower($s['service_name'])) {
    case 'coding and programming':
        $iconClass = 'fa-solid fa-code';
        break;

    case 'branding':
        $iconClass = 'fa-solid fa-bullhorn';
        break;

    case 'motion/ui design':
    case 'motion ui design':
        $iconClass = 'fa-solid fa-object-group';
        break;

    case 'graphics design':
        $iconClass = 'fa-solid fa-pen-nib';
        break;
}
?>

<div class="icon">
    <i class="<?= $iconClass ?>"></i>
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
    font-size:22px;
    min-width:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#00ebfa,#0084ff);
    border-radius:12px;
    width:50px;
    height:50px;
    color:#fff;
}


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
    
.services{
    padding:80px 6%;
    background:#020617;
    display:flex;
    justify-content:center;
}

.services .section-container{
    max-width:1100px;
    width:100%;
    background:#020617;
    border-radius:20px;
    padding:40px;
    border:1px solid #00ebfa;
}

.services h2{
    color:#00ebfa;
    margin-bottom:30px;
}

.services-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}

.service{
    background:#020617;
    border:1px solid rgba(147,197,253,0.2);
    border-radius:16px;
    padding:20px;
    display:flex;
    gap:14px;
}

.service h4{color:#93c5fd;}
.service p{color:#c7d2fe;font-size:14px;}
</style>
