<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $appName ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= '//' . $_SERVER['HTTP_HOST'] . '/inventory/public/css/app.css' ?>">
    <script>
        const t = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
    </script>
</head>

<body class="auth-body">

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="<?= '//' . $_SERVER['HTTP_HOST'] . '/inventory/public/js/app.js' ?>"></script>

    <?= $content ?>

    <button class="auth-theme-btn" id="authThemeToggle" title="Toggle dark mode">
      <span id="authThemeIcon">🌙</span>
    </button>

</body>

</html>