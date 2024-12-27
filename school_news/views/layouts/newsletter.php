<!-- Newsletter Start -->
<div class="mb-3">
    <div class="section-title mb-0">
        <h4 class="m-0 text-uppercase font-weight-bold">Newsletter</h4>
    </div>
    <div class="bg-white text-center border border-top-0 p-3">
        <p>Subscribe to our newsletter to get the latest news and updates.</p>
        <form action="/newsletter/subscribe" method="POST" id="newsletterForm">
            <div class="input-group mb-2" style="width: 100%;">
                <input type="email" 
                       name="email"
                       class="form-control form-control-lg" 
                       placeholder="Your Email"
                       required>
                <div class="input-group-append">
                    <button type="submit" 
                            class="btn btn-primary font-weight-bold px-3">
                        Sign Up
                    </button>
                </div>
            </div>
            <small>We'll never share your email with anyone else.</small>
        </form>
    </div>
</div>
<!-- Newsletter End -->

<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[name="email"]').value;
    
    // Add AJAX call here if you want to handle the subscription
    console.log('Newsletter subscription for:', email);
    
    // Clear the input
    this.querySelector('input[name="email"]').value = '';
    
    // Show success message
    alert('Thank you for subscribing!');
});
</script>