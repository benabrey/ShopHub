<?php
// Set the page title used by the admin layout
$pageTitle = 'Dashboard';
?>

<!-- Stats grid: summary cards for quick overview -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Products</h3>
        <!-- Total number of products in the catalog (from controller) -->
        <div class="stat-value"><?php echo $totalProducts; ?></div>
    </div>

    <div class="stat-card">
        <h3>Total Users</h3>
        <!-- Total registered users (from controller) -->
        <div class="stat-value"><?php echo $totalUsers; ?></div>
    </div>

    <div class="stat-card">
        <h3>Total Orders</h3>
        <!-- Total orders placed (from controller) -->
        <div class="stat-value"><?php echo $totalOrders; ?></div>
    </div>

    <div class="stat-card">
        <h3>Total Revenue</h3>
        <!-- Sum of order totals (formatted as currency) -->
        <div class="stat-value">$<?php echo number_format($totalRevenue, 2); ?></div>
    </div>
</div>

<!-- Recent Products: table listing a subset of products for quick actions -->
<div class="content-card">
    <h2 style="margin-top: 0;">Recent Products</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
        <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
            <th style="padding: 1rem;">ID</th>
            <th style="padding: 1rem;">Name</th>
            <th style="padding: 1rem;">Category</th>
            <th style="padding: 1rem;">Price</th>
            <th style="padding: 1rem;">Stock</th>
            <th style="padding: 1rem;">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Loop over the first 10 products provided by the controller ($products)
        // array_slice is used to avoid rendering too many rows here.
        foreach (array_slice($products, 0, 10) as $product): ?>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 1rem;"><?php echo $product['id']; ?></td>
                <!-- Escape output to avoid XSS for user-editable strings -->
                <td style="padding: 1rem;"><?php echo htmlspecialchars($product['name']); ?></td>
                <td style="padding: 1rem;"><?php echo htmlspecialchars($product['category']); ?></td>
                <td style="padding: 1rem;">$<?php echo number_format($product['price'], 2); ?></td>
                <td style="padding: 1rem;"><?php echo $product['stock_quantity']; ?></td>
                <td style="padding: 1rem;">
                    <!-- Link to admin edit page for the product -->
                    <a href="/Ecommerce_final_project/public/admin/edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        <!-- Button to view the full products management page -->
        <a href="/Ecommerce_final_project/public/admin/products.php" class="btn btn-primary">View All Products</a>
    </div>
</div>
