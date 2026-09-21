<?php
require_once __DIR__ . "/../config/db.php";

$cat = $_GET['cat'] ?? '';

$stmt = $conn->prepare(
    "SELECT * FROM projects WHERE category=? ORDER BY id DESC"
);
$stmt->bind_param("s", $cat);
$stmt->execute();
$projects = $stmt->get_result();

$portfolioScriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$portfolioAppBase = rtrim(dirname($portfolioScriptPath), '/');
if (basename($portfolioAppBase) === 'users') {
    $portfolioAppBase = dirname($portfolioAppBase);
}
if ($portfolioAppBase === '/' || $portfolioAppBase === '.') {
    $portfolioAppBase = '';
}

function portfolioCategoryImageUrl(string $path, string $appBase): string
{
    if (preg_match('#^(https?:)?//#i', $path)) {
        return $path;
    }

    $segments = array_map('rawurlencode', array_filter(explode('/', ltrim($path, '/')), 'strlen'));
    return $appBase . '/' . implode('/', $segments);
}
?>

<section class="portfolio">
    <h3><?= htmlspecialchars($cat) ?></h3>

    <div class="portfolio-grid">
        <?php if($projects->num_rows): while($p=$projects->fetch_assoc()): ?>
            <div class="project">
                <?php if(!empty($p['link'])): ?>
                    <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank">
                        <img src="<?= htmlspecialchars(portfolioCategoryImageUrl('uploads/projects/' . $p['image'], $portfolioAppBase), ENT_QUOTES, 'UTF-8') ?>">
                    </a>
                <?php else: ?>
                    <img src="<?= htmlspecialchars(portfolioCategoryImageUrl('uploads/projects/' . $p['image'], $portfolioAppBase), ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <h4><?= htmlspecialchars($p['title']) ?></h4>
            </div>
        <?php endwhile; else: ?>
            <p style="color:#9fb6c2">No projects yet.</p>
        <?php endif; ?>
    </div>
</section>



<style>
.portfolio-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:22px;
}
.project-card{
    background:#0f1316;
    border-radius:14px;
    overflow:hidden;
    text-decoration:none;
    color:#fff;
    border:1px solid rgba(0,255,136,.1);
    transition:.3s;
}
.project-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}
.project-card span{
    display:block;
    padding:12px;
    font-size:14px;
}
.project-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(0,0,0,.7);
}
</style>
