<?php
require_once __DIR__ . "/../config/db.php";
require_once "auth.php";

if (empty($_SESSION['hero_tools_csrf'])) {
    $_SESSION['hero_tools_csrf'] = bin2hex(random_bytes(32));
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'];

function uploadToolLogo(array $file, array $allowedExtensions): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return '';
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true) || !is_uploaded_file($file['tmp_name'])) {
        return '';
    }

    $filename = 'tool_' . bin2hex(random_bytes(12)) . '.' . $extension;
    $relativePath = 'uploads/' . $filename;
    $destination = __DIR__ . '/../' . $relativePath;

    return move_uploaded_file($file['tmp_name'], $destination) ? $relativePath : '';
}

function removeToolLogo(string $relativePath): void
{
    $uploadsRoot = realpath(__DIR__ . '/../uploads');
    $filePath = realpath(__DIR__ . '/../' . ltrim(str_replace('\\', '/', $relativePath), '/'));
    $prefix = $uploadsRoot ? $uploadsRoot . DIRECTORY_SEPARATOR : '';

    if ($uploadsRoot && $filePath && strpos($filePath, $prefix) === 0) {
        @unlink($filePath);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['hero_tools_csrf'], $csrf)) {
        http_response_code(400);
        exit('Invalid request.');
    }

    $action = $_POST['action'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

    if ($action === 'delete' && $id) {
        $select = $conn->prepare("SELECT logo FROM hero_tools WHERE id=? LIMIT 1");
        $select->bind_param("i", $id);
        $select->execute();
        $tool = $select->get_result()->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM hero_tools WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0 && !empty($tool['logo'])) {
            removeToolLogo($tool['logo']);
        }

        header('Location: hero_tools.php?deleted=1');
        exit;
    }

    if ($action === 'save') {
        $name = trim($_POST['name'] ?? '');
        $status = ($_POST['status'] ?? 'inactive') === 'active' ? 'active' : 'inactive';
        $logo = uploadToolLogo($_FILES['logo'] ?? [], $allowedExtensions);

        if ($name === '') {
            header('Location: hero_tools.php?error=missing_name');
            exit;
        }

        if ($id) {
            $current = $conn->prepare("SELECT logo FROM hero_tools WHERE id=? LIMIT 1");
            $current->bind_param("i", $id);
            $current->execute();
            $existing = $current->get_result()->fetch_assoc();
            $oldLogo = $existing['logo'] ?? '';

            if ($logo) {
                $stmt = $conn->prepare("UPDATE hero_tools SET name=?, logo=?, status=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $logo, $status, $id);
            } else {
                $stmt = $conn->prepare("UPDATE hero_tools SET name=?, status=? WHERE id=?");
                $stmt->bind_param("ssi", $name, $status, $id);
            }
            $stmt->execute();

            if ($logo && $oldLogo) {
                removeToolLogo($oldLogo);
            }
        } else {
            if (!$logo) {
                header('Location: hero_tools.php?error=missing_logo');
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO hero_tools (name, logo, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $logo, $status);
            $stmt->execute();
        }

        header('Location: hero_tools.php?saved=1');
        exit;
    }
}

$editTool = null;
if (!empty($_GET['edit'])) {
    $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($editId) {
        $stmt = $conn->prepare("SELECT * FROM hero_tools WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $editTool = $stmt->get_result()->fetch_assoc() ?: null;
    }
}

$tools = $conn->query("SELECT * FROM hero_tools ORDER BY id DESC");
$csrf = htmlspecialchars($_SESSION['hero_tools_csrf'], ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Hero Tools</title>
<style>
body{margin:0;padding:24px;background:#040506;color:#cbd5df;font-family:Inter,Arial,sans-serif}
.wrap{max-width:1000px;margin:0 auto}.panel,.tool{padding:20px;margin-bottom:20px;background:#0f172a;border:1px solid rgba(34,211,238,.25);border-radius:12px}
h1,h2{color:#00ebfa}.page-header,.list-heading{display:flex;align-items:center;justify-content:space-between;gap:18px}.page-header{margin-bottom:24px}.page-header h1{margin-bottom:6px}.page-header p{margin:0}.page-links{display:flex;gap:8px;flex-wrap:wrap}.button.secondary{margin-top:0;background:#1e293b;color:#cbd5df;border:1px solid #334155}.list-heading{margin:28px 0 14px}.list-heading h2{margin:0}label{display:block;margin-top:12px}input,select{width:100%;box-sizing:border-box;padding:10px;margin-top:6px;border:1px solid #334155;border-radius:7px;background:#020617;color:#fff}button,.button{display:inline-block;margin-top:16px;padding:10px 16px;border:0;border-radius:7px;background:#00ff88;color:#040506;font-weight:700;text-decoration:none;cursor:pointer}.danger{background:#dc2626;color:#fff}.muted{color:#94a3b8}.message{color:#22c55e}.error{color:#f87171}.tool{display:grid;grid-template-columns:90px 1fr auto;gap:18px;align-items:center}.tool img{width:80px;height:64px;object-fit:contain;background:#fff;border-radius:7px}.tool-actions{display:flex;gap:8px;flex-wrap:wrap}.tool-actions a,.tool-actions button{margin-top:0}@media(max-width:600px){body{padding:14px}.page-header,.list-heading{align-items:flex-start;flex-direction:column}.tool{grid-template-columns:1fr}.tool img{width:100%;height:90px}}
</style>
</head>
<body>
<main class="wrap">
    <div class="page-header">
        <div>
            <h1>Hero Tools Management</h1>
            <p class="muted">Manage the logos displayed in the public Hero Tools section.</p>
        </div>
        <div class="page-links">
            <a class="button secondary" href="dashboard.php">Dashboard</a>
            <a class="button secondary" href="../hive.php#hero-tools" target="_blank" rel="noopener">View on Website</a>
        </div>
    </div>
    <?php if (isset($_GET['saved'])): ?><p class="message">Tool saved successfully.</p><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><p class="message">Tool deleted successfully.</p><?php endif; ?>
    <?php if (isset($_GET['error'])): ?><p class="error">Please provide a tool name and logo where required.</p><?php endif; ?>

    <section class="panel">
        <h2><?= $editTool ? 'Edit Hero Tool' : 'Add Hero Tool' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int)($editTool['id'] ?? 0) ?>">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <label>Tool name<input type="text" name="name" value="<?= htmlspecialchars($editTool['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required></label>
            <label>Status<select name="status"><option value="active" <?= (($editTool['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option><option value="inactive" <?= (($editTool['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option></select></label>
            <label>Logo <?= $editTool ? '<span class="muted">(optional when editing)</span>' : '' ?><input type="file" name="logo" accept="image/*" <?= $editTool ? '' : 'required' ?>></label>
            <button type="submit"><?= $editTool ? 'Save Changes' : 'Add Tool' ?></button>
            <?php if ($editTool): ?><a class="button" href="hero_tools.php">Cancel</a><?php endif; ?>
        </form>
    </section>

    <div class="list-heading">
        <h2>Existing Hero Tools</h2>
        <span class="muted"><?= $tools ? (int)$tools->num_rows : 0 ?> tool(s)</span>
    </div>
    <?php if ($tools && $tools->num_rows): while ($tool = $tools->fetch_assoc()): ?>
        <article class="tool">
            <img src="../<?= htmlspecialchars($tool['logo'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?>">
            <div><strong><?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></strong><br><span class="muted">Status: <?= htmlspecialchars($tool['status'], ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="tool-actions">
                <a class="button" href="hero_tools.php?edit=<?= (int)$tool['id'] ?>">Edit</a>
                <form method="post" onsubmit="return confirm('Delete this hero tool?');">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$tool['id'] ?>"><input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <button class="danger" type="submit">Delete</button>
                </form>
            </div>
        </article>
    <?php endwhile; else: ?><p class="muted">No hero tools have been added.</p><?php endif; ?>
</main>
</body>
</html>
