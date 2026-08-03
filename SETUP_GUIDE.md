# Sukkur Hostel & Dawn School System - Setup & Deployment Guide

This project is a School Management System containing the customized **Sukkur Hostel Management** ledger and database system.

---

## Technical Specifications

- **PHP Version Requirement**: `^8.1` or `^8.2`
- **Laravel Framework Version**: `^10.0`
- **Frontend Tools**: Laravel Mix / Vite (Tailwind CSS, Vanilla JS)
- **Database**: MySQL / MariaDB

---

## Installation & Setup Instructions

If you are cloning this repository on a new machine or deploying it to a live production server, follow these setup steps:

### 1. Clone the Repository
```bash
git clone https://github.com/Zaffar1/dawn-school.git
cd dawn-school
```

### 2. Install PHP Dependencies (Composer)
- **For Local Development:**
  ```bash
  composer install
  ```
- **For Live Production Server:**
  ```bash
  composer install --no-dev --optimize-autoloader
  ```

### 3. Create and Configure Environment File (`.env`)
1. Duplicate the example template file:
   ```bash
   cp .env.example .env
   ```
2. Open the `.env` file and configure your database credentials, app URL, and environment variables:
   ```env
   APP_NAME="SUPER DAWN SCHOOL LAKHI"
   APP_ENV=production        # Use 'local' for development, 'production' for live
   APP_DEBUG=false           # Set to 'false' on live server to hide technical errors
   APP_URL=https://your-domain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

### 4. Generate Application Security Key
```bash
php artisan key:generate
```

### 5. Build and Populate the Database
1. Create a blank database in MySQL/MariaDB matching your `DB_DATABASE` setting.
2. Run Laravel migrations to build the tables, and seed the default roles/users:
   ```bash
   php artisan migrate --seed
   ```
   *Note: The seeders populate the initial settings, default accounts, and hostel dashboard values.*

### 6. Create Storage Public Symlink
Run this command to create a symlink to display uploaded files (like student profiles or school logos):
```bash
php artisan storage:link
```

### 7. Compile Frontend Assets (NPM)
1. Install NPM packages:
   ```bash
   npm install
   ```
2. Build assets:
   - **For Live Production:**
     ```bash
     npm run build
     ```
   - **For Local Development:**
     ```bash
     npm run dev
     ```

### 8. Production Optimization (Live Servers Only)
To cache configurations, routes, and views for optimal performance on live hosting:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
