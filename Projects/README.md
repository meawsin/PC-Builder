# Custom PC Builder & Management System

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL Database](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Apache / XAMPP](https://img.shields.io/badge/XAMPP-Supported-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)
[![Database Management System](https://img.shields.io/badge/DBMS-Project-brightgreen?style=for-the-badge)](https://github.com/meawsin/PC-Builder)

A full-stack web application designed for custom PC configuration, hardware compatibility validation, side-by-side component comparison, order fulfillment, and administrative DBMS analytics. Developed with PHP and MySQL as a Database Management System (DBMS) project.

---

## Visual Overview and Feature Walkthrough

### 1. Customer Portal

#### Homepage with Active Promotions and Dynamic Countdown
Displays current promotional offerings with real-time countdown timers and direct navigation to all system modules.
![Home Page](./screenshots/01_home_page.png)

#### User Authentication Portal
Access portal supporting role-based authentication for regular users and administrators.
![Login](./screenshots/02_user_login.png)

#### Step-by-Step PC Configuration Engine
Dynamic component selection engine that computes real-time system wattage and estimated prices while applying hardware compatibility rules (CPU socket generation, Motherboard RAM support, PSU power headroom, and Casing form factors).
![PC Builder](./screenshots/03_pc_builder.png)

#### User Builds Dashboard
Allows users to review saved configurations, examine detailed specifications of all selected components, and place purchase orders.
![My Builds](./screenshots/04_my_builds.png)

#### Order Tracking and Status
Order monitoring interface displaying build numbers, order status updates (Pending, Processing, Shipped, Delivered), and invoice access links.
![Order Status](./screenshots/05_order_status.png)

#### Itemized Order Invoice and Build Sheet
Print-ready invoice providing an itemized breakdown of component pricing, customer contact information, and total order costs.
![Order Invoice](./screenshots/06_order_invoice.png)

#### Hardware Component Catalog and Search
Search and browse processors, graphics cards, motherboards, storage, memory, power supplies, and casings with detailed specifications.
![Component Catalog](./screenshots/07_component_catalog.png)

#### Side-by-Side Product Comparison
Enables comparative analysis of any two hardware components across specifications (clock, memory, TDP wattage, form factor, and price) within any category.
![Product Comparison](./screenshots/08_product_comparison.png)

#### Technical Inquiry and Consultation
Direct consultation interface allowing customers to submit technical inquiries to administrators regarding build compatibility and custom requests.
![Ask Expert](./screenshots/09_ask_expert.png)

---

### 2. Administrative Control & DBMS Insights

#### Admin Control Center
Central management dashboard for hardware catalog updates, order processing, and system oversight.
![Admin Dashboard](./screenshots/10_admin_dashboard.png)

#### DBMS Insights and Aggregated Business Analytics
Business intelligence interface powered by SQL relational aggregate queries (`COUNT`, `SUM`, `AVG`, `GROUP BY`) displaying user growth, total revenue, average build values, order distribution, and most popular hardware components.
![DBMS Analytics](./screenshots/11_dbms_analytics.png)

#### Order Fulfillment Manager
Order lifecycle management interface enabling administrators to update order statuses and inspect custom configuration specifications.
![Manage Orders](./screenshots/12_manage_orders.png)

#### Hardware Inventory Management
Product management portal allowing administrators to add new hardware models, modify existing pricing and specs, or remove discontinued components across all hardware categories.
![Manage Products](./screenshots/13_manage_products.png)

#### User Account Management
Customer roster interface showing user IDs, contact records, delivery addresses, and associated order history.
![Manage Users](./screenshots/14_manage_users.png)

#### Customer Inquiry and Support Desk
Dedicated inquiry response portal for administrators to answer technical questions submitted by customers.
![Admin Support](./screenshots/15_admin_responses.png)

---

## Database Architecture and Relational Schema

The underlying database `projectdemo1` consists of 13 relational tables with enforced foreign key constraints:

```mermaid
erDiagram
    USER ||--o{ BUILDS : configures
    USER ||--o{ ORDERS : places
    USER ||--o{ MESSAGES : sends
    ADMIN ||--o{ MESSAGES : responds
    BUILDS ||--o{ ORDERS : contains
    CPU ||--o{ BUILDS : includes
    MOTHERBOARD ||--o{ BUILDS : includes
    RAM ||--o{ BUILDS : includes
    STORAGE ||--o{ BUILDS : includes
    GRAPHICS_CARD ||--o{ BUILDS : includes
    POWER_SUPPLY ||--o{ BUILDS : includes
    CASING ||--o{ BUILDS : includes

    USER {
        string U_ID PK
        string Name
        string Email
        string Phone_Number
        string Address
        string Password
    }

    ADMIN {
        string A_ID PK
        string Name
        string Email
        string Password
    }

    BUILDS {
        int Build_ID PK
        string User_ID FK
        string CPU_ID FK
        string Motherboard_ID FK
        string RAM_ID FK
        string Storage_ID FK
        string Graphics_Card_ID FK
        string PSU_ID FK
        string Casing_ID FK
        string Build_Number
        int Estimated_Price
        datetime Build_Date
    }

    ORDERS {
        int Order_ID PK
        string U_ID FK
        int Build_ID FK
        int Price
        string Status
    }

    MESSAGES {
        int Message_ID PK
        string User_ID FK
        string Admin_ID FK
        text Message
        text Response
        timestamp Created_At
    }

    PROMOTIONS {
        int id PK
        string image_url
        datetime expiration_date
    }
```

### Relational Schema Highlights:
- **Foreign Keys**: `builds` links to all 7 hardware component tables (`CPU`, `Motherboard`, `RAM`, `Storage`, `Graphics_Card`, `Power_Supply`, `Casing`) alongside `User`.
- **Integrity**: `orders` connects back to `User` and `builds` to guarantee pricing and build history immutability.
- **Aggregation**: Multi-table analytical queries power the administrator analytics dashboard.

---

## Getting Started and Installation

### 1. Prerequisites
- **PHP**: 8.0 or newer
- **MySQL**: 8.0 or newer (or MariaDB via XAMPP)
- **Web Server**: Apache (via XAMPP) or PHP Built-in Server

### 2. Database Initialization
1. Start the MySQL service.
2. Create the database and import schema & seed data:
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS ProjectDemo1;"
mysql -u root -p ProjectDemo1 < "Projects/Table creating codes.txt"
mysql -u root -p ProjectDemo1 < "seed_demo_data.sql"
```

### 3. Database Credentials Configuration
Verify or edit `Projects/connection.php` if your local MySQL settings differ:
```php
$host = "localhost";
$user = "root";
$password = "root";       // Set your local MySQL password
$database = "ProjectDemo1";
```

### 4. Running the Application

#### Option A: PHP Built-in Server
```bash
cd Projects
php -S localhost:8080
```
Open in browser: [http://localhost:8080/index.php](http://localhost:8080/index.php)

#### Option B: XAMPP / Apache
1. Place or symlink the `Projects` directory inside your XAMPP web root:
   `C:/xampp/htdocs/Projects`
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open in browser: [http://localhost/Projects/index.php](http://localhost/Projects/index.php)

---

## Test Credentials

| Role | Username | Password | Description |
| :--- | :--- | :--- | :--- |
| **Customer** | `Mohsin` | `mohsin` | User account with pre-configured custom builds & orders |
| **Customer** | `Sarah Jenkins` | `sarah123` | Customer with budget AMD build & delivered order |
| **Customer** | `Alex Mercer` | `alex2024` | Customer with high-end RTX 3090 workstation build |
| **Administrator** | `John Doe` | `password123` | Full administrative privileges across all modules |
| **Administrator** | `Jane Smith` | `password456` | Full administrative privileges |

---

## Repository Structure

```text
PC Builder/
├── Projects/                       # Application source code
│   ├── index.php                   # Homepage with promotions & countdown timer
│   ├── building.php                # PC Builder component selection engine
│   ├── compare.php                 # Side-by-side component comparison
│   ├── components.php              # Hardware component catalog
│   ├── mybuilds.php                # User's saved builds & order placement
│   ├── orderStatus.php             # Order tracking view
│   ├── printbuild.php              # Itemized build print / invoice sheet
│   ├── ask.php                     # Customer technical inquiry chat
│   ├── login.php                   # Login authentication form
│   ├── signup.php                  # User registration
│   ├── forgot_password.php         # CSRF-protected password reset
│   ├── logout.php                  # Session termination
│   ├── admin_dashboard.php         # Admin control panel
│   ├── Anlytics.php                # DBMS System Insights & Business Analytics
│   ├── manage_orders.php           # Order status management
│   ├── manage_product.php          # Hardware catalog CRUD portal
│   ├── manage_users.php            # Registered user oversight
│   ├── admin_responses.php         # Admin support desk & inquiry replies
│   ├── promotion.php               # Promotional banner upload & management
│   ├── connection.php              # Database connection configuration
│   ├── header.php                  # Universal navigation bar & session state
│   ├── Table creating codes.txt    # Base DDL & 141 component seed script
│   └── uploads/                    # Promotional banners and assets
├── screenshots/                    # High-resolution screenshots for documentation
├── add_more_components.sql         # Expansion SQL adding 70 modern components
├── seed_demo_data.sql              # Seed data for users, builds, orders & messages
├── capture_all_screenshots.py      # Automated Selenium script to capture all views
├── capture_admin_screenshots.py    # Dedicated admin screenshot automation
└── README.md                       # System documentation
```

---

## Key Technical Highlights

- **Relational Integrity**: Enforced foreign key constraints across 8 interrelated entities (`User`, `Admin`, `Builds`, `Orders`, `Messages`, and hardware tables).
- **Dynamic Compatibility Engine**: Automated hardware validation enforcing socket generation matching, memory standards (DDR4/DDR5), thermal/wattage headroom calculations, and physical chassis form factor constraints.
- **SQL Aggregations and Analytics**: Real-time administrative reporting engine utilizing SQL multi-table joins, subqueries, and `GROUP BY` aggregation for order tracking, inventory status, and revenue calculation.
- **Transactional Workflow**: End-to-end e-commerce flow covering component selection, build saving, order placement, lifecycle management, and printable invoice generation.
