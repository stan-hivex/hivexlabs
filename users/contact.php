<?php
require_once __DIR__ . "/../config/db.php";

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $message = $conn->real_escape_string($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $conn->prepare(
            "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $message);
        $msg = $stmt->execute()
            ? "Message sent successfully!"
            : "Failed to send message. Try again.";
    } else {
        $msg = "All fields are required.";
    }
}

$contact = $conn->query("SELECT * FROM contact_section LIMIT 1")->fetch_assoc();
?>

<section id="contact">
    <div class="contact-wrapper">

        <h2>Contact Us</h2>

        <?php if($msg): ?>
            <div class="status <?= strpos($msg,'successfully')!==false?'success':'error' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <div class="contact-info">
            <?php if(!empty($contact['address'])): ?>
                <p>📍 <?= htmlspecialchars($contact['address']) ?></p>
            <?php endif; ?>
            <?php if(!empty($contact['email'])): ?>
                <p>✉️ <?= htmlspecialchars($contact['email']) ?></p>
            <?php endif; ?>
            <?php if(!empty($contact['phone'])): ?>
                <p>📞 <?= htmlspecialchars($contact['phone']) ?></p>
            <?php endif; ?>
        </div>

        <form method="post" class="contact-form">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>

    </div>
</section>

<style>
/* Centered and smaller layout */
#contact {
    padding: 0px 15px;
    display: flex;
    justify-content: center;
}

.contact-wrapper {
    width: 100%;
    max-width: 600px;  /* smaller than before */
    background: #0f172a;
    border-radius: 18px;
    padding: 35px;
    border: 1px solid rgba(59,130,246,0.25);
}

#contact h2 {
    color: #00ebfa;
    text-align: center;
    margin-bottom: 25px;
}

.contact-info p {
    margin-bottom: 8px;
    color: #e6eafa;
    font-size: 0.95rem;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 20px;
}

.contact-form input,
.contact-form textarea {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.8);
    background: #020617;
    color: #fff;
    font-size: 0.95rem;
}

.contact-form button {
    background: #00ebfa;
    padding: 12px;
    border: none;
    border-radius: 10px;
    color: #020617;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

.contact-form button:hover {
    background: #0ac0e8;
}

.status {
    margin-bottom: 16px;
    font-weight: 700;
    text-align: center;
}
.status.success { color: #22c55e; }
.status.error { color: #ef4444; }
</style>
