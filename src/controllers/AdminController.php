<?php
// Controller that provides admin-only actions for managing products and viewing basic site stats.

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/AdminAuth.php';
require_once __DIR__ . '/../helpers/Session.php';

class AdminController {
    private $db;
    private $productModel;

    // Inject a PDO database connection and initialize the Product model.
    public function __construct($db) {
        $this->db = $db;
        $this->productModel = new Product($db);
    }

    // Dashboard: requires admin, loads product list and site statistics, then renders admin views.
    public function dashboard() {
        AdminAuth::requireAdmin();

        //retrieve products and counts for display
        $products = $this->productModel->getAll();
        $totalProducts = $this->productModel->count();

        // Get some stats
        $stmt = $this->db->query("SELECT COUNT(*) as total_users FROM users");
        $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

        $stmt = $this->db->query("SELECT COUNT(*) as total_orders FROM orders");
        $totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

        $stmt = $this->db->query("SELECT SUM(total_amount) as total_revenue FROM orders");
        $totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

        //Render the admin dashboard layout/ content
        require_once __DIR__ . '/../views/admin/header.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/admin/footer.php';
    }

    // List products: admin-only, fetches products and renders the products list view.
    public function listProducts() {
        AdminAuth::requireAdmin();

        $products = $this->productModel->getAll();

        require_once __DIR__ . '/../views/admin/header.php';
        require_once __DIR__ . '/../views/admin/products_list.php';
        require_once __DIR__ . '/../views/admin/footer.php';
    }

    // Create product: shows form on GET, validates and inserts product on POST.
    public function createProduct() {
        AdminAuth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Collect input values
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock_quantity'] ?? 0;
            $category = $_POST['category'] ?? '';
            $brand = $_POST['brand'] ?? '';
            $imageUrl = $_POST['image_url'] ?? '';

            //Required field validation
            if (empty($name) || empty($price) || empty($category)) {
                Session::flash('error', 'Please fill in all required fields');
            } else {
                //Insert new product into db
                $sql = "INSERT INTO products (name, description, price, stock_quantity, category, brand, image_url) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);

                if ($stmt->execute([$name, $description, $price, $stock, $category, $brand, $imageUrl])) {
                    Session::flash('success', 'Product created successfully');
                    //Redirect back to admin products page after success
                    header('Location: /Ecommerce_final_project/public/admin/products.php');
                    exit();
                } else {
                    Session::flash('error', 'Failed to create product');
                }
            }
        }

        //Rendering product creation form
        require_once __DIR__ . '/../views/admin/header.php';
        require_once __DIR__ . '/../views/admin/product_form.php';
        require_once __DIR__ . '/../views/admin/footer.php';
    }

    //Loads existing product, shows form, updates DB on post
    public function editProduct($id) {
        AdminAuth::requireAdmin();

        $product = $this->productModel->findById($id);

        // If product not found, notify and redirect
        if (!$product) {
            Session::flash('error', 'Product not found');
            header('Location: /Ecommerce_final_project/public/admin/products.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect updated values from the form
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock_quantity'] ?? 0;
            $category = $_POST['category'] ?? '';
            $brand = $_POST['brand'] ?? '';
            $imageUrl = $_POST['image_url'] ?? '';

            //Validate required fields
            if (empty($name) || empty($price) || empty($category)) {
                Session::flash('error', 'Please fill in all required fields');
            } else {
                //Update product record
                $sql = "UPDATE products 
                        SET name = ?, description = ?, price = ?, stock_quantity = ?, 
                            category = ?, brand = ?, image_url = ?
                        WHERE id = ?";
                $stmt = $this->db->prepare($sql);

                if ($stmt->execute([$name, $description, $price, $stock, $category, $brand, $imageUrl, $id])) {
                    Session::flash('success', 'Product updated successfully');
                    header('Location: /Ecommerce_final_project/public/admin/products.php');
                    exit();
                } else {
                    Session::flash('error', 'Failed to update product');
                }
            }
        }
        // Render the product edit form (reuses product_form.php)
        require_once __DIR__ . '/../views/admin/header.php';
        require_once __DIR__ . '/../views/admin/product_form.php';
        require_once __DIR__ . '/../views/admin/footer.php';
    }

    //Admin only, deletes on POST then redirects
    public function deleteProduct($id) {
        AdminAuth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            if ($stmt->execute([$id])) {
                Session::flash('success', 'Product deleted successfully');
            } else {
                Session::flash('error', 'Failed to delete product');
            }
        }
        // Always redirect back to products list after attempting delete
        header('Location: /Ecommerce_final_project/public/admin/products.php');
        exit();
    }
}