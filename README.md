# Online Shopping System Setup

## Prerequisites
- XAMPP (or any PHP/MySQL environment)
- A web browser

## Installation
1.  **Copy Files**: Copy all files in this directory to your XAMPP web root (usually `C:\xampp\htdocs\onlineshop`).
2.  **Start Servers**: Open XAMPP Control Panel and start **Apache** and **MySQL**.
3.  **Create Database**:
    - Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
    - Create a new database named `onlineshop`.
    - Click "Import" and select the `sql/database.sql` file from this project.
    - Click "Go" to run the SQL script.
4.  **Configuration**:
    - The database connection settings are in `includes/db.php`. Default is User: `root`, Password: `` (empty). Update if your configuration differs.

## Usage
- **Storefront**: [http://localhost/onlineshop/index.php](http://localhost/onlineshop/index.php)
- **Admin Panel**: [http://localhost/onlineshop/admin/index.php](http://localhost/onlineshop/admin/index.php)

## Features
- User Registration & Login
- Product Browsing & Searching
- Shopping Cart & Checkout
- Admin Dashboard (Manage Products, Orders)
