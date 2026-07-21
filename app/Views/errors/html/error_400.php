<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Page introuvable | Mobile Money</title>
  
  <!-- Polices Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  
  <!-- Votre fichier CSS compilé -->
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="d-flex flex-column min-vh-100 justify-content-center align-items-center py-5">


  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        
        <!-- Carte Neo-Brutalist principale -->
        <div class="card p-4 p-md-5 mb-4">
          
          <!-- Badge d'erreur Flat -->
          <div class="d-inline-block bg-teal text-lime px-3 py-1 rounded-pill mb-3 border-flat-sm fw-bold">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> ERREUR 404
          </div>

          <!-- Titre principal géant -->
          <h1 class="display-1 fw-bold text-teal mb-0 error-code">404</h1>
          
          <h2 class="h3 font-display text-black mb-3 fw-bold">
            ERROR !
          </h2>
          
          <p class="text-muted mb-4 fs-6">
            La page ou le service que vous cherchez a été déplacé, supprimé ou n'a jamais existé dans l' application.
          </p>

          <!-- Bloc récapitulatif factice façon ticket de caisse -->
          <div class="error-receipt p-3 rounded mb-4 text-start">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted small">Code statut :</span>
              <span class="fw-bold text-danger">PAGE_NOT_FOUND</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted small">Destination :</span>
              <span class="fw-bold text-break text-teal font-monospace small" id="currentUrl">/inconnue</span>
            </div>
          </div>

          <!-- Actions de redirection -->
          <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/" class="btn btn-success btn-lg px-4 gap-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-house-door-fill"></i> Retour au tableau de bord
            </a>
            <!-- <a href="support.html" class="btn btn-secondary btn-lg px-4 gap-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-headset"></i> Support client
            </a> -->
          </div>

        </div>

        <!-- Footer minimaliste -->
        <p class="small text-muted mb-0">
          Mobile Money &copy; 2026 — Tous droits réservés.
        </p>

      </div>
    </div>
  </div>

  <!-- Script simple pour gérer l'affichage de l'URL et le Dark Mode -->
  <script>
    // Affiche le chemin cassé courant
    document.getElementById('currentUrl').textContent = window.location.pathname;
  </script>
</body>
</html>