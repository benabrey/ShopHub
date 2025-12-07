<?php
// Product controller for handling product-related requests
// Responsible for: listing products, displaying product details, handling reviews,
// searching, and filtering by category.

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Session.php';

class ProductController {

    private $db;
    private $productModel;

    // Constructor: accepts PDO DB connection and initializes Product model.
    // Also ensures the session is started.
    public function __construct($dbConnection) {
        $this->db =$dbConnection;
        $this->productModel = new Product($dbConnection);
        Session::start();
    }

    // Show all products (product listing page)
    // Supports search by keyword, filtering by category, and pagination.
    // Renders header, navbar, products list view and footer.
    public function index() {
        // Get query parameters for search, category filter, and pagination
        $search = $_GET['search'] ?? null;
        $category = $_GET['category'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Determine which products to fetch based on active filters
        if ($search) {
            $products = $this->productModel->search($search, $perPage, $offset);
            $totalProducts = $this->productModel->countSearch($search);
        } elseif ($category) {
            $products = $this->productModel->getByCategory($category, $perPage, $offset);
            $totalProducts = $this->productModel->countByCategory($category);
        } else {
            // No filters: get all products with ratings
            $products = $this->productModel->getWithRatings($perPage, $offset);
            $totalProducts = $this->productModel->count();
        }

        // Calculate total pages for pagination
        $totalPages = ceil($totalProducts / $perPage);

        // Render page with products, pagination info, and applied filters
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/products.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Show single product details
    // Displays product info, reviews, rating, and related products.
    // Handles review submission via POST.
    public function show($id) {
        // Validate product id
        if (!$id || !is_numeric($id)) {
            Session::flash('error', 'Invalid product');
            header('Location: /Ecommerce_final_project/public/products.php');
            exit();
        }

        // Handle review submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
            if (!Auth::check()) {
                Session::flash('error', 'Please login to write a review');
                header('Location: /Ecommerce_final_project/public/login.php');
                exit();
            }

            require_once __DIR__ . '/../models/Review.php';
            $reviewModel = new Review($this->db);

            // Check if user already reviewed this product
            if ($reviewModel->hasUserReviewed($id, Auth::id())) {
                Session::flash('error', 'You have already reviewed this product');
                header('Location: /Ecommerce_final_project/public/product.php?id=' . $id);
                exit();
            }

            // Extract and validate review data from form
            $rating = $_POST['rating'] ?? 0;
            $reviewText = $_POST['review_text'] ?? '';

            // Validate rating is 1-5 and review text is not empty
            if ($rating >= 1 && $rating <= 5 && !empty($reviewText)) {
                // Create the review record in the database
                $reviewModel->create($id, Auth::id(), $rating, $reviewText);
                Session::flash('success', 'Review submitted successfully!');
            } else {
                Session::flash('error', 'Invalid review data');
            }

            header('Location: /Ecommerce_final_project/public/product.php?id=' . $id);
            exit();
        }

        // Fetch the product by id
        $product = $this->productModel->findById($id);

        // If product not found, notify and redirect
        if (!$product) {
            Session::flash('error', 'Product not found');
            header('Location: /Ecommerce_final_project/public/products.php');
            exit();
        }

        // Get reviews and average rating data
        require_once __DIR__ . '/../models/Review.php';
        $reviewModel = new Review($this->db);
        $reviews = $reviewModel->getByProduct($id);
        $ratingData = $reviewModel->getAverageRating($id);

        //Get related products from the same category (up to 4)
        $relatedProducts = $this->productModel->getByCategory($product['category'], 4);
        $pageTitle = $product['name'] . " - ShopHub";

        // Render product detail page with all data
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/product_detail.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Search products (AJAX endpoint)
    // Accepts a search term via GET and returns matching products as JSON.
    // Used by autocomplete or live search features.
    public function search() {
        //Get the search query from the request
        $searchTerm = $_GET['q'] ?? '';

        //Return empty result if search term is empty
        if (trim($searchTerm) === '') {
            echo json_encode([]);
            exit();
        }

        // Search for products matching the term (limit to 10 results)
        $products = $this->productModel->search($searchTerm, 10);

        //Return results as JSON
        header('Content-Type: application/json');
        echo json_encode($products);
        exit();
    }

    // Filter products by category
    // Supports pagination and renders the filtered product listing.
    public function filterByCategory($category) {
        //Get pagination parameters
        $page = $_GET['page'] ?? 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Fetch products in the specified category with pagination
        $products   = $this->productModel->getByCategory($category, $perPage, $offset);
        $categories = $this->productModel->getAllCategories();

        // Render filtered product listing
        require_once __DIR__ . '/../views/products.php';
    }
}
