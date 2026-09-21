<?php
$policySections = [
    [
        'title' => 'What cookies are used',
        'content' => 'HiveX Labs uses a first-party cookie named hivex_cookie_consent to remember the cookie choices made in this dialog. It stores only the consent version, timestamp, and whether functional, analytics, or marketing categories are enabled. It does not store names, email addresses, passwords, or other sensitive information.'
    ],
    [
        'title' => 'Cookie categories',
        'content' => 'Necessary cookies are always enabled because they support the consent experience and core website behavior. Functional or preference cookies are optional and are not loaded through the consent system unless you enable them. Analytics cookies are optional and are not loaded unless you enable them. Marketing cookies are optional and are not loaded unless you enable them. No analytics, advertising, or marketing services are currently installed in the inspected public website code.'
    ],
    [
        'title' => 'How long choices last',
        'content' => 'The consent choice is stored in your browser for 180 days. It is scoped to this website, uses SameSite=Lax, and is marked Secure when the website is served over HTTPS. Clearing browser cookies removes the choice and shows the consent dialog again.'
    ],
    [
        'title' => 'Changing your choice',
        'content' => 'Use the Cookie Preferences button at the bottom of the page to reopen the dialog and save a different choice. Optional categories can be turned off at any time. Turning a category off prevents future registered scripts in that category from loading; already-loaded third-party code may require a page refresh to fully stop.'
    ],
    [
        'title' => 'Business details to confirm',
        'content' => 'Before publishing this policy, HiveX Labs should confirm its legal business name, contact address, responsible privacy contact, retention periods, and any future analytics or advertising providers. The public code inspected for this implementation currently includes contact and feedback forms that store submitted names, email addresses, messages, ratings, and related product feedback in MySQL; those data practices should be described in the Privacy Policy by the site owner.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy | HiveX Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --muted: #cbd5df; --accent: #00ebfa; --surface: rgba(15,23,42,.85); }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #05070d; color: var(--muted); font-family: Inter, sans-serif; position: relative; overflow-x: hidden; }
        .circuit-bg { position: fixed; inset: 0; z-index: -3; background: #05070d; background-image: radial-gradient(circle at 50% 50%, transparent 34px, rgba(0,234,255,0.35) 35px, rgba(0,234,255,0.35) 36px, transparent 37px); background-size: 70px 60.62px; }
        .circuit-bg::before { content: ""; position: absolute; inset: 0; background-image: linear-gradient(90deg, rgba(0,234,255,0.25) 1px, transparent 1px), linear-gradient(60deg, rgba(0,234,255,0.25) 1px, transparent 1px), linear-gradient(-60deg, rgba(0,234,255,0.25) 1px, transparent 1px); background-size: 80px 140px; opacity: 0.25; }
        main { width: min(88%, 1200px); margin: 0 auto; padding: 80px 0 120px; }
        h1 { color: var(--accent); text-align: center; margin-bottom: 40px; }
        .intro { max-width: 680px; margin: 0 auto 40px; line-height: 1.7; text-align: center; font-size: 18px; }
        article { margin-bottom: 30px; padding: 20px; border: 1px solid rgba(0,235,250,.2); border-radius: 12px; background: var(--surface); }
        h2 { margin: 0 0 10px; color: #3b82f6; font-size: 24px; }
        article p { margin: 0; line-height: 1.7; font-size: 18px; color: #cbd5df; }
        .back { display: inline-block; margin-bottom: 28px; color: var(--accent); font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
    <div class="circuit-bg" aria-hidden="true"></div>
    <main>
        <a class="back" href="hive.php">&larr; Back to HiveX Labs</a>
        <h1>Cookie Policy</h1>
        <p class="intro">This page explains the cookie choices used by the HiveX Labs website. Last reviewed: 2025-09-01.</p>
        <?php foreach ($policySections as $section): ?>
            <article>
                <h2><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= htmlspecialchars($section['content'], ENT_QUOTES, 'UTF-8') ?></p>
            </article>
        <?php endforeach; ?>
    </main>
    <?php include __DIR__ . "/../includes/cookie-consent.php"; ?>
</body>
</html>
