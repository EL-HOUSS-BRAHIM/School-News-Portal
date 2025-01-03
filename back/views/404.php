<?php require_once __DIR__ . '/layouts/article_header.php'; ?>

<div class="container-fluid mt-5 mb-3 pt-3">
    <div class="container text-center">
        <h1 class="display-1 font-weight-bold">404</h1>
        <h2 class="mb-4">Page non trouvée</h2>
        <p class="mb-4">Désolé, la page que vous recherchez n'existe pas. Vous pouvez retourner à la page d'accueil ou utiliser la barre de recherche ci-dessous.</p>
        <a href="<?php echo $app['constants']['BASE_URL']; ?>" class="btn btn-primary">Retour à l'accueil</a>
        <div class="input-group mt-4" style="max-width: 500px; margin: auto;">
            <form action="<?php echo $app['constants']['BASE_URL']; ?>/search" method="GET" class="w-100">
                <div class="input-group">
                    <input type="text" name="q" class="form-control border-0" placeholder="Rechercher des nouvelles...">
                    <div class="input-group-append">
                        <button class="input-group-text text-dark border-0 px-3" style="background-color: #2558d8;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>