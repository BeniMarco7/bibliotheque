<?php
include 'header.php'; // Inclut le header, qui gère aussi session_start()
// include 'config.php'; // Si besoin plus tard
?>

<!-- PAGE ABONNEMENT -->
<div class="container-fluid subscription-page-container" style="margin-top: 2rem; padding-bottom: 3rem;">
    
    <!-- Toggle mensuel / annuel -->
    <div class="text-center mb-5 d-flex justify-content-center">
        <div class="subscription-toggle-container">
            <button type="button" class="btn btn-subscription-toggle active" data-duration="monthly">Mensuel</button>
            <button type="button" class="btn btn-subscription-toggle ms-3" data-duration="annual">Annuel</button>
        </div>
    </div>

    <!-- Cartes abonnements -->
    <div class="row justify-content-center g-4 subscription-cards-wrapper">

        <!-- Fontaine -->
        <div class="col-md-6 col-lg-6">
            <div class="card subscription-card card-fontaine">
                <div class="card-body">
                    <h3 class="card-title">Homère</h3>
                    <p class="subscription-price monthly-price">$9.99<span class="duration">/mois</span></p>
                    <p class="subscription-price annual-price d-none">$99.99<span class="duration">/an</span></p>
                    <ul class="list-unstyled subscription-features">
                        <li><i class="bi bi-check-circle-fill"></i> 1 livre offert définitivement chaque mois</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de genre narratif</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de genre romanesque</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de genre poétique</li>
                        <li><i class="bi bi-check-circle-fill"></i> Futur forum accessible</li>
                    </ul>
                    <button class="btn btn-subscribe mt-4" data-plan="Homère">Souscrire</button>
                </div>
            </div>
        </div>

        <!-- Shakespeare -->
        <div class="col-md-6 col-lg-6">
            <div class="card subscription-card card-shakespeare">
                <div class="card-body">
                    <h3 class="card-title">Aristote</h3>
                    <p class="subscription-price monthly-price">$14.99<span class="duration">/mois</span></p>
                    <p class="subscription-price annual-price d-none">$149.99<span class="duration">/an</span></p>
                    <ul class="list-unstyled subscription-features">
                        <li><i class="bi bi-check-circle-fill"></i> Tous les avantages de l'offre Homère</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de la catégorie Jeunesse</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de la catégorie Scolaire</li>
                        <li><i class="bi bi-check-circle-fill"></i> Accès à tous les livres de la catégorie Art, Culture & Société</li>
                        <li><i class="bi bi-check-circle-fill"></i> Le livre offert est au choix !</li>
                    </ul>
                    <button class="btn btn-subscribe mt-4" data-plan="Aristote">Souscrire</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT toggle abonnements -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyBtn = document.querySelector('[data-duration="monthly"]');
        const annualBtn = document.querySelector('[data-duration="annual"]');
        const monthlyPrices = document.querySelectorAll('.monthly-price');
        const annualPrices = document.querySelectorAll('.annual-price');
        const subscribeButtons = document.querySelectorAll('.btn-subscribe');

        monthlyBtn.addEventListener('click', function() {
            monthlyBtn.classList.add('active');
            annualBtn.classList.remove('active');
            monthlyPrices.forEach(price => price.classList.remove('d-none'));
            annualPrices.forEach(price => price.classList.add('d-none'));
        });

        annualBtn.addEventListener('click', function() {
            annualBtn.classList.add('active');
            monthlyBtn.classList.remove('active');
            annualPrices.forEach(price => price.classList.remove('d-none'));
            monthlyPrices.forEach(price => price.classList.add('d-none'));
        });

        subscribeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const planName = this.dataset.plan;
                const isMonthly = monthlyBtn.classList.contains('active');
                const durationText = isMonthly ? 'mensuelle' : 'annuelle';
                
                alert(`Vous avez choisi l'abonnement "${planName}" en formule ${durationText} ! La page de paiement n'est pas encore implémentée.`);
            });
        });
    });
</script>

<?php include 'footer.php'; ?>
