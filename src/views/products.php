<!-- Products Listing Page -->
<main class="products-page">
    <div class="container">
        <div class="products-layout">

            <!-- Sidebar with Categories -->
            <aside class="products-sidebar">
                <h3>Categories</h3>
                <ul class="category-list">
                    <li><a href="/Ecommerce_final_project/public/products.php" class="<?php echo !isset($category) || empty($category) ? 'active' : ''; ?>">All Products</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Electronics'); ?>" class="<?php echo isset($category) && $category === 'Electronics' ? 'active' : ''; ?>">Electronics</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Home & Kitchen'); ?>" class="<?php echo isset($category) && $category === 'Home & Kitchen' ? 'active' : ''; ?>">Home & Kitchen</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Sports & Outdoors'); ?>" class="<?php echo isset($category) && $category === 'Sports & Outdoors' ? 'active' : ''; ?>">Sports & Outdoors</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Shoes'); ?>" class="<?php echo isset($category) && $category === 'Shoes' ? 'active' : ''; ?>">Shoes</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Bags'); ?>" class="<?php echo isset($category) && $category === 'Bags' ? 'active' : ''; ?>">Bags</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Health & Personal Care'); ?>" class="<?php echo isset($category) && $category === 'Health & Personal Care' ? 'active' : ''; ?>">Health & Personal Care</a></li>
                    <li><a href="/Ecommerce_final_project/public/products.php?category=<?php echo urlencode('Furniture'); ?>" class="<?php echo isset($category) && $category === 'Furniture' ? 'active' : ''; ?>">Furniture</a></li>
                </ul>
            </aside>

            <!-- Main Products Area -->
            <div class="products-main">
                <div class="page-header">
                    <h1>All Products</h1>
                    <?php if (isset($search) && $search): ?>
                        <p class="search-results">Search results for: "<?php echo htmlspecialchars($search); ?>"</p>
                    <?php endif; ?>
                    <?php if (isset($category) && $category): ?>
                        <p class="category-filter">Category: <?php echo htmlspecialchars($category); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (isset($products) && !empty($products)): ?>
                    <div class="products-grid">
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $product['id']; ?>" class="product-image-link">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         class="product-image"
                                         onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                                </a>

                                <div class="product-info">
                                    <h3 class="product-name">
                                        <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $product['id']; ?>">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h3>
                                    <p class="product-category">
                                        <?php echo htmlspecialchars($product['category']); ?>
                                    </p>

                                    <!-- Product Rating -->
                                    <div class="product-rating" style="margin: 0.5rem 0; font-size: 0.875rem;">
                                        <?php if ($product['review_count'] > 0): ?>
                                            <span style="color: #fbbf24;">
                                    <?php
                                    $avgRating = $product['avg_rating'];
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $fullStars) {
                                            echo '⭐';
                                        } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                            echo '⭐';
                                        } else {
                                            echo '☆';
                                        }
                                    }
                                    ?>
                                </span>
                                            <span style="color: #6b7280; margin-left: 0.25rem;">
                                                (<?php echo number_format($avgRating, 1); ?>) · <?php echo $product['review_count']; ?> <?php echo $product['review_count'] == 1 ? 'review' : 'reviews'; ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #9ca3af;">No reviews yet</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="product-price">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </div>

                                    <form action="/Ecommerce_final_project/public/cart.php" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?><?php echo isset($category) && $category ? '&category=' . urlencode($category) : ''; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
                                    ← Previous
                                </a>
                            <?php endif; ?>

                            <div class="pagination-numbers">
                                <?php
                                $start = max(1, $page - 2);
                                $end = min($totalPages, $page + 2);

                                if ($start > 1): ?>
                                    <a href="?page=1<?php echo isset($category) && $category ? '&category=' . urlencode($category) : ''; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-number">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <a href="?page=<?php echo $i; ?><?php echo isset($category) && $category ? '&category=' . urlencode($category) : ''; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>"
                                       class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($end < $totalPages): ?>
                                    <?php if ($end < $totalPages - 1): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                    <a href="?page=<?php echo $totalPages; ?><?php echo isset($category) && $category ? '&category=' . urlencode($category) : ''; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-number"><?php echo $totalPages; ?></a>
                                <?php endif; ?>
                            </div>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo isset($category) && $category ? '&category=' . urlencode($category) : ''; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>" class="pagination-btn">
                                    Next →
                                </a>
                            <?php endif; ?>
                        </div>

                        <p class="pagination-info">
                            Showing page <?php echo $page; ?> of <?php echo $totalPages; ?>
                        </p>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="no-products">
                        <p>No products found.</p>
                        <a href="/Ecommerce_final_project/public/products.php" class="btn btn-primary">View All Products</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>