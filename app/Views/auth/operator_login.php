<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Opérateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; }
    </style>
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
    <i class="bi bi-moon" id="theme-icon"></i>
    <span id="theme-text">Sombre</span>
</button>

<div class="card login-card">
    <div class="text-center mb-4">
        <i class="bi bi-shield-lock fs-1" style="color: #0C4650;"></i>
        <h3 class="mt-3">Administration</h3>
    </div>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('operator/login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label text-muted">Nom d'utilisateur</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label text-muted">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
    
    <div class="text-center mt-3">
        <a href="<?= site_url('login') ?>" class="text-decoration-none"><small>Retour au site client</small></a>
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
