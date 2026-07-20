<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mobile Money' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .app-container { max-width: 600px; margin: 0 auto; padding-top: 20px; }
    </style>
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
    <i class="bi bi-moon" id="theme-icon"></i>
    <span id="theme-text">Sombre</span>
</button>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container app-container pb-0 pt-0">
        <a class="navbar-brand" href="<?= site_url('client/dashboard') ?>"><i class="bi bi-wallet2"></iMobile Money</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(session()->get('client_id')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('client/dashboard') ?>">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('client/history') ?>">Historique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= site_url('logout') ?>">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container app-container mb-5">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-3"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const icon = document.getElementById('theme-icon');
        const text = document.getElementById('theme-text');
        
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('bi-moon');
            icon.classList.add('bi-sun');
            text.textContent = 'Clair';
            localStorage.setItem('theme', 'dark');
        } else {
            icon.classList.remove('bi-sun');
            icon.classList.add('bi-moon');
            text.textContent = 'Sombre';
            localStorage.setItem('theme', 'light');
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('theme-icon').classList.remove('bi-moon');
        document.getElementById('theme-icon').classList.add('bi-sun');
        document.getElementById('theme-text').textContent = 'Clair';
    }
</script>
</body>
</html>
