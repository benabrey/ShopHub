<?php
// Determine whether we are editing an existing product or creating a new one
$isEdit = isset($product);
$pageTitle = $isEdit ? 'Edit Product' : 'Create Product';
?>

<div class="content-card">
    <h2 style="margin-top: 0;"><?php echo $isEdit ? 'Edit' : 'Create New'; ?> Product</h2>

    <!-- Product form: posts back to the same page and is handled by the controller -->
    <form method="POST" style="max-width: 800px;">
        <div style="display: grid; gap: 1.5rem;">

            <!-- Product name (required) -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Name *</label>
                <input type="text" name="name" required
                       value="<?php echo $isEdit ? htmlspecialchars($product['name']) : ''; ?>"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <!-- Description (optional) -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                <textarea name="description" rows="4"
                          style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"><?php echo $isEdit ? htmlspecialchars($product['description']) : ''; ?></textarea>
            </div>

            <!-- Price and Stock fields -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Price *</label>
                    <input type="number" name="price" step="0.01" min="0" required
                           value="<?php echo $isEdit ? $product['price'] : ''; ?>"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" min="0" required
                           value="<?php echo $isEdit ? $product['stock_quantity'] : '0'; ?>"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </div>

            <!-- Category and Brand -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Category *</label>
                    <select name="category" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="">Select Category</option>
                        <?php
                        // Static list of categories for the admin form.
                        // In future this could be pulled from a categories table.
                        $categories = ['Electronics', 'Home & Kitchen', 'Sports & Outdoors', 'Shoes', 'Bags', 'Health & Personal Care', 'Furniture', 'Office Products'];
                        foreach ($categories as $cat):
                            $selected = $isEdit && $product['category'] === $cat ? 'selected' : '';
                            ?>
                            <option value="<?php echo $cat; ?>" <?php echo $selected; ?>><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Brand</label>
                    <input type="text" name="brand"
                           value="<?php echo $isEdit ? htmlspecialchars($product['brand']) : ''; ?>"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </div>

            <!-- Image URL field with small helper text -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Image URL</label>
                <input type="text" name="image_url"
                       value="<?php echo $isEdit ? htmlspecialchars($product['image_url']) : ''; ?>"
                       placeholder="assets/images/products/product.jpg"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <small style="color: #6b7280;">Relative path from public folder (e.g., assets/images/products/product.jpg)</small>
            </div>

            <!-- Form actions: submit and cancel -->
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update' : 'Create'; ?> Product
                </button>
                <a href="/Ecommerce_final_project/public/admin/products.php" class="btn btn-outline">Cancel</a>
            </div>
        </div>
    </form>
</div>