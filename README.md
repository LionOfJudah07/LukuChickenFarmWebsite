# LUKU Farms Distribution Website v1.0

A comprehensive, responsive, and secure web application developed for **LUKU Farms** (እውቀት ጥራትን ያመጣል). This platform serves as the central digital hub for automated poultry supply distribution, veterinary product tracking, feed logistics management, and service booking within Addis Ababa and its surrounding radius.

---

## 🎯 Project Overview
The **LUKU Farms Distribution Website** bridges the gap between commercial poultry operations and smallholder farmers. The system streamlines complex multi-tiered product distribution (feeds, vaccines, medicines) alongside expert service offerings (coop construction, veterinary consultation, beak trimming), complete with a strict logistics and delivery tariff engine.

### Key Objectives
* Provide a dynamic, database-driven catalog for specialized poultry feeds, vaccines, and veterinary medicines.
* Implement structural delivery calculations based on volume, weight thresholds, and regional parameters.
* Deliver an intuitive user registration and client authentication ecosystem.
* Establish a high-performance, secure data persistence layer using PostgreSQL relational architecture.

---

## 🚀 Key Features

### 1. Advanced Product & Service Catalog
* **Nutritional Feed Engine:** Categorized presentation of specialized feeds (Starter, Medicated, Grower, Layer, Broiler) sorted by developmental timelines (e.g., 0-6 weeks) and forms (Crumbles, Pellets, Mash).
* **Biosecurity & Vaccine Registry:** Detailed administration matrix mapping specific vaccines (Newcastle, Gumboro, Fowl Pox) to accurate application vectors (Eye drop, Wing web stab, Drinking water) and schedules.
* **Veterinary Medical Directory:** Inventory indexing of antibiotics, dewormers, and rehydration supports complete with structural dosage data.
* **Service Booking Gateway:** Client portals to request specialized solutions including multi-floor Chicken Coop Construction, Debeaking, and Biosecurity audits.

### 2. Intelligent Feed Logistics & Delivery Router
* **Weight Threshold Triggers:** Built-in automation detecting if order weight satisfies the **400 KG minimum limit**.
* **Dynamic Fee Calculation:** Automatically computes standard shipping fees (**1,500.00 ETB base fee**) or grants **Free Delivery** for bulk volumes exceeding 2000 KG.
* **Geographical Filtering:** Boundary restriction handling ensuring delivery coverage remains confined within Addis Ababa and its strategic 50km radius.

### 3. Account Management & Authentication
* Secure client registration and session-controlled login states.
* Role-based visibility separating public poultry catalogs from authorized procurement dashboards.

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Backend Core** | PHP 7.4+ |
| **Database Engine** | **PostgreSQL** (Relational Database Management System) |
| **Frontend Layout** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 Framework |
| **Payment & Operations** | Multichannel tracking interface (Cash, Bank Transfer, Mobile Money) |

---

## 🗄️ Database Schema Design (PostgreSQL)

The relational storage layer utilizes PostgreSQL schemas optimized for high ACID compliance and strict foreign key relationships:

### 1. `users` Table
Tracks customer and administrator access metadata.
* `id` (SERIAL PRIMARY KEY)
* `username` (VARCHAR, UNIQUE)
* `password_hash` (VARCHAR)
* `full_name` (VARCHAR)
* `email` (VARCHAR, UNIQUE)
* `phone_number` (VARCHAR)
* `created_at` (TIMESTAMP)

### 2. `products` Table
Houses all physical agricultural inventory items (Feeds, Medicines, Vaccines).
* `id` (SERIAL PRIMARY KEY)
* `product_name` (VARCHAR)
* `sku_type` (VARCHAR) — *Feed, Vaccine, Medicine, or Poultry Supply*
* `category_age` (VARCHAR) — *e.g., 0-6 weeks, 18+ weeks*
* `form_factor` (VARCHAR) — *Crumbles, Pellets, Mash, Injection, Liquid*
* `price_per_unit` (NUMERIC)
* `unit_weight_kg` (NUMERIC)
* `stock_quantity` (INTEGER)

### 3. `orders` Table
Manages transactional purchasing and shipping logs.
* `id` (SERIAL PRIMARY KEY)
* `user_id` (INTEGER, FOREIGN KEY references `users.id`)
* `total_weight_kg` (NUMERIC)
* `base_price` (NUMERIC)
* `delivery_fee` (NUMERIC)
* `payment_method` (VARCHAR) — *Cash, Transfer, Mobile Money*
* `delivery_address` (TEXT)
* `order_status` (VARCHAR)
* `created_at` (TIMESTAMP)

---

## 💻 Local Setup & Deployment

Follow these steps to deploy and execute the platform locally using an Apache/PostgreSQL configuration (such as XAMPP/WAMP configured with the `php_pdo_pgsql` extension):

1. **Clone or Download the Repository:**
   Extract the project folder directly into your local server root environment (e.g., `/htdocs/luku-farm`).

2. **Configure PostgreSQL Database:**
   * Open your PostgreSQL terminal or administration interface (pgAdmin).
   * Create a completely new database instance named: `luku_farm_db`.
   * Execute the initialization script payload located inside `/sql/setup.sql` to construct tables and seed default lookup values.

3. **Enable PHP PostgreSQL Drivers:**
   Ensure your local environment configuration file (`php.ini`) has explicitly uncommented and active database extensions:
   
```ini
   extension=php_pdo_pgsql.dll
   extension=php_pgsql.dll