<?php
// Manage Products view for admin panel
// Expects the controller to provide:
//   - $pageTitle : string for the page title
//   - $products  : array of product rows (each row is an associative array)
$pageTitle = 'Manage Products';
?>

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">All Products</h2>
        <!-- Link to create new product -->
        <a href="/Ecommerce_final_project/public/admin/create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- Products table -->
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
        <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
            <th style="padding: 1rem;">Image</th>
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
        // Loop through products provided by controller and render a row per product.
        // Use htmlspecialchars for any user-editable strings to prevent XSS.
        foreach ($products as $product): ?>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <!-- Product thumbnail (falls back to placeholder if image fails) -->
                <td style="padding: 1rem;">
                    <img src="/Ecommerce_final_project/public/<?php echo htmlspecialchars($product['image_url']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem;"
                         onerror="this.src='https://via.placeholder.com/50'">
                </td>

                <!-- Product ID -->
                <td style="padding: 1rem;"><?php echo $product['id']; ?></td>

                <!-- Product name (escaped) -->
                <td style="padding: 1rem;"><?php echo htmlspecialchars($product['name']); ?></td>

                <!-- Category (escaped) -->
                <td style="padding: 1rem;"><?php echo htmlspecialchars($product['category']); ?></td>

                <!-- Price formatted as currency -->
                <td style="padding: 1rem;">$<?php echo number_format($product['price'], 2); ?></td>

                <!-- Stock with color indicator (green if in stock, red if out) -->
                <td style="padding: 1rem;">
                    <span style="<?php echo $product['stock_quantity'] > 0 ? 'color: #10b981;' : 'color: #ef4444;'; ?>">
                        <?php echo $product['stock_quantity']; ?>
                    </span>
                </td>

                <!-- Actions: Edit and Delete -->
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <!-- Edit links to admin edit page for the product -->
                        <a href="/Ecommerce_final_project/public/admin/edit.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Delete form posts to admin/delete.php; includes JS confirm to avoid accidental deletes -->
                        <form action="/Ecommerce_final_project/public/admin/delete.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-sm" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>