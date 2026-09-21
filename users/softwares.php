<?php
require_once __DIR__ . "/../config/db.php";

function asset_path($path) {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#', $path)) {
        return $path;
    }

    $isRootRelative = strpos($path, '/') === 0;
    $encodedSegments = array_map('rawurlencode', array_filter(explode('/', ltrim($path, '/')), 'strlen'));
    $path = implode('/', $encodedSegments);
    if ($isRootRelative) {
        return '/' . $path;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $appRoot = dirname(dirname($scriptName));
    if ($appRoot === '/' || $appRoot === '.' || $appRoot === '\\') {
        $appRoot = '';
    }

    if ($appRoot === '' && strpos($scriptName, '/hivex-labs/') !== false) {
        $appRoot = '/hivex-labs';
    }

    return ($appRoot === '' ? '' : $appRoot) . '/' . $path;
}

$softwares = $conn->query("SELECT * FROM product_content ORDER BY created_at DESC");
?>

<section id="softwares">
    <div class="section-container">
        <h2>Our Software Solutions</h2>
        <div class="softwares-list">
            <?php if($softwares->num_rows):
                while($s=$softwares->fetch_assoc()): ?>
                <div class="software-card">
                    <?php if (!empty($s['hero_image'])): ?>
                        <div class="card-image">
                            <img src="<?= htmlspecialchars(asset_path($s['hero_image'])) ?>" alt="<?= htmlspecialchars($s['title']) ?>" class="hero-thumb">
                        </div>
                    <?php else: ?>
                        <div class="card-icon">
                            <i class="fas fa-code"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-content">
                        <h4><?= htmlspecialchars($s['title']) ?></h4>
                        <p class="card-description"><?= htmlspecialchars(substr($s['description'], 0, 120)) ?>...</p>
                    </div>
                    <div class="card-footer">
                        <a href="<?= asset_path('users/software_detail.php') ?>?key=<?= urlencode($s['product_key']) ?>" class="view-more-btn">
                            View More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <p>No software solutions added yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.card-icon{
    font-size:24px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(135deg,#00ebfa,#0084ff);
    border-radius:12px;
    width:50px;
    height:50px;
    color:#fff;
    margin-bottom:15px;
}
.card-image{
    width:100%;
    border-radius:12px;
    overflow:hidden;
    margin-bottom:15px;
    background:#f8f9fa;
    display:flex;
    align-items:center;
    justify-content:center;
}
.hero-thumb{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    transition:all 0.3s ease;
}
.hero-thumb:hover{
    transform:scale(1.05);
}
.software-card{
    display:flex;
    flex-direction:column;
    padding:28px;
    background:#ffffff;
    border:2px solid #e0f0ff;
    border-radius:16px;
    transition:all 0.3s ease;
    min-height:280px;
    position:relative;
    box-shadow:0 4px 12px rgba(0,132,255,0.08);
}
.software-card:hover{
    border-color:#00ebfa;
    background:#ffffff;
    transform:translateY(-8px);
    box-shadow:0 16px 40px rgba(0,132,255,0.2);
}
.card-content{
    flex-grow:1;
    margin-bottom:15px;
}
.software-card h4{
    color:#00a8cc;
    margin-bottom:12px;
    font-size:20px;
    font-weight:700;
}
.card-description{
    color:#4a5568;
    line-height:1.7;
    font-size:14px;
    margin:0;
}
.card-footer{
    display:flex;
    justify-content:flex-start;
    margin-top:auto;
}
.view-more-btn{
    color:#fff;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 20px;
    background:linear-gradient(135deg,#00ebfa,#0084ff);
    border-radius:8px;
    transition:all 0.2s ease;
}
.view-more-btn:hover{
    background:linear-gradient(135deg,#0084ff,#006db3);
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,132,255,0.3);
}
.softwares{
    padding:80px 6%;
    background:#020617;
    display:flex;
    justify-content:center;
}
.softwares .section-container{
    max-width:1100px;
    width:100%;
    background:#020617;
    border-radius:20px;
    padding:40px;
    border:1px solid #00ebfa;
}
.softwares h2{
    color:#00ebfa;
    text-align:center;
    margin-bottom:60px;
    font-size:32px;
}
.softwares-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:24px;
}
@media (max-width:768px){
    #softwares{padding:52px 14px;}
    #softwares .section-container{padding:26px 14px; border-radius:14px;}
    .softwares-list{grid-template-columns:1fr; gap:16px;}
    .software-card{padding:20px; min-height:0;}
    .softwares h2{font-size:clamp(24px, 8vw, 32px); margin-bottom:32px;}
}
</style>
