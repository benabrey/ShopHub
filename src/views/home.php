<?php
/**
 * Home page view
 * @var array $featuredProducts
 * @var array $categories
 */
?>

<main class="homepage">
    <div class="container">

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Welcome to ShopHub</h1>
                <p class="hero-subtitle">Discover amazing products at unbeatable prices</p>
                <a href="/Ecommerce_final_project/public/products.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <a href="/Ecommerce_final_project/public/products.php" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <?php if (!empty($featuredProducts)): ?>
                <div class="product-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         onerror="this.src='https://via.placeholder.com/300x200?text=<?php echo urlencode($product['name']); ?>'">
                                </a>

                                <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] <= 0): ?>
                                    <span class="badge badge-out-of-stock">Out of Stock</span>
                                <?php elseif (isset($product['stock_quantity']) && $product['stock_quantity'] < 10): ?>
                                    <span class="badge badge-low-stock">Only <?php echo $product['stock_quantity']; ?> left!</span>
                                <?php endif; ?>
                            </div>

                            <div class="product-info">
                                <p class="product-category">
                                    <a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode($product['category'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?>
                                    </a>
                                </p>

                                <h3 class="product-name">
                                    <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $product['id']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h3>

                                <div class="product-rating">
                                    <?php if ($product['review_count'] > 0): ?>
                                        <div class="stars">
                                            <?php
                                            $avgRating = $product['avg_rating'];
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $fullStars) {
                                                    echo '<i class="fas fa-star"></i>';
                                                } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                                    echo '<i class="fas fa-star-half-alt"></i>';
                                                } else {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <span class="rating-text">(<?php echo number_format($avgRating, 1); ?>)</span>
                                    <?php else: ?>
                                        <div class="stars" style="color: #d1d5db;">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="rating-text" style="color: #9ca3af;">No reviews</span>
                                    <?php endif; ?>
                                </div>

                                <div class="product-footer">
                                    <div class="product-price">
                                        <span class="price-current">
                                            $<?php echo number_format($product['price'], 2); ?>
                                        </span>
                                    </div>

                                    <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                        <?php if (Auth::check()): ?>
                                            <form action="/Ecommerce_final_project/public/cart.php" method="POST" class="add-to-cart-form-inline">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-primary" title="Add to Cart">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <a href="/Ecommerce_final_project/public/login.php" class="btn btn-sm btn-outline" title="Login to purchase">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-times"></i> Out of Stock
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="section-footer">
                    <a href="/Ecommerce_final_project/public/products.php" class="btn btn-outline btn-lg">
                        Browse All Products <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open fa-4x"></i>
                    <h3>No products available</h3>
                    <p>Check back soon for amazing deals!</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Categories Section -->
        <?php if (!empty($categories)): ?>
            <section class="categories-section">
                <h2 class="section-title">Shop by Category</h2>

                <div class="categories-grid">
                    <?php foreach ($categories as $category): ?>
                        <a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode($category); ?>" class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <h3 class="category-name"><?php echo htmlspecialchars($category); ?></h3>
                            <span class="category-link-text">
                                Explore <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Features Section -->
        <section class="features-section">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Free Shipping</h3>
                    <p>On orders over $50</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <h3>Easy Returns</h3>
                    <p>30-day return policy</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Secure Payment</h3>
                    <p>100% secure checkout</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Dedicated customer service</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="cta-content">
                <h2>Ready to Start Shopping?</h2>
                <p>Join thousands of satisfied customers and find your perfect products today!</p>
                <div class="cta-buttons">
                    <?php if (Auth::check()): ?>
                        <a href="/Ecommerce_final_project/public/products.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag"></i> Browse Products
                        </a>
                        <a href="/Ecommerce_final_project/public/profile.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-user"></i> My Account
                        </a>
                    <?php else: ?>
                        <a href="/Ecommerce_final_project/public/register.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Create Account
                        </a>
                        <a href="/Ecommerce_final_project/public/login.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>
</main>

<style>
    /* Homepage specific styles */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 2rem;
        border-radius: 1rem;
        text-align: center;
        margin-bottom: 3rem;
    }

    .hero-content h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .featured-section, .categories-section, .features-section, .cta-section {
        margin-bottom: 4rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
    }

    .view-all-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .product-image {
        position: relative;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-out-of-stock {
        background: #dc2626;
        color: white;
    }

    .badge-low-stock {
        background: #f59e0b;
        color: white;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-category {
        font-size: 0.875rem;
        color: #667eea;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .product-category a {
        color: inherit;
        text-decoration: none;
    }

    .product-name {
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .product-name a {
        color: #1f2937;
        text-decoration: none;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .stars {
        color: #fbbf24;
    }

    .rating-text {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        color: #667eea;
    }

    .category-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .category-name {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .feature-icon {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .feature-card p {
        color: #6b7280;
    }

    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 2rem;
        border-radius: 1rem;
        text-align: center;
    }

    .cta-content h2 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .cta-content p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .section-footer {
        text-align: center;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }

    .empty-state i {
        color: #d1d5db;
        margin-bottom: 1rem;
    }
</style>