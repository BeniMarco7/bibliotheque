<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()
// include 'config.php'; // Inclus si vous avez besoin de la connexion DB ici, mais pas nécessaire pour les citations statiques
?>

<main>
    <section class="hero-section text-center d-flex align-items-center justify-content-center">
        <div class="container text-white">
            <h1 class="display-3">Bienvenue sur Biblizone</h1>
            <p class="lead mb-4">Votre site de bibliothèque en ligne entièrement fait par des <br> ETUDIANTS !</p>
            <a href="livres.php" class="btn btn-custom btn-lg">Découvrez nos catégories</a>
        </div>
    </section>

    <section class="background-image-section d-flex align-items-center justify-content-center text-white py-5">
        <div class="container text-center">
            </div>
    </section>

<img src="images/bibliotheque.png" alt="Bibliothèque" class="hero-bottom-image mt-5 img-fluid">

    <section class="citations-section py-5">
        <div class="container">
            <h2 class="mb-5">Citations du jour</h2>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Aimer, c'est savoir dire je t'aime sans parler."</p>
                        <p>Victor Hugo</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Je veux vivre selon ma propre volonté. Si je meurs en essayant, alors tant pis."</p>
                        <p>Kentaro Miura</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Ce monde est cruel, mais aussi si beau."</p>
                        <p>Hajime Isayama</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Les petites routines quotidiennes sont souvent les plus résistantes."</p>
                        <p>Richard Hoggart</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Il faut se méfier du calme, c'est là que couve l'orage."</p>
                        <p>Gustave Flaubert</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="citation-card">
                        <p>"Dans l'univers, tout est permis, sauf de ne pas être sincère."</p>
                        <p>Albert Camus</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include 'footer.php'; // Inclut le pied de page ?>
