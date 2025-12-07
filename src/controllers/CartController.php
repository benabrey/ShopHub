<?php
// Cart controller for handling shopping cart operations
// Responsible for: viewing cart, adding/updating/removing items, checkout processing,
// order creation, and sending confirmation emails.
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Mailer.php';

class CartController {

    private $db;
    private $productModel;
    private $orderModel;

    // Constructor: accepts a PDO DB connection and initializes models.
    // Also ensures the session is started so cart data is available.
    public function __construct($dbConnection) {
         $this->db = $dbConnection;

         $this->productModel = new Product($dbConnection);
         $this->orderModel = new Order($dbConnection);

         Session::start();
    }

    // Show cart page
    // Reads cart from session, loads product details, computes subtotals and total,
    // then renders the header, navbar, cart view and footer
    public function index() {
         $cartItems = Session::get('cart', []);

         $cart = [];
         $total = 0;
         foreach ($cartItems as $productId => $quantity) {
             $product = $this->productModel->findById($productId);
             if ($product) {
                 $cart[] = [
                     'product' => $product,
                     'quantity' => $quantity,
                     'subtotal' => $product['price'] * $quantity
                 ];
                 $total += $product['price'] * $quantity;
             }
         }

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/cart.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    //Add item to cart
    //Validates input, checks stock, updates cart stored in session and redirects
    public function addToCart() {
        Auth::requireAuth();

         $productId = $_POST['product_id'] ?? null;
         $quantity = $_POST['quantity'] ?? 1;

         $validator = new Validator();
         $validator->required('product_id', $productId)
                   ->numeric('product_id', $productId)
                   ->required('quantity', $quantity)
                   ->numeric('quantity', $quantity)
                   ->between('quantity', $quantity, 1, 100);

         $product = $this->productModel->findById($productId);
         if (!$product) {
             Session::flash('error', 'Product not found');
             header('Location: /Ecommerce_final_project/public/product.php');
             exit();
         }

         //Ensure sufficient stock before adding
         if (!$this->productModel->hasStock($productId, $quantity)) {
             Session::flash('error', 'Insufficient stock');
             header('Location: /Ecommerce_final_project/public/product.php?id=' . $productId);
             exit();
         }

         $cart = Session::get('cart', []);

         if (isset($cart[$productId])) {
             $cart[$productId] += $quantity;
         } else {
             $cart[$productId] = $quantity;
         }

         Session::set('cart', $cart);

         Session::flash('success', 'Product added to cart');
         header('Location: /Ecommerce_final_project/public/cart.php');
         exit();
    }

    //Update cart item quantity
    // Validates input and updates/removes item in session cart
    public function updateQuantity() {
        Auth::requireAuth();
        if($_SERVER['REQUEST_METHOD'] !=='POST'){
            header('Location: /Ecommerce_final_project/public/cart.php');
            exit();
        }
         $productId = $_POST['product_id'] ?? null;
         $quantity = $_POST['quantity'] ?? 0;

        $validator = new Validator();
        $validator->required('product_id', $productId)
                  ->numeric('product_id', $productId)
                  ->required('quantity', $quantity)
                  ->numeric('quantity', $quantity);
        if($validator->fails()){
            Session::set('errors', $validator->getErrors());
            header('Location: /Ecommerce_final_project/public/cart.php');
            exit();
        }

        $cart = Session::get('cart', []);

         if ($quantity <= 0) {
             // Remove the item when quantity is 0
             unset($cart[$productId]);
         } else {
             // Ensure stock is available for requested quantity
             if ($this->productModel->hasStock($productId, $quantity)) {
                 $cart[$productId] = $quantity;
             } else {
                 Session::flash('error', 'Insufficient stock');
                 header('Location: /Ecommerce_final_project/public/cart.php');
                 exit();
             }
         }

        Session::set('cart', $cart);

         header('Location: /Ecommerce_final_project/public/cart.php');
         exit();
    }

    //Remove Item from cart
    // Accepts product_id via POST (or GET id) and removes from session cart
    public function removeItem() {
        Auth::requireAuth();
        $productId = $_POST['product_id'] ?? $_GET['id'] ?? null;

        if(!$productId || !is_numeric($productId) ){
            Session::flash('error', 'Invalid product ID');
            header('Location: /Ecommerce_final_project/public/cart.php');
            exit();
        }

        $cart = Session::get('cart', []);

        unset($cart[$productId]);

        Session::set('cart', $cart);

         Session::flash('success', 'Item removed from cart');
         header('Location: /Ecommerce_final_project/public/cart.php');
         exit();
    }

    // Clear entire cart
    //Removes the cart from session and redirects.
    public function clearCart() {
        Auth::requireAuth();

        Session::remove('cart');

         Session::flash('success', 'Cart cleared');
         header('Location: /Ecommerce_final_project/public/cart.php');
         exit();
    }

    // Get cart count(for navbar badge)
    // Returns JSON with the total item count in cart.
    public function getCartCount() {
        $cart = Session::get('cart', []);
        $count = array_sum($cart);

         header('Content-Type: application/json');
         echo json_encode(['count' => $count]);
         exit();
    }

    //Calculate Cart total
    // Helper that sums line totals
    private function calculateCartTotal() {
        $cart = Session::get('cart', []);

         $total = 0;
         foreach ($cart as $productId => $quantity) {
             $product = $this->productModel->findById($productId);
             if ($product) {
                 $total += $product['price'] * $quantity;
             }
         }
         return $total;
    }

    // SHow checkout page
    //Ensures user is authenticated, cart is not empty, prepares cart & user data,
    //then  renders checkout view
    public function showCheckout() {
         Auth::requireAuth();
         $cart = Session::get('cart', []);

         if (empty($cart)) {
             Session::flash('error', 'Your cart is empty');
             header('Location: /Ecommerce_final_project/public/cart.php');
             exit();
         }

         $cartItems = [];
         $total = 0;
         foreach ($cart as $productId => $quantity) {
             $product = $this->productModel->findById($productId);
             if ($product) {
                 $cartItems[] = [
                     'product' => $product,
                     'quantity' => $quantity,
                     'subtotal' => $product['price'] * $quantity
                 ];
                 $total += $product['price'] * $quantity;
             }
         }

         $userId = Auth::id();
         $userModel = new User($this->db);
         $user = $userModel->findById($userId);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/checkout.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Process checkout and create order
    // Validates shipping/payment data, ensures stock, creates order and order items,
    // decrements stock, sends confirmation email, clears cart and redirects to confirmation.
    public function processCheckout() {
         Auth::requireAuth();

         if($_SERVER['REQUEST_METHOD'] !== 'POST'){
             header('Location: /Ecommerce_final_project/public/checkout.php');
             exit();
         }
         $cart = Session::get('cart', []);

         if (empty($cart)) {
             Session::flash('error', 'Your cart is empty');
             header('Location: /Ecommerce_final_project/public/cart.php');
             exit();
         }

         $shippingAddress = $_POST['shipping_address'] ?? '';
         $shippingCity = $_POST['shipping_city'] ?? '';
         $shippingPostalCode = $_POST['shipping_postal_code'] ?? '';
         $shippingCountry = $_POST['shipping_country'] ?? '';

         $validator = new Validator();
         $validator->required('shipping_address', $shippingAddress)
                   ->required('shipping_city', $shippingCity)
                   ->required('shipping_postal_code', $shippingPostalCode)
                   ->required('shipping_country', $shippingCountry);

         if($validator->fails()){
             Session::set('errors', $validator->getErrors());
             header('Location: /Ecommerce_final_project/public/checkout.php');
             exit();
         }

        // Test Credit Card Info:
        $paymentMethod = $_POST['payment_method'] ?? '';
        $cardNumber = $_POST['card_number'] ?? '';

        // Basic test-card enforcement for local/testing environment
        if($paymentMethod === 'test_card'){
            if($cardNumber !== '4242424242424242'&& str_replace(' ', '', $cardNumber) !== '4242424242424242') {
                Session::flash('error', 'Please use test card: 4242 4242 4242 4242');
                header('Location: /Ecommerce_final_project/public/checkout.php');
                exit();
            }
        }
         $total = $this->calculateCartTotal();

        // Re-check stock for each item before creating an order
        foreach ($cart as $productId => $quantity) {
             if (!$this->productModel->hasStock($productId, $quantity)) {
                 Session::flash('error', 'Some items are out of stock');
                 header('Location: /Ecommerce_final_project/public/cart.php');
                 exit();
             }
         }

        // Prepare order data and create order record
        $orderData = [
             'user_id' => Auth::id(),
             'total_amount' => $total,
             'status' => 'pending',
             'shipping_address' => $shippingAddress,
             'shipping_city' => $shippingCity,
             'shipping_postal_code' => $shippingPostalCode,
             'shipping_country' => $shippingCountry
         ];

         $orderId = $this->orderModel->create($orderData);

         if (!$orderId) {
             Session::flash('error', 'Order creation failed');
             header('Location: /Ecommerce_final_project/public/checkout.php');
             exit();
         }

        // Build order items, persist them and decrement product stock
        $orderItems = [];
         foreach ($cart as $productId => $quantity) {
             $product = $this->productModel->findById($productId);
             $orderItems[] = [
                 'product_id' => $productId,
                 'quantity' => $quantity,
                 'price_at_purchase' => $product['price']
             ];

             $this->productModel->decreaseStock($productId, $quantity);
         }
         $this->orderModel->addItems($orderId, $orderItems);

        // Send confirmation email to user
        $user = Auth::user();
         Mailer::sendOrderConfirmation($user['email'], $orderId, $total);

        // Clear cart and redirect to confirmation page
        Session::remove('cart');

         Session::flash('success', 'Order placed successfully! Order ID: ' . $orderId);
         header('Location: /Ecommerce_final_project/public/order-confirmation.php?id=' . $orderId);
         exit();
    }

    // Show order confirmation page
    // Validates ownership, loads order and item details (including product info),
    //then renders order-confirmation view.
    public function showOrderConfirmation($orderId){
        Auth::requireAuth();
        $order = $this->orderModel->findById($orderId);
        if(!$order || $order['user_id'] !== Auth::id()){
            header('Location: /Ecommerce_final_project/public/index.php');
            exit();
        }

        $orderItems = $this->orderModel->getItems($orderId);

        // Add product details to each order item
        foreach ($orderItems as &$item) {
            $product = $this->productModel->findById($item['product_id']);
            $item['product'] = $product;
        }

        $total = $order['total_amount'];

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/order-confirmation.php';
        require_once __DIR__ . '/../views/footer.php';
    }
}
