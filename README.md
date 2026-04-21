# 🛒 OmniMart — E-Commerce Web Application

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-omnimart__db-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-Compatible-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

> A full-stack e-commerce platform built with PHP, MySQL & vanilla JavaScript. Supports multi-role authentication (Admin, Vendor, User), product management, order tracking, and more.

---

## 📸 Preview

| Home Page | Admin Dashboard | Product Page |
|-----------|----------------|--------------|
| ![Home](images/banner1.jpg) | Admin Panel | Products Grid |

---

## ✨ Features

- 🔐 **Multi-Role Authentication** — Admin, Vendor, and User roles
- 🛍️ **Product Listing & Categories** — Browse by Electronics, Fashion, Home & more
- 🛒 **Shopping Cart & Checkout** — Place orders with Cash on Delivery
- 📦 **Order Management** — Track and update order status
- 👤 **User Profile** — Manage personal info and view order history
- 🏪 **Vendor Dashboard** — Vendors can manage their own products
- 🛠️ **Admin Panel** — Full control over users, products, and orders
- 📩 **Contact Form** — Users can send messages stored in database
- 📱 **Responsive Design** — Works on mobile, tablet, and desktop

---

## 🗂️ Project Structure

```
omnimartt/
├── admin/                  # Admin panel pages
│   ├── dashboard.php
│   ├── products.php
│   ├── orders.php
│   └── users.php
├── backend/
│   ├── api/                # API endpoints
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── place-order.php
│   │   ├── get-products.php
│   │   ├── update-order.php
│   │   └── contact.php
│   ├── config/
│   │   └── db.php          # Database connection
│   └── middleware/
│       └── role_check.php
├── frontend/               # User-facing auth pages
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── profile.php
│   └── orders.php
├── vendor/                 # Vendor dashboard
├── includes/               # Navbar & Footer
├── css/                    # Stylesheets
├── js/                     # JavaScript
├── images/                 # Product & banner images
├── database_setup.sql      # ⬅️ Full DB setup with sample data
├── index.php               # Home page
├── products.php            # All products
├── product.php             # Single product view
├── categories.php          # Browse by category
├── checkout.php            # Checkout page
└── contact.php             # Contact page
```

---

## ⚙️ Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.0+)
- Git

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/omnimartt.git
cd omnimartt
```

### 2. Move to XAMPP's htdocs folder
```bash
# Windows
move omnimartt C:/xampp/htdocs/

# Mac/Linux
mv omnimartt /Applications/XAMPP/htdocs/
```

### 3. Setup the Database
1. Start **Apache** and **MySQL** from XAMPP Control Panel
2. Open [phpMyAdmin](http://localhost/phpmyadmin)
3. Click **New** → name it `omnimart_db` → click **Create**
4. Click **Import** → choose `database_setup.sql` → click **Go**

### 4. Configure Database Connection
Open `backend/config/db.php` and update if needed:
```php
$host     = "localhost";
$user     = "root";
$password = "";           // your MySQL password
$database = "omnimart_db";
```

### 5. Run the Project
Open your browser and go to:
```
http://localhost/omnimartt
```

---

## 👥 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| 👑 Admin | `rohandevadiga@gmail.com` | `rohan` |
| 🏪 Vendor | `aman@gmail.com` | `aman` |
| 👤 User | Register a new account | — |

---

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `users` | Stores all users with roles (admin/vendor/user) |
| `categories` | Product categories with slug and image |
| `products` | All products with price, stock, brand, rating |
| `orders` | Customer orders with status and payment method |
| `order_items` | Individual items inside each order |
| `contact_messages` | Messages submitted via contact form |

---

## 🔐 Role-Based Access

```
/admin/*        → Admin only
/vendor/*       → Vendor only
/frontend/*     → Logged-in users
/index.php      → Public
/products.php   → Public
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.0+ |
| Database | MySQL (via MySQLi) |
| Server | Apache (XAMPP) |
| Auth | PHP Sessions + bcrypt |

---

## 📌 To-Do / Future Improvements

- [ ] Payment gateway integration (Razorpay / Stripe)
- [ ] Product search and filters
- [ ] Email notifications for orders
- [ ] Product reviews and ratings by users
- [ ] Image upload for products via admin panel

---

## 👨‍💻 Author

**Rohan Devadiga**
- GitHub: [@Rohanindia](https://github.com/Rohanindia/omnimartt)
- Email: rohandevadiga@gmail.com

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

> ⭐ If you found this project helpful, please give it a star on GitHub!
