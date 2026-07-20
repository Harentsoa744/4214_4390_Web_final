<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Opérateur - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        body { font-size: .875rem; }
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 48px 0 0; }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 500; }
        .sidebar .nav-link.active { font-weight: 700; }
        main { margin-left: 16.66666667%; }
    </style>
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
    <i class="bi bi-moon" id="theme-icon"></i>
    <span id="theme-text">Sombre</span>
</button>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><i class="bi bi-wallet2"></i> Opérateur Mobile Money</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="<?= site_url('operator/logout') ?>">Déconnexion</a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/dashboard') ? 'active' : '' ?>" href="<?= site_url('operator/dashboard') ?>">
                            <i class="bi bi-house-door"></i> Vue d'ensemble
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/prefixes*') ? 'active' : '' ?>" href="<?= site_url('operator/prefixes') ?>">
                            <i class="bi bi-hash"></i> Préfixes Téléphoniques
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/operation-types*') ? 'active' : '' ?>" href="<?= site_url('operator/operation-types') ?>">
                            <i class="bi bi-gear"></i> Types d'opérations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/fees*') ? 'active' : '' ?>" href="<?= site_url('operator/fees') ?>">
                            <i class="bi bi-currency-exchange"></i> Barèmes de frais
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/clients*') ? 'active' : '' ?>" href="<?= site_url('operator/clients') ?>">
                            <i class="bi bi-people"></i> Comptes Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/commissions*') ? 'active' : '' ?>" href="<?= site_url('operator/commissions') ?>">
                            <i class="bi bi-percent"></i> Gestion des commissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= url_is('operator/settlements*') ? 'active' : '' ?>" href="<?= site_url('operator/settlements') ?>">
                            <i class="bi bi-bank"></i> Situation des reversements
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
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
