<?php
require_once __DIR__ . "/../config/db.php";

// Fetch team members
$members = $conn->query("SELECT id, name, profession, photo FROM team_member ORDER BY id DESC");
?>

<section id="team">
    <h2>Meet Our Team</h2>

    <div class="team-grid">
        <?php if ($members && $members->num_rows > 0): ?>
            <?php while ($m = $members->fetch_assoc()): ?>
                <div class="member">
                    <img src="../<?= htmlspecialchars($m['photo']) ?>" alt="<?= htmlspecialchars($m['name']) ?>">
                    <h3><?= htmlspecialchars($m['name']) ?></h3>
                    <p><?= htmlspecialchars($m['profession']) ?></p>
                    <a class="btn" href="team_member.php?id=<?= (int)$m['id'] ?>">See More</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No team members added yet.</p>
        <?php endif; ?>
    </div>
</section>

<style>
#team{
    padding:70px 6%;
    background:#040506;
    color:#fff;
    text-align:center;
}
#team h2{
    color:#00ff88;
    margin-bottom:40px;
    letter-spacing:1px;
}
.team-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}
.member{
    background:#0f1316;
    border-radius:14px;
    padding:18px;
    border:1px solid rgba(0,255,136,0.3);
    transition:.3s;
}
.member:hover{
    transform:translateY(-8px);
    box-shadow:0 25px 50px rgba(0,255,136,.15);
}
.member img{
    width:100%;
    height:260px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:12px;
}
.member h3{
    margin:6px 0;
}
.member p{
    color:#cbd5df;
}
.btn{
    display:inline-block;
    margin-top:12px;
    padding:10px 18px;
    background:#00ff88;
    color:#040506;
    border-radius:8px;
    font-weight:700;
    text-decoration:none;
}
</style>
