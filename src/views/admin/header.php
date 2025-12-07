<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ShopHub</title>
    <link rel="stylesheet" href="/Ecommerce_final_project/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        .admin-sidebar {
            background: #1f2937;
            color: white;
            padding: 2rem 0;
        }

        .admin-logo {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid #374151;
            margin-bottom: 1rem;
        }

        .admin-logo h2 {
            margin: 0;
            color: white;
        }

        .admin-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-nav li {
            margin: 0;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.2s;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: #374151;
            color: white;
        }

        .admin-nav i {
            width: 20px;
        }

        .admin-main {
            background: #f3f4f6;
            padding: 2rem;
        }

        .admin-header {
            background: white;
            padding: 1.5rem 2rem;
            margin: -2rem -2rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-back-to-site {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back-to-site:hover {
            background: #5568d3;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0 0 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
        }

        .content-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
    </style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-logo">
            <h2><i class="fas fa-shopping-cart"></i> ShopHub Admin</h2>
        </div>
        <ul class="admin-nav">
            <li><a href="/Ecommerce_final_project/public/admin/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
            <li><a href="/Ecommerce_final_project/public/admin/products.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i> Products
                </a></li>
            <li><a href="/Ecommerce_final_project/public/admin/create.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'create.php' ? 'active' : ''; ?>">
                    <i class="fas fa-plus"></i> Add Product
                </a></li>
            <li><a href="/Ecommerce_final_project/public/index.php">
                    <i class="fas fa-arrow-left"></i> Back to Site
                </a></li>
        </ul>
    </aside>
    <main class="admin-main">
        <div class="admin-header">
            <h1><?php echo $pageTitle ?? 'Admin Dashboard'; ?></h1>
            <div class="admin-user">
                <span>Welcome, <?php echo htmlspecialchars(Session::get('username', 'Admin')); ?></span>
                <a href="/Ecommerce_final_project/public/logout.php" class="btn btn-sm btn-outline">Logout</a>
            </div>
        </div>

        <?php if (Session::has('success')): ?>
            <div class="alert alert-success">
                <?php echo Session::get('success'); Session::remove('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (Session::has('error')): ?>
        <div class="alert alert-error">
            <?php echo Session::get('error'); Session::remove('error'); ?>
        </div>
<?php endif; ?>