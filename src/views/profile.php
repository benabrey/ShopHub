<?php
/** @var array $user */
/** @var array $orders */

$pageTitle = "My Account - ShopHub";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
?>

<main class="profile-page">
    <div class="container">
        <h1 class="page-title">My Account</h1>

        <div class="profile-content">
            <!-- Profile Information -->
            <section class="profile-section">
                <h2>Profile Information</h2>

                <form action="/Ecommerce_final_project/public/profile.php" method="POST" class="profile-form">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text"
                                   id="first_name"
                                   name="first_name"
                                   value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text"
                                   id="last_name"
                                   name="last_name"
                                   value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text"
                               id="username"
                               name="username"
                               value="<?php echo htmlspecialchars($user['username']); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="<?php echo htmlspecialchars($user['email']); ?>"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </section>

            <!-- Change Password -->
            <section class="profile-section">
                <h2>Change Password</h2>

                <form action="/Ecommerce_final_project/public/profile.php" method="POST" class="profile-form">
                    <input type="hidden" name="action" value="change_password">

                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password *</label>
                        <input type="password"
                               id="new_password"
                               name="new_password"
                               minlength="6"
                               required>
                        <small>Minimum 6 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password *</label>
                        <input type="password"
                               id="confirm_password"
                               name="confirm_password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </section>

            <!-- Order History -->
            <section class="profile-section">
                <h2>Order History</h2>

                <?php if (!empty($orders)): ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div>
                                        <strong>Order #<?php echo $order['id']; ?></strong>
                                        <span class="order-date">
                                            <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                        </span>
                                    </div>
                                    <div class="order-status status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </div>
                                </div>

                                <div class="order-details">
                                    <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                                    <p><strong>Shipping:</strong>
                                        <?php echo htmlspecialchars($order['shipping_address']); ?>,
                                        <?php echo htmlspecialchars($order['shipping_city']); ?>
                                    </p>
                                </div>

                                <a href="/Ecommerce_final_project/public/order-confirmation.php?id=<?php echo $order['id']; ?>"
                                   class="btn btn-sm btn-outline">
                                    View Details
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>You haven't placed any orders yet.</p>
                    <a href="/Ecommerce_final_project/public/products.php" class="btn btn-primary">Start Shopping</a>
                <?php endif; ?>
            </section>

            <!-- Danger Zone -->
            <section class="profile-section danger-zone">
                <h2>Danger Zone</h2>
                <p class="warning-text">
                    <i class="fas fa-exclamation-triangle"></i>
                    Once you delete your account, there is no going back. Please be certain.
                </p>

                <form action="/Ecommerce_final_project/public/profile.php" method="POST"
                      onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');">
                    <input type="hidden" name="action" value="delete_account">
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </form>
            </section>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>






