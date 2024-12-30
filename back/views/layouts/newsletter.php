<!-- Newsletter Start -->
<div class="mb-3">
    <div class="section-title mb-0">
        <h4 class="m-0 text-uppercase font-weight-bold">Newsletter</h4>
    </div>
    <div class="bg-white text-center border border-top-0 p-3">
        <p>Abonnez-vous à notre newsletter pour recevoir les dernières nouvelles et mises à jour.</p>
        <form action="/newsletter/subscribe" method="POST" id="newsletterForm">
            <div class="input-group mb-2" style="width: 100%;">
                <input type="email" 
                       name="email"
                       class="form-control form-control-lg" 
                       placeholder="Votre Email"
                       required>
                <div class="input-group-append">
                    <button type="submit" 
                            class="btn btn-primary font-weight-bold px-3">
                        S'inscrire
                    </button>
                </div>
            </div>
            <small>Nous ne partagerons jamais votre email avec quelqu'un d'autre.</small>
        </form>
    </div>
</div>
<!-- Newsletter End -->

<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[name="email"]').value;
    
    // Add AJAX call here if you want to handle the subscription
    console.log('Abonnement à la newsletter pour:', email);
    
    // Clear the input
    this.querySelector('input[name="email"]').value = '';
    
    // Show success message
    alert('Merci pour votre abonnement!');
});
</script>