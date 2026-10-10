# Online Shopping Management System

## Project Overview
The Online Shopping Management System is a web-based application developed using PHP, MySQL, HTML, and CSS. It allows users to view products, add products to a shopping cart, and proceed to checkout.

## Objectives
- To display products online.
- To allow users to add products to a shopping cart.
- To manage product and order details using MySQL.
- To provide a simple and user-friendly shopping interface.

## Technologies Used
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Local Server:** XAMPP

## Project Structure
```text
online_shopping/
├── db.php
├── index.php
├── cart.php
├── checkout.php
├── style.css
├── images/
└── README.md
```

## Features
- Product listing
- Shopping cart
- Checkout page
- Database connectivity
- Product images
- Simple user interface

## Installation and Setup

1. Install XAMPP on your computer.
2. Copy the project folder into `C:\xampp\htdocs\`.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
5. Create a database named `shopdb`.
6. Create the required database tables and insert sample product data.
7. Check the database connection settings in `db.php`.
8. Open the project in your browser:

   `http://localhost/online_shopping/`

## Database Configuration
The project uses MySQL with the database name `shopdb`.

The database connection is configured in `db.php`. Update the username and password according to your local MySQL settings.

## Future Enhancements
- User registration and login
- Product search
- Online payment integration
- Order history
- Admin dashboard

## Author
Developed as a student project to learn PHP, MySQL, and web application development.
