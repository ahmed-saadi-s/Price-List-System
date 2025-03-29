# Price List System

A price list management system built with Laravel 11, featuring a simple admin dashboard for easy manual testing.

This system allows defining different product prices based on country, currency, and time period. It includes an admin dashboard for easy management and an API to fetch applicable prices dynamically.
## Installation

Clone the Repository
```bash

git clone https://github.com/ahmed-saadi-s/price-list-system.git
cd price-list-system
```

Install Dependencies
```bash
composer install
```

 Configure Environment Variables
```bash
cp .env.example .env
php artisan key:generate
```
Update the .env file with your database credentials.


 Run Migrations & Seeders
```bash
php artisan migrate --seed
```

 Start the Development Server
```bash
php artisan serve
```

The admin dashboard is available at:
```bash
http://localhost:8000/dashboard
```
Admin Credentials:

Username: admin

Password: admin123
## 🛠️ Testing the Application

```python
php artisan test
```
Note: When running tests (php artisan test),
 ensure you use MySQL as the database instead of SQLite.
 SQLite may return decimal values (e.g., 100) as numbers without decimal formatting,
 causing test failures when expecting strings like "100.00".
MySQL preserves decimal formatting (e.g., 100.00) due to the decimal(8, 2) data type,
making it more compatible with the test expectations in this project.


The repository includes a Postman Collection and a documentation file explaining the thought process and decisions made during the implementation.
