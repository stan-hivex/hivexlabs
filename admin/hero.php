<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

if (empty($_SESSION['hero_csrf'])) {
    $_SESSION['hero_csrf'] = bin2hex(random_bytes(32));
}

// Handle adding/editing slides
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'delete') {
        $csrf = $_POST['csrf'] ?? '';
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id || !hash_equals($_SESSION['hero_csrf'], $csrf)) {
            http_response_code(400);
            exit('Invalid delete request.');
        }

        $select = $conn->prepare("SELECT image FROM hero_section WHERE id=? LIMIT 1");
        $select->bind_param("i", $id);
        $select->execute();
        $slide = $select->get_result()->fetch_assoc();

        $delete = $conn->prepare("DELETE FROM hero_section WHERE id=?");
        $delete->bind_param("i", $id);
        $delete->execute();

        if ($delete->affected_rows > 0 && !empty($slide['image'])) {
            $relativeImage = str_replace('\\', '/', ltrim($slide['image'], '/'));
            $uploadsRoot = realpath(__DIR__ . '/../uploads');
            $imagePath = realpath(__DIR__ . '/../' . $relativeImage);
            $uploadsPrefix = $uploadsRoot ? $uploadsRoot . DIRECTORY_SEPARATOR : '';

            if ($uploadsRoot && $imagePath && strpos($imagePath, $uploadsPrefix) === 0) {
                @unlink($imagePath);
            }
        }

        header("Location: hero.php?deleted=1");
        exit;
    }

    $id = $_POST['id'] ?? null;
    $main = $conn->real_escape_string($_POST['main_title']);
    $sub  = $conn->real_escape_string($_POST['sub_title']);
    $btn_text = $conn->real_escape_string($_POST['button_text']);
    $btn_link = $conn->real_escape_string($_POST['button_link']);
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = 'uploads/hero_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $image);
    }

    if ($id) {
        // Update existing slide
        $query = "UPDATE hero_section SET main_title=?, sub_title=?, button_text=?, button_link=?".($image ? ", image='$image'" : "")." WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $main, $sub, $btn_text, $btn_link, $id);
        $stmt->execute();
    } else {
        // Insert new slide
        $stmt = $conn->prepare("INSERT INTO hero_section (main_title, sub_title, button_text, button_link, image) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $main, $sub, $btn_text, $btn_link, $image);
        $stmt->execute();
    }
    header("Location: hero.php?ok=1");
    exit;
}

// Fetch all slides
$slides = $conn->query("SELECT * FROM hero_section ORDER BY id ASC");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Hero Slides</title>
<style>
body{background:#040506;color:var(--muted);font-family:Inter,Arial;padding:20px;--muted:#cbd5df;}
input,textarea{width:100%;padding:10px;margin:6px 0;border-radius:6px;border:1px solid #00ff88;background:#0f1316;color:#cbd5df;}
button{padding:10px 20px;background:#00ff88;border:none;border-radius:8px;font-weight:700;color:#040506;cursor:pointer;}
.delete-button{background:#dc2626;color:#fff;}
.slide-card{background:#FFFFFF;padding:12px;margin-bottom:12px;border-radius:12px;border:1px solid #22D3EE;}
.slide-card img{width:100%;border-radius:8px;margin-bottom:8px;}
</style>
</head>
<body>
<h1>Hero Slides Management</h1>
<?php if(isset($_GET['ok'])): ?><p style="color:#16a34a;">Saved.</p><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><p style="color:#16a34a;">Slide deleted.</p><?php endif; ?>

<h2>Add New Slide</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="">
    <label>Main title</label><br>
    <input name="main_title" value=""><br>
    <label>Sub title</label><br>
    <input name="sub_title" value=""><br>
    <label>Button text</label><br>
    <input name="button_text" value=""><br>
    <label>Button link</label><br>
    <input name="button_link" value=""><br>
    <label>Background image</label><br>
    <input type="file" name="image" accept="image/*"><br>
    <button>Add Slide</button>
</form>

<h2>Existing Slides</h2>
<?php while($s = $slides->fetch_assoc()): ?>
    <div class="slide-card">
        <?php if($s['image']): ?><img src="../<?= $s['image'] ?>" alt="Slide"><?php endif; ?>
        <strong><?= htmlspecialchars($s['main_title']) ?></strong><br>
        <em><?= htmlspecialchars($s['sub_title']) ?></em><br>
        <small>Button: <?= htmlspecialchars($s['button_text']) ?> → <?= htmlspecialchars($s['button_link']) ?></small>
        <form method="post" style="margin-top:8px;">
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <button type="submit">Edit</button>
        </form>
        <form method="post" style="margin-top:8px;" onsubmit="return confirm('Delete this hero slide?');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['hero_csrf'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="delete-button">Delete Slide</button>
        </form>
    </div>
<?php endwhile; ?>
</body>
</html>
