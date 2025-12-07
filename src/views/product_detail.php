<!-- Product Detail Page -->
<?php
$product = $product ?? null;
$relatedProducts = $relatedProducts ?? [];
?>

<main class="product-detail-page">
    <div class="container">

        <nav class="breadcrumb">
            <a href="/Ecommerce_final_project/public/index.php">Home</a>
            <span>/</span>
            <a href="/Ecommerce_final_project/public/product.php">Products</a>
            <span>/</span>
            <a href="/Ecommerce_final_project/public/product.php?category=<?php echo urlencode($product['category']); ?>">
                <?php echo htmlspecialchars($product['category']); ?>
            </a>
            <span>/</span>
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="product-detail-content">
            <div class="product-images">
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         id="main-product-image"
                         onerror="this.src='/assets/images/placeholder.jpg'">
                </div>

                <div class="image-thumbnails">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                         alt="Thumbnail"
                         class="thumbnail active"
                         onclick="changeMainImage(this.src)">
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="product-rating">
                    <?php if (!empty($reviews)): ?>
                        <div class="stars">
                            <?php
                            $avgRating = $ratingData['avg_rating'];
                            $fullStars = floor($avgRating);
                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $fullStars) {
                                    echo '<i class="fa fa-star"></i>';
                                } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                    echo '<i class="fa fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-count">
            (<?php echo number_format($ratingData['avg_rating'], 1); ?> out of 5 - <?php echo $ratingData['review_count']; ?> <?php echo $ratingData['review_count'] == 1 ? 'review' : 'reviews'; ?>)
        </span>
                    <?php else: ?>
                        <div class="stars">
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="rating-count">No reviews yet - Be the first to review!</span>
                    <?php endif; ?>
                </div>

                <div class="product-price">
                    <span class="price-amount">$<?php echo number_format($product['price'], 2); ?></span>
                </div>

                <div class="stock-info">
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <span class="stock-available">
                            <i class="fa fa-check-circle"></i>
                            In Stock (<?php echo $product['stock_quantity']; ?> available)
                        </span>
                    <?php else: ?>
                        <span class="stock-unavailable">
                            <i class="fa fa-times-circle"></i>
                            Out of Stock
                        </span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-specs">
                    <h3>Specifications</h3>
                    <ul>
                        <li><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></li>
                        <li><strong>SKU:</strong> <?php echo htmlspecialchars($product['id']); ?></li>
                    </ul>
                </div>

                <?php if ($product['stock_quantity'] > 0): ?>
                    <form action="/Ecommerce_final_project/public/cart.php" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                        <div class="quantity-selector">
                            <label for="quantity">Quantity:</label>
                            <div class="quantity-control">
                                <button type="button" class="qty-btn qty-minus">-</button>
                                <input type="number"
                                       id="quantity"
                                       name="quantity"
                                       value="1"
                                       min="1"
                                       max="<?php echo $product['stock_quantity']; ?>"
                                       class="qty-input">
                                <button type="button" class="qty-btn qty-plus">+</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-add-to-cart">
                            <i class="fa fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>
                        Out of Stock
                    </button>
                    <button class="btn btn-outline">
                        <i class="fa fa-bell"></i> Notify Me When Available
                    </button>
                <?php endif; ?>

                <div class="product-actions">
                    <button class="btn-wishlist" title="Add to Wishlist">
                        <i class="fa fa-heart"></i> Add to Wishlist
                    </button>
                    <button class="btn-share" title="Share">
                        <i class="fa fa-share-alt"></i> Share
                    </button>
                </div>
            </div>
        </div>

        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="reviews">Reviews</button>
                <button class="tab-btn" data-tab="shipping">Shipping Info</button>
                <button class="tab-btn" data-tab="returns">Returns</button>
            </div>

            <div class="tabs-content">
                <div class="tab-pane active" id="reviews">
                    <h3>Customer Reviews</h3>

                    <?php if (!empty($reviews)): ?>
                        <div style="margin-bottom: 2rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem;">
                            <p style="margin: 0;"><strong>Average Rating:</strong>
                                <span style="color: #fbbf24; font-size: 1.25rem;">
                    <?php
                    $avgRating = $ratingData['avg_rating'];
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= round($avgRating) ? '⭐' : '☆';
                    }
                    ?>
                </span>
                                <?php echo number_format($ratingData['avg_rating'], 1); ?> / 5
                                (<?php echo $ratingData['review_count']; ?> <?php echo $ratingData['review_count'] == 1 ? 'review' : 'reviews'; ?>)
                            </p>
                        </div>

                        <?php foreach ($reviews as $review): ?>
                            <div style="border-bottom: 1px solid #e5e7eb; padding: 1.5rem 0;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <strong style="font-size: 1.1rem;"><?php echo htmlspecialchars($review['username']); ?></strong>
                                    <span style="color: #fbbf24; font-size: 1.25rem;">
                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>⭐<?php endfor; ?>
                    </span>
                                </div>
                                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                    <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                </p>
                                <p style="margin: 0; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #6b7280; margin: 1rem 0;">No reviews yet. Be the first to review this product!</p>
                    <?php endif; ?>

                    <?php if (Auth::check()): ?>
                        <?php
                         $hasReviewed = $reviewModel->hasUserReviewed($product['id'], Auth::id());
                        ?>

                        <?php if (!$hasReviewed): ?>
                            <button class="btn btn-outline" style="margin-top: 1.5rem;">Write a Review</button>

                            <!-- Review Form (hidden by default) -->
                            <div id="reviewForm" style="display: none; margin-top: 2rem; padding: 2rem; background: #f9fafb; border-radius: 0.5rem;">
                                <h4>Write Your Review</h4>
                                <form action="/Ecommerce_final_project/public/product.php?id=<?php echo $product['id']; ?>" method="POST">
                                    <input type="hidden" name="action" value="add_review">

                                    <div class="form-group">
                                        <label for="rating">Rating *</label>
                                        <select name="rating" id="rating" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                                            <option value="">Select rating</option>
                                            <option value="5">⭐⭐⭐⭐⭐ (5 stars)</option>
                                            <option value="4">⭐⭐⭐⭐ (4 stars)</option>
                                            <option value="3">⭐⭐⭐ (3 stars)</option>
                                            <option value="2">⭐⭐ (2 stars)</option>
                                            <option value="1">⭐ (1 star)</option>
                                        </select>
                                    </div>

                                    <div class="form-group" style="margin-top: 1rem;">
                                        <label for="review_text">Your Review *</label>
                                        <textarea name="review_text" id="review_text" rows="5" required placeholder="Share your experience with this product..." style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></textarea>
                                    </div>

                                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                        <button type="button" class="btn btn-outline">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <p style="margin-top: 1.5rem; padding: 1rem; background: #e0f2fe; border-radius: 0.375rem; color: #0369a1;">
                                <i class="fas fa-check-circle"></i> You have already reviewed this product.
                            </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p style="margin-top: 1.5rem; padding: 1rem; background: #fef3c7; border-radius: 0.375rem; color: #92400e;">
                            <i class="fas fa-info-circle"></i> Please <a href="/Ecommerce_final_project/public/login.php" style="color: #92400e; text-decoration: underline;">login</a> to write a review.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" id="shipping">
                    <h3>Shipping Information</h3>
                    <p>Free shipping on orders over $50. Standard shipping takes 3-5 business days.</p>
                </div>

                <div class="tab-pane" id="returns">
                    <h3>Return Policy</h3>
                    <p>We offer a 30-day return policy. Items must be in original condition.</p>
                </div>
            </div>
        </div>

        <?php if (!empty($relatedProducts)): ?>
            <section class="related-products">
                <h2 class="section-title">Related Products</h2>
                <div class="product-grid">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <?php if ($relatedProduct['id'] != $product['id']):  ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $relatedProduct['id']; ?>">
                                        <img src="<?php echo htmlspecialchars($relatedProduct['image_url']); ?>"
                                             alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>"
                                             onerror="this.src='/assets/images/placeholder.jpg'">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h3>
                                        <a href="/Ecommerce_final_project/public/product.php?id=<?php echo $relatedProduct['id']; ?>">
                                            <?php echo htmlspecialchars($relatedProduct['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="product-price">
                                        $<?php echo number_format($relatedProduct['price'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyMinus = document.querySelector('.qty-minus');
        const qtyPlus = document.querySelector('.qty-plus');
        const qtyInput = document.querySelector('.qty-input');

        if (qtyMinus && qtyPlus && qtyInput) {
            qtyMinus.addEventListener('click', function() {
                if (qtyInput.value > 1) {
                    qtyInput.value = parseInt(qtyInput.value) - 1;
                }
            });

            qtyPlus.addEventListener('click', function() {
                const max = parseInt(qtyInput.getAttribute('max'));
                if (qtyInput.value < max) {
                    qtyInput.value = parseInt(qtyInput.value) + 1;
                }
            });
        }

        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });

        // Write a Review button toggle
        const writeReviewBtns = document.querySelectorAll('.btn-outline');
        writeReviewBtns.forEach(btn => {
            if (btn.textContent.trim() === 'Write a Review') {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = document.getElementById('reviewForm');
                    if (form.style.display === 'none' || form.style.display === '') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                });
            }

            // Cancel button
            if (btn.textContent.trim() === 'Cancel' && btn.closest('#reviewForm')) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('reviewForm').style.display = 'none';
                });
            }
        });
    });

    function changeMainImage(src) {
        document.getElementById('main-product-image').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        event.target.classList.add('active');
    }
</script>
