<?php
$pageTitle = "Register - ShopHub";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
?>

    <main class="register-page">
        <div class="container">
            <div class="auth-form-container">
                <h1>Create Your Account</h1>

                <form action="/Ecommerce_final_project/public/register.php" method="POST" class="auth-form" data-validate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text"
                                   id="first_name"
                                   name="first_name"
                                   placeholder="John"
                                   value="<?php echo htmlspecialchars(Session::getFlash('old_first_name') ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text"
                                   id="last_name"
                                   name="last_name"
                                   placeholder="Doe"
                                   value="<?php echo htmlspecialchars(Session::getFlash('old_last_name') ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text"
                               id="username"
                               name="username"
                               required
                               placeholder="johndoe"
                               value="<?php echo htmlspecialchars(Session::getFlash('old_username') ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               required
                               placeholder="your@email.com"
                               value="<?php echo htmlspecialchars(Session::getFlash('old_email') ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               minlength="6"
                               placeholder="At least 6 characters">
                        <small>Password must be at least 6 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password"
                               id="confirm_password"
                               name="confirm_password"
                               required
                               placeholder="Re-enter your password">
                    </div>

                    <div class="form-group">
                        <label for="address">Address *</label>
                        <textarea id="address"
                                  name="address"
                                  required
                                  rows="3"
                                  placeholder="123 Main Street, City, Province"><?php echo htmlspecialchars(Session::getFlash('old_address') ?? ''); ?></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="agree_terms" required>
                            I agree to the <a href="/Ecommerce_final_project/public/terms.php" target="_blank">Terms and Conditions</a> *
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Create Account
                    </button>
                </form>

                <div class="auth-links">
                    <p>Already have an account? <a href="/Ecommerce_final_project/public/login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/footer.php'; ?>