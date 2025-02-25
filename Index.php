<?php
include 'header.php';
?>

<!-- Welcome Section -->
<div style="background-color: #ef684c;">
    <div class="container text-white py-5">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h1 class="mb-5 display-2"><strong>Welcome to Syzygy Snazzy</strong></h1>
                <p>
                    Your global e-commerce platform for all your needs. Discover the best products at the best prices.
                </p>
                <!-- Optional button links to other pages -->
                <a href="shop_now.php" class="btn btn-primary">Shop Now</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="images/hero.png" class="img-fluid" alt="hero" />
            </div>
        </div>
    </div>
</div>



<!-- Apparel Section -->


<!-- product Section -->
<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-4 text-primary">Trending Apparel</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Product Cards -->
            <div class="col">
                <div class="card shadow-sm h-100">
                    <img src="images/stylishs.jpeg" class="card-img-top" alt="Stylish Jacket">
                    <div class="card-body">
                        <h5 class="card-title">Stylish Jacket</h5>
                        <p class="card-text text-muted">$49.99</p>
                        <a href="checkout.php?product_id=15" class="btn btn-danger">Buy Now</a>
                        <a href="add_to_cart.php?product_id=15" class="btn btn-outline-primary">Add Cart</a>

                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card shadow-sm h-100">
                    <img src="images/t-shirt.jpeg" class="card-img-top" alt="Casual T-Shirt">
                    <div class="card-body">
                        <h5 class="card-title">Casual T-Shirt</h5>
                        <p class="card-text text-muted">$19.99</p>
                        <a href="checkout.php?product_id=14" class="btn btn-danger">Buy Now</a>
                        <a href="add_to_cart.php?product_id=14" class="btn btn-outline-primary">Add Cart</a>

                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card shadow-sm h-100">
                    <img src="images/stylishs.jpeg" class="card-img-top" alt="Elegant Dress">
                    <div class="card-body">
                        <h5 class="card-title">Elegant Dress</h5>
                        <p class="card-text text-muted">$59.99</p>
                        <a href="checkout.php?product_id=13" class="btn btn-danger">Buy Now</a> 
                        <a href="add_to_cart.php?product_id=13" class="btn btn-outline-primary">Add Cart</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Books Section -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-4 text-danger">Top Selling Books</h2>
        <div class="row justify-content-center g-4">
            
            <!-- Book Cards -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <img src="images/Book1.jpeg" class="card-img-top" alt="The Success Mindset">
                    <div class="card-body">
                        <h5 class="card-title">The Success Mindset</h5>
                        <p class="card-text text-muted">$14.99</p>
                        <a href="checkout.php?product_id=17" class="btn btn-danger">Buy Now</a>
                        <a href="add_to_cart.php?product_id=17" class="btn btn-outline-primary">Add Cart</a>

                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <img src="images/book2.png" class="card-img-top" alt="E-commerce Mastery">
                    <div class="card-body">
                        <h5 class="card-title">E-commerce Mastery</h5>
                        <p class="card-text text-muted">$29.99</p>
                        <a href="checkout.php?product_id=18" class="btn btn-danger">Buy Now</a>
                        <a href="add_to_cart.php?product_id=18" class="btn btn-outline-primary">Add Cart</a>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- Cloud Services Section -->
<section id="cloud" class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="mb-3 text-dark">Cloud Services</h2>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Explore our secure and scalable cloud computing solutions designed for businesses of all sizes.
        </p>

        <!-- Cloud Services Grid -->
        <div class="row g-4 mt-4 justify-content-center">
            
            <!-- Cloud Storage -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 text-center">
                    <img src="images/Cloud Storage.jpg" alt="Cloud Storage" class="img mb-3" style="width: 80px;">
                    <h3 class="h5">Cloud Storage</h3>
                    <p class="text-muted">Securely store and access your data from anywhere.</p>
                    <a href="checkout.php?product_id=19" class="btn btn-danger">Buy Now</a>

                </div>
            </div>

            <!-- Cloud Hosting -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 text-center">
                    <img src="images/cloud-hosting.jpg" alt="Cloud Hosting" class="img-fluid mb-3" style="width: 80px;">
                    <h3 class="h5">Cloud Hosting</h3>
                    <p class="text-muted">Powerful and scalable hosting solutions for your business.</p>
                    <a href="checkout.php?product_id=20" class="btn btn-danger">Buy Now</a>

                </div>
            </div>

            <!-- Cloud Security -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 text-center">
                    <img src="images/cloud security.jpeg" alt="Cloud Security" class="img-fluid mb-3" style="width: 80px;">
                    <h3 class="h5">Cloud Security</h3>
                    <p class="text-muted">Top-notch security features to protect your data.</p>
                    <a href="checkout.php?product_id=21" class="btn btn-danger">Buy Now</a>

                </div>
            </div>

        </div>

        <!-- Call to Action -->
        <a href="shop_now.php" class="btn btn-primary mt-4 px-4 py-2">
            Learn More
        </a>
    </div>
</section>



<!-- Contact Section -->
<section id="contact" style="background-color: #e9ecef;">
    <div class="container py-5">
        <h2 class="text-center mb-4">Contact Us</h2>
        <p class="text-center">
            Have any questions? Get in touch with us at <a href="mailto:info@syzygysnazzy.com">info@syzygysnazzy.com</a> or call us at (123) 456-7890.
        </p>
    </div>
</section>

<?php
include 'footer.php';
?>