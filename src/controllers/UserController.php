<?php
// User controller for handling user-related requests
// Responsible for: registration, login, logout, profile management,
// password changes, and account deletion.

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Mailer.php';

class UserController {

    private $userModel;

    private PDO $db;

    // Constructor: accepts PDO DB connection and initializes User model.
    // Also ensures the session is started.
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->userModel = new User($dbConnection);
        Session::start();
    }

    // Show registration form
    // Ensures user is not already logged in, then renders registration page.
    public function showRegisterForm() {
        Auth::requireGuest();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/register.php';
        require_once __DIR__ . '/../views/footer.php';

    }

    // Handle user registration
    // Validates input, checks for duplicate email/username, creates user account,
    // sends welcome email, then redirects to login page.
    public function register() {
        //Only allow POST requests
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             http_response_code(405);
             die("Method not allowed");
         }

         // Extract form data
         $username = $_POST['username'] ?? '';
         $email = $_POST['email'] ?? '';
         $password = $_POST['password'] ?? '';
         $confirmPassword = $_POST['confirm_password'] ?? '';
         $firstName = $_POST['first_name'] ?? '';
         $lastName = $_POST['last_name'] ?? '';

         //Validate all required fields and fornmat
         $validator = new Validator();
         $validator->required('username', $username)
                   ->min('username', $username, 3)
                   ->required('email', $email)
                   ->email('email', $email)
                   ->required('password', $password)
                   ->min('password', $password, 6)
                   ->matches('confirm_password', $confirmPassword, $password);

        // If validation fails, flash errors and redirect back to form
        if ($validator->fails()) {
             $errorMessages = implode(', ', $validator->getErrors());
             Session::flash('errors', $errorMessages);
             Session::flash('old_username', $username);
             Session::flash('old_email', $email);
             header('Location: /Ecommerce_final_project/public/register.php');
             exit();
         }

        // Check if email is already registered
        if ($this->userModel->emailExists($email)) {
             Session::flash('error', 'Email already registered');
             header('Location: /Ecommerce_final_project/public/register.php');
             exit();
         }

        // Check if username is already taken
        if ($this->userModel->usernameExists($username)) {
             Session::flash('error', 'Username already taken');
             header('Location: /Ecommerce_final_project/public/register.php');
             exit();
         }
        // Create the user in the database
        $userId = $this->userModel->create($userData);

        // On success, send welcome email and redirect to login
        if ($userId) {
            Mailer::sendWelcomeEmail($email, $username);
            Session::flash('success', 'Registration successful! Please login.');
            header('Location: /Ecommerce_final_project/public/login.php');
            exit();
        } else {
            // If insertion fails, show error
            Session::flash('error', 'Registration failed. Please try again.');
            header('Location: /Ecommerce_final_project/public/register.php');
            exit();
        }
    }

    // Show login form
    // Ensures user is not already logged in, then renders login page.
    public function showLoginForm() {
        Auth::requireGuest();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Handle user login
    // Validates email/password input, verifies credentials, sets session,
    // and redirects to home page on success.
    public function login() {
        // Extract credentials from form
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate email and password are provided and valid format
        $validator = new Validator();
        $validator->required('email', $email)
            ->email('email', $email)
            ->required('password', $password);

        // If validation fails, show errors and redirect back
        if ($validator->fails()) {
            $errorMessages = implode(', ', $validator->getErrors());
            Session::flash('errors', $errorMessages);
            header('Location: /Ecommerce_final_project/public/login.php');
            exit();
        }

        // Verify email and password against database
        $user = $this->userModel->verifyCredentials($email, $password);

        // If credentials valid, set session and redirect to home
        if ($user) {
            Session::setUser($user['id'], [
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);

            Session::flash('success', 'Welcome back, ' . $user['username'] . '!');
            header('Location: /Ecommerce_final_project/public/index.php');
            exit();
        } else {
            // Invalid credentials: show error
            Session::flash('error', 'Invalid email or password');
            header('Location: /Ecommerce_final_project/public/login.php');
            exit();
        }
    }

    // Handle user logout
    // Destroys the session and redirects to home page.
    public function logout() {
        Session::destroy();
        Session::start();
        Session::flash('info', 'You have been logged out');
        header('Location: /Ecommerce_final_project/public/index.php');
        exit();
    }

    // Show user profile/account page
    // Requires authentication, loads user profile and order history,
    // then renders profile view.
    public function showProfile() {
        Auth::requireAuth();

        // Get current logged-in user data
        $userId = Auth::id();
        $user = $this->userModel->findById($userId);

        // Get user's order history
        require_once __DIR__ . '/../models/Order.php';
        $orderModel = new Order($this->db);
        $orders = $orderModel->getByUserId($userId);

        // Render profile page with user and order data
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/navbar.php';
        require_once __DIR__ . '/../views/profile.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    // Handle profile update
    // Validates input, checks for duplicate email/username, updates user record,
    // refreshes session, then redirects back to profile.
    public function updateProfile() {
        Auth::requireAuth();

        // Only allow POST requests
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            http_response_code(405);
            die("Method not allowed");
        }

        // Get current user id
        $userId = Auth::id();

        // Extract profile fields from form
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';

        // Validate required fields and format
        $validator = new Validator();
        $validator->required('username', $username)
            ->min('username', $username, 3)
            ->required('email', $email)
            ->email('email', $email);

        // If validation fails, flash errors and redirect
        if($validator->fails()){
            $errorMessages = implode(', ', $validator->getErrors());
            Session::flash('errors', $errorMessages);
            header("Location: /Ecommerce_final_project/public/profile.php");
            exit();
        }

        // Check if email is already in use by another user
        if($this->userModel->emailExists($email, $userId)){
            Session::flash('error', 'Email already in use');
            header('Location: /Ecommerce_final_project/public/profile.php');
            exit();
        }

        // Check if username is already in use by another user
        if($this->userModel->usernameExists($username, $userId)){
            Session::flash('error', 'Username already in use');
            header('Location: /Ecommerce_final_project/public/profile.php');
            exit();
        }

        // Prepare updated user data
        $updateData = [
            'username' => $username,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];

        // Update user record in database
        $success = $this->userModel->update($userId, $updateData);

        // If successful, update session data and show success message
        if($success){
            Session::setUser($userId, [
                'username' => $username,
                'email' => $email,
            ]);

            Session::flash('success', 'Profile updated successfully');
        } else {
            // Show error if update fails
            Session::flash('error', 'Update failed');
        }

        header("Location: /Ecommerce_final_project/public/profile.php");
        exit();
    }

    // Handle password change
    // Validates current password, checks new password rules, updates password in DB,
    // then redirects back to profile.
    public function changePassword() {
        Auth::requireAuth();

        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Method not allowed");
        }

        // Get current user id
        $userId = Auth::id();

        // Extract password fields from form
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Validate all password fields and rules
        $validator = new Validator();
        $validator->required('current_password', $current)
            ->required('new_password', $new)
            ->min('new_password', $new, 6)
            ->matches('confirm_password', $confirm, $new);

        // If validation fails, flash errors and redirect
        if ($validator->fails()) {
            $errorMessages = implode(', ', $validator->getErrors());
            Session::flash('errors', $errorMessages);
            header("Location: /Ecommerce_final_project/public/profile.php");
            exit();
        }

        // Fetch user record from database
        $user = $this->userModel->findById($userId);

        // Verify current password matches stored hash
        if (!password_verify($current, $user['password'])) {
            Session::flash('error', 'Current password is incorrect');
            header("Location: /Ecommerce_final_project/public/profile.php");
            exit();
        }

        // Update password in database
        $success = $this->userModel->updatePassword($userId, $new);

        // Show success or error message
        if ($success) {
            Session::flash('success', 'Password updated successfully');
        } else {
            Session::flash('error', 'Password update failed');
        }

        header("Location: /Ecommerce_final_project/public/profile.php");
        exit();
    }

    // Delete user account
    // Requires password verification, deletes user and all related data,
    // destroys session, then redirects to home page.
    public function deleteAccount() {
        Auth::requireAuth();

        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die("Method not allowed");
        }

        // Get current user id
        $userId = Auth::id();

        // Extract password from form for verification
        $password = $_POST['password'] ?? '';

        // Fetch user record
        $user = $this->userModel->findById($userId);

        // Verify password matches before allowing account deletion
        if (!password_verify($password, $user['password'])) {
            Session::flash('error', 'Password is incorrect');
            header('Location: /Ecommerce_final_project/public/profile.php');
            exit();
        }

        // Delete user account from database
        $success = $this->userModel->delete($userId);

        // On success, destroy session and redirect to home
        if ($success) {
            Session::destroy();
            Session::start();
            Session::flash('success', 'Your account has been deleted');
            header('Location: /Ecommerce_final_project/public/index.php');
            exit();
        }

        // Show error if deletion fails
        Session::flash('error', 'Failed to delete account');
        header('Location: /Ecommerce_final_project/public/profile.php');
        exit();
    }
}