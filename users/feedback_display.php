<?php
require_once __DIR__ . "/../config/db.php";

// Fetch approved feedback
$approved_feedback = $conn->query(
    "SELECT * FROM feedback WHERE approved=1 ORDER BY id DESC"
);
?>

<section id="feedback">
    <div class="section-container">

        <h2>What Our Clients Say</h2>

        <?php if($approved_feedback->num_rows): ?>
    
    <div class="feedback-slider">

        <button class="nav-btn left" id="prevBtn">&#10094;</button>

        <div class="feedback-track" id="feedbackTrack">
            <?php while($f = $approved_feedback->fetch_assoc()): ?>
                <div class="feedback-card">
                    <strong><?= htmlspecialchars($f['name']) ?></strong>
                    <div class="stars"><?= str_repeat('★', $f['rating']) ?></div>
                    <p><?= nl2br(htmlspecialchars($f['message'])) ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <button class="nav-btn right" id="nextBtn">&#10095;</button>

    </div>

<?php else: ?>
    <p>No feedback yet.</p>
<?php endif; ?>


    </div>
</section>

<style>
.feedback{
    padding:80px 6%;
    background:#010f2b;
    display:flex;
    justify-content:center;
}

.feedback .section-container{
    max-width:1000px;
    width:100%;
    border-radius:20px;
    padding:40px;
    background:#020617;
    border:1px solid rgba(59,130,246,0.25);
    position:relative;
}

.feedback h2{
    color:#00ebfa;
    margin-bottom:30px;
}

/* SLIDER */

.feedback-slider{
    position:relative;
    overflow:hidden;
}

.feedback-track{
    display:flex;
    gap:20px;
    transition:transform 0.5s ease;
}

/* Cards */
.feedback-card{
    min-width:300px;
    max-width:300px;
    border:1px solid rgba(255,255,255,0.12);
    border-radius:14px;
    padding:20px;
    background:rgba(255,255,255,0.03);
    backdrop-filter: blur(6px);
    flex-shrink:0;
}

.feedback-card .stars{
    color:#f7ca05;
    margin:8px 0;
}

.feedback-card p{
    color:#c7d2fe;
    font-size:14px;
}

/* NAV BUTTONS */

.nav-btn{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:#00ebfa;
    color:#020617;
    border:none;
    width:40px;
    height:40px;
    border-radius:50%;
    cursor:pointer;
    font-size:20px;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:2;
    transition:0.3s ease;
}

.nav-btn:hover{
    background:#38bdf8;
    transform:translateY(-50%) scale(1.1);
}

.nav-btn.left{
    left:-20px;
}

.nav-btn.right{
    right:-20px;
}

</style>

<script>

document.addEventListener("DOMContentLoaded", () => {

    const track = document.getElementById("feedbackTrack");
    const next = document.getElementById("nextBtn");
    const prev = document.getElementById("prevBtn");

    let position = 0;
    const cardWidth = 320; // 300 + gap
    const visibleCards = 3;

    next.addEventListener("click", () => {
        const maxScroll = track.children.length - visibleCards;
        if(position < maxScroll){
            position++;
            track.style.transform = `translateX(-${position * cardWidth}px)`;
        }
    });

    prev.addEventListener("click", () => {
        if(position > 0){
            position--;
            track.style.transform = `translateX(-${position * cardWidth}px)`;
        }
    });

});
</script>


