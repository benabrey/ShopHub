<?php
//Handles all database operations related to product reviews

class Review {
    /** @var PDO Database connection instance */
    private $db;

    /**
     * Constructor - Initialize model with database connection
     *
     * @param PDO $db Active PDO database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Create a new product review
     *
     * @param int $productId ID of the product being reviewed
     * @param int $userId ID of the user submitting the review
     * @param int $rating Rating from 1-5 stars
     * @param string $reviewText Review content/comment
     * @return bool True if review was created successfully
     */
    public function create($productId, $userId, $rating, $reviewText) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$productId, $userId, $rating, $reviewText]);
    }

    /**
     * Get all reviews for a specific product
     * Includes reviewer's username and email from users table
     * Returns reviews in reverse chronological order (newest first)
     *
     * @param int $productId Product ID to get reviews for
     * @return array Array of review objects with user information
     */
    public function getByProduct($productId) {
        $sql = "SELECT r.*, u.username, u.email 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calculate average rating and total review count for a product
     * Used for displaying star ratings on product pages
     *
     * @param int $productId Product ID to calculate rating for
     * @return array Contains 'avg_rating' (float) and 'review_count' (int)
     */
    public function getAverageRating($productId) {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
                FROM reviews 
                WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a user has already reviewed a specific product
     * Prevents users from submitting multiple reviews for the same product
     *
     * @param int $productId Product ID to check
     * @param int $userId User ID to check
     * @return bool True if user has already reviewed this product
     */
    public function hasUserReviewed($productId, $userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM reviews 
                WHERE product_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
