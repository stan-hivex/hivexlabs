<?php
require_once __DIR__ . "/../config/db.php";

$categories = [
    "Graphic Design",
    "Web Design",
    "Motion / UI Design",
    "Branding",
    "Software Engineering"
];

$active = $_GET['cat'] ?? $categories[0];

$stmt = $conn->prepare(
    "SELECT * FROM projects WHERE category=? ORDER BY id DESC"
);
$stmt->bind_param("s", $active);
$stmt->execute();
$projects = $stmt->get_result();
?>

<section id="portfolio">
    <div class="section-container">
        <h2>Our Projects</h2>

        <!-- FILTER BAR -->
        <div class="filters">
            <select onchange="location=this.value + '#portfolio'">
                <?php foreach($categories as $cat): ?>
                    <option 
                        value="?cat=<?= urlencode($cat) ?>"
                        <?= $active==$cat?'selected':'' ?>>
                        <?= $cat ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="filter-links">
                <?php foreach($categories as $cat): ?>
                    <a href="?cat=<?= urlencode($cat) ?>#portfolio"
                       class="<?= $active==$cat?'active':'' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- PROJECTS -->
        <div class="projects">

            <?php if($projects->num_rows): while($p=$projects->fetch_assoc()): ?>

                <div class="project-big">

                    <?php if(!empty($p['link'])): ?>
                        <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank">
                            <img loading="lazy"
                                 src="/hivex-labs/uploads/projects/<?= htmlspecialchars($project['image']) ?>"
                                 alt="<?= htmlspecialchars($project['title']) ?>">
                        </a>
                    <?php else: ?>
                        <img loading="lazy"
                             src="/hivex-labs/uploads/projects/<?= htmlspecialchars($project['image']) ?>"
                             alt="<?= htmlspecialchars($p['title']) ?>">
                    <?php endif; ?>

                    <div class="caption">
                        <?= htmlspecialchars($p['title']) ?>
                    </div>

                </div>

            <?php endwhile; else: ?>
                <p class="empty">No projects found.</p>
            <?php endif; ?>

        </div>
    </div>
</section>

<style>
/* ===== PORTFOLIO SECTION ===== */
.portfolio{
    padding:60px 6%;
    background:#0f1316; 
    display:flex;
    justify-content:center;
}

.portfolio .container{
    background: rgba(4,5,6,0.95);
    border-radius:20px;
    padding:40px 30px;
    max-width:1200px;
    border:1px solid #00ebfa;
}

/* Section title */
.portfolio h2{
    text-align:center;
    font-size:30px;
    color: #00ebfa;
    margin-bottom:40px;
}

/* FILTERS */
.filters{
    display:flex;
    justify-content:center;
    margin-bottom:50px;
    flex-wrap:wrap;
    gap:20px;
}

.filter-links{
    display:flex;
    gap:28px;
}

.filter-links a{
    text-decoration:none;
    color: #9fb6c2;
    font-size:15px;
    position:relative;
    transition: all .25s ease;
}

.filter-links a.active,
.filter-links a:hover{
    color: #00ebfa;
}

.filter-links a.active::after{
    content:'';
    position:absolute;
    bottom:-8px;
    left:0;
    width:100%;
    height:2px;
    background: #00ebfa;
}

/* Mobile dropdown */
.filters select{
    display:none;
    background:#0f1316;
    color:#fff;
    padding:12px 16px;
    border-radius:10px;
    border:1px solid rgba(0,255,136,.2);
    cursor:pointer;
}

/* PROJECTS — MODERN LAYOUT */
.projects{
    display:flex;
    gap:24px;
    overflow-x:auto;
    padding-bottom:20px;
    scroll-snap-type:x mandatory;
}

/* hide scrollbar */
.projects::-webkit-scrollbar{
    height:6px;
}
.projects::-webkit-scrollbar-thumb{
    background:rgba(0,255,136,.25);
    border-radius:10px;
}

/* Each project card – modern glass look */
.project-big{
    flex:0 0 auto;
    width:300px;
    scroll-snap-align:start;
    border-radius:16px;
    overflow:hidden;
    background: linear-gradient(145deg, rgba(255,255,255,0.05), rgba(0,255,136,0.05));
    border: 1px solid rgba(0,255,136,0.25);
    backdrop-filter: blur(12px);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    position:relative;
}

/* Hover effect – subtle lift + glow */
.project-big:hover{
    transform: translateY(-8px) scale(1.03);
    border-color: rgba(0,255,136,0.45);
}

/* Project images */
.project-big img{
    width:100%;
    height:auto;
    display:block;
    border-bottom:1px solid rgba(0,255,136,0.1);
    transition: transform 0.3s ease;
}

.project-big a img:hover{
    transform: scale(1.05);
    cursor:pointer;
}

/* Caption overlay */
.caption{
    padding:12px 16px;
    font-size:14px;
    font-weight:600;
    color:#00ebfa;
    text-align:left;
}

/* Mobile tweaks */
@media(max-width:768px){
    .project-big{
        width:85%;
    }
    .filters select{
        display:block;
    }
    .filter-links{
        display:none;
    }
}
</style>
