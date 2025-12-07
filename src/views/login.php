<?php
$pageTitle = "Login - ShopHub";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
?>

<main class="login-page">
    <div class="container">
        <div class="auth-form-container">
            <h1>Login to Your Account</h1>

            <form action="/Ecommerce_final_project/public/login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Enter your password">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember_me">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Login
                </button>
            </form>

            <div class="auth-links">
                <p>Don't have an account? <a href="/Ecommerce_final_project/public/register.php">Register here</a></p>
                <p><a href="#">Forgot your password?</a></p>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>






