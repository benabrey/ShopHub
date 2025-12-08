# ShopHub E-Commerce Platform

A full-featured e-commerce web application built with the WAMP stack (Windows, Apache, MySQL, PHP) following the MVC architectural pattern.

Folder had to be changed for WAMP to work, so to see all previous commit history, visit: https://github.com/benabrey/Ecommerce-final-project

## 👥 Team Members

- Benjamin Abrey

---

## 📋 Project Description

ShopHub is a comprehensive online shopping platform that provides a complete e-commerce experience. The application features user authentication, product browsing with search and filtering, a fully functional shopping cart, checkout process, and an administrative dashboard for product management.

### Core Features

- ✅ Secure user registration and authentication with password hashing
- ✅ Product catalog with 36+ products across 8 categories
- ✅ Shopping cart with session persistence and real-time updates
- ✅ Multi-step checkout process with order confirmation
- ✅ Admin panel with full CRUD operations for products

### Advanced Features

- ⭐ Product reviews and 5-star rating system
- 🔍 Category filtering and search functionality
- 📦 Inventory management with stock tracking
- 📜 User order history

### Technology Stack

- **Backend:** PHP 8.0+ with PDO for database operations
- **Database:** MySQL 8.0 with normalized schema (5 tables)
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Web Server:** Apache 2.4
- **Architecture:** Model-View-Controller (MVC) pattern
- **Security:** Bcrypt password hashing, SQL injection prevention, XSS protection

---

## 🚀 Setup Instructions

### Prerequisites

- **WAMP/XAMPP/MAMP** (Apache + MySQL + PHP 8.0+)
- **Git** (for cloning repository)
- **Web browser** (Chrome, Firefox, Safari, or Edge)

### Step 1: Clone the Repository

```bash
git clone https://github.com/benabrey/ShopHub.git
cd ShopHub
```

### Step 2: Move to Web Server Directory

**For WAMP:**

```bash
# Copy project to WAMP's www directory
cp -r Ecommerce_final_project C:/wamp64/www/
```

**For XAMPP:**

```bash
cp -r Ecommerce_final_project C:/xampp/htdocs/
```

**For MAMP:**

```bash
cp -r Ecommerce_final_project /Applications/MAMP/htdocs/
```

### Step 3: Database Setup

#### 3.1 Create the Database

1. **Start your web server** (WAMP/XAMPP/MAMP)
2. **Open phpMyAdmin** in browser: `http://localhost/phpmyadmin`
3. Click **"New"** in the left sidebar
4. **Database name:** `ecommerce_finaldb`
5. **Collation:** `utf8mb4_unicode_ci`
6. Click **"Create"**

#### 3.2 Import Database Schema

Run the SQL files in this **exact order**:

1. **Users table:**

   - In phpMyAdmin, select `ecommerce_finaldb` database
   - Click **"SQL"** tab
   - Open `sql/user/schema.sql`
   - Copy and paste the SQL
   - Click **"Go"**

2. **Products table:**

   - Click **"SQL"** tab again
   - Open `sql/product/schema.sql`
   - Copy and paste
   - Click **"Go"**

3. **Orders tables:**

   - Open `sql/order/schema.sql`
   - Copy and paste
   - Click **"Go"**

4. **Reviews table:**

   - Open `sql/reviews/create_reviews_table.sql`
   - Copy and paste
   - Click **"Go"**

5. **Admin column:**
   - Open `sql/admin/add_admin_column.sql`
   - Copy and paste
   - Click **"Go"**

#### 3.3 Seed Sample Data

1. **Products:**

   - Open `sql/product/seed.sql`
   - Copy and paste in SQL tab
   - Click **"Go"**

### Step 4: Configure Database Connection

1. Open `config/database.php`
2. **Update credentials if needed:**

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_finaldb');
define('DB_USER', 'root');
define('DB_PASS', 'pass');
```

### Step 5: Verify Installation

1. **Start Apache and MySQL** in WAMP/XAMPP/MAMP
2. **Open browser** and go to:
   ```
   http://localhost/Ecommerce_final_project/public/index.php
   ```
3. **You should see the homepage** with featured products

---

## 🔐 Demo Accounts

### Admin Account

- **Email:** admin@admin.com
- **Password:** adminpassword

### Regular User Account

**Example:**

- **Email:** example@icloud.com
- **Password:** password

** you can register a new account** at:

```
http://localhost/Ecommerce_final_project/public/register.php
```

---

## 📁 Project Structure

```
Ecommerce_final_project/
│
├── config/
│   └── database.php           # Database connection configuration
│
├── public/                    # Public-facing files (document root)
│   ├── admin/                 # Admin panel
│   │   ├── index.php          # Admin dashboard
│   │   ├── products.php       # Product management
│   │   ├── create.php         # Add new product
│   │   ├── edit.php           # Edit product
│   │   └── delete.php         # Delete product
│   │
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css      # Main stylesheet
│   │   ├── images/
│   │   │   └── products/      # Product images
│   │   └── js/
│   │       └── main.js        # JavaScript functionality
│   │
│   ├── index.php              # Homepage
│   ├── products.php           # Product listing
│   ├── product.php            # Product detail page
│   ├── cart.php               # Shopping cart
│   ├── checkout.php           # Checkout process
│   ├── order-confirmation.php # Order success page
│   ├── login.php              # User login
│   ├── register.php           # User registration
│   └── profile.php            # User profile
│
├── src/
│   ├── controllers/           # Business logic
│   │   ├── AdminController.php
│   │   ├── CartController.php
│   │   ├── ProductController.php
│   │   └── UserController.php
│   │
│   ├── models/                # Database operations
│   │   ├── Order.php
│   │   ├── Product.php
│   │   ├── Review.php
│   │   └── User.php
│   │
│   ├── helpers/               # Utility classes
│   │   ├── AdminAuth.php      # Admin authentication
│   │   ├── Auth.php           # User authentication
│   │   └── Session.php        # Session management
│   │
│   └── views/                 # Presentation layer
│       ├── admin/             # Admin panel views
│       ├── header.php         # Common header
│       ├── navbar.php         # Navigation bar
│       ├── footer.php         # Common footer
│       └── [other views]
│
├── sql/                       # Database files
│   ├── user/
│   ├── product/
│   ├── order/
│   ├── reviews/
│   └── admin/
│
└── README.md                  # This file
```

---

## 🎯 Usage Guide

### For Customers

1. **Browse Products:**

   - Visit homepage or click "Products" in navigation
   - Use search bar or filter by category

2. **Add to Cart:**

   - Click "Add to Cart" on product cards
   - Or go to product detail page for quantity selection

3. **Checkout:**

   - Click cart icon in navbar
   - Review items and click "Proceed to Checkout"
   - Fill in shipping information
   - **Test Card:** 4242 4242 4242 4242
   - Place order

4. **View Orders:**
   - Click user menu → "My Account"
   - See order history and details

### For Administrators

1. **Access Admin Panel:**

   - Login with admin account
   - Click user menu → "Admin Panel"

2. **Manage Products:**

   - **View all products** in table format
   - **Add product:** Click "Add Product" button
   - **Edit product:** Click "Edit" on any product row
   - **Delete product:** Click "Delete" (with confirmation)

3. **Dashboard:**
   - View statistics: total products, users, orders, revenue
   - See recent products

---

## 🔒 Security Features

- ✅ **Password Security:** Bcrypt hashing with cost factor 12
- ✅ **SQL Injection Prevention:** PDO prepared statements throughout
- ✅ **XSS Protection:** Output sanitization with `htmlspecialchars()`
- ✅ **Session Security:** HTTP-only session cookies
- ✅ **CSRF Protection:** Session-based validation
- ✅ **Admin Access Control:** Role-based authentication

---

## 📊 Database Schema

### Tables (5 total)

1. **users** - User accounts with authentication
2. **products** - Product catalog with inventory
3. **orders** - Customer orders with shipping details
4. **order_items** - Junction table linking orders and products
5. **reviews** - Product reviews with ratings (1-5 stars)

### Relationships

- Users → Orders (One-to-Many)
- Users → Reviews (One-to-Many)
- Products → Order Items (One-to-Many)
- Products → Reviews (One-to-Many)
- Orders → Order Items (One-to-Many)

**For detailed schema, see:** `sql/` directory

---

## 🐛 Troubleshooting

### Issue: "Database connection failed"

**Solution:**

- Check MySQL is running in WAMP/XAMPP
- Verify credentials in `config/database.php`
- Ensure database `ecommerce_finaldb` exists

### Issue: Images not loading

**Solution:**

- Check images exist in `public/assets/images/`
- Verify image URLs in database match actual filenames

### Issue: "Class not found" errors

**Solution:**

- Check file paths in `require_once` statements
- Ensure all files are in correct directories per project structure

### Issue: Can't access admin panel

**Solution:**

- Login with admin account first
- Verify `is_admin = 1` in users table
- Clear browser cookies and retry

### Issue: Cart not working

**Solution:**

- Check PHP sessions are enabled
- Clear browser cookies
- Ensure session_start() is called

---

## 🎓 Features by Category

### ✅ Core Requirements (All Implemented)

1. Public product catalog ✓
2. Product detail pages ✓
3. User registration & login ✓
4. Shopping cart ✓
5. Admin panel (CRUD) ✓
6. Simulated checkout ✓

### ⭐ Advanced Features (4+ Implemented)

1. Product categories & filtering ✓
2. User order history ✓
3. Product reviews & ratings ✓
4. Search functionality ✓
5. Inventory management ✓

---

## 📈 Project Statistics

- **Lines of Code:** ~5,000+
- **PHP Files:** 50+
- **Database Tables:** 5
- **Product Categories:** 8
- **Seeded Products:** 36
- **Development Time:** 10 weeks

---

## 📝 License

This project was created as a final project for COSC 213: Web Development using LAMP at Okanagan College.

---

**Last Updated:** December 2025
