<div align="center">

# 🏠 DreamHome Property Management System

A web-based property management system built with **Laravel 12** and **Breeze** for staff authentication, property listings, and tenant management.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Latest-336791?style=for-the-badge&logo=postgresql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![AlpineJS](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

</div>

---

## ✨ Features

- 🔐 **Staff Authentication** — Custom staff login system using Laravel Breeze (separate from regular user auth)
- 🏘️ **Property Management** — Full CRUD for property listings (create, view, edit, update)
- 👷 **Staff Management** — Manage staff accounts with details like position, salary, branch, and contact info
- 📊 **Dashboard** — Analytics and overview with charts powered by ApexCharts
- 👥 **Renters Module** — Track and manage renters
- 📅 **Viewings Module** — Schedule and manage property viewings
- 📝 **Leases Module** — Handle rental lease records
- 🔍 **Inspections Module** — Property inspection tracking
- 📈 **Reports Module** — Generate and view system reports
- 👤 **Profile Management** — Staff can update their own profile information

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 |
| Auth Scaffolding | Laravel Breeze 2.x |
| Language | PHP 8.2+ |
| Database | PostgreSQL |
| Frontend Styling | Tailwind CSS v3 |
| UI Components | Flowbite |
| JS Interactivity | Alpine.js 3.x |
| Charts | ApexCharts |
| Build Tool | Vite 7.x |
| Package Manager | Composer + npm |

---

## 📁 Folder Structure

```
dreamhome-system/
└── dreamhome/                  # Laravel root
    ├── app/
    │   ├── Http/
    │   │   └── Controllers/    # DashboardController, PropertiesController, StaffProfileController, etc.
    │   └── Models/             # User.php, Staff.php, Properties.php
    ├── database/
    │   ├── migrations/         # DB schema definitions
    │   ├── seeders/            # DatabaseSeeder
    │   └── factories/          # Model factories
    ├── resources/
    │   └── views/
    │       ├── staff/          # Staff-side views (dashboard, properties, renters, etc.)
    │       ├── layouts/        # App layouts
    │       └── components/     # Reusable Blade components
    ├── routes/
    │   ├── web.php             # All web routes (staff + user)
    │   └── auth.php            # Breeze auth routes
    ├── public/                 # Publicly accessible assets
    ├── .env.example            # Environment variable template
    ├── composer.json           # PHP dependencies
    └── package.json            # Node dependencies
```

---

## ⚙️ Installation Guide

Follow these steps carefully to set up the project on your local machine.

### ✅ Prerequisites

Make sure you have the following installed before proceeding:

- [PHP 8.2+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [Node.js + npm](https://nodejs.org/)
- [PostgreSQL](https://www.postgresql.org/download/)
- [Git](https://git-scm.com/)

---

### 1. Clone the Repository

```bash
git clone https://github.com/Jedlouies/dreamhome-system.git
cd dreamhome-system/dreamhome
```

---

### 2. Install PHP Dependencies via Composer

```bash
composer install
```

Then update to make sure everything is current:

```bash
composer update
```

---

### 3. Install Laravel Breeze

```bash
composer require laravel/breeze --dev
```

Run the Breeze installer and select **Blade** as the stack when prompted:

```bash
php artisan breeze:install
```

---

### 4. Set Up Environment File

Copy the example `.env` file:

```bash
cp .env.example .env
```

Then generate the application key:

```bash
php artisan key:generate
```

---

### 5. Configure the Database

Open the `.env` file and update the following values to match your PostgreSQL setup:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dreamhome
DB_USERNAME=your_postgres_username
DB_PASSWORD=your_postgres_password
```

> 💡 Make sure you have already created the `dreamhome` database in PostgreSQL before running migrations.
>
> ```sql
> CREATE DATABASE dreamhome;
> ```

---

### 6. Run Migrations

```bash
php artisan migrate
```

_(Optional)_ Seed the database with default test data:

```bash
php artisan db:seed
```

---

### 7. Install Node Dependencies

```bash
npm install
```

---

### 8. Install Flowbite

```bash
npm install flowbite
```

---

### 9. Link Storage

```bash
php artisan storage:link
```

---

### 10. Build Frontend Assets

For **development** (with hot reload):

```bash
npm run dev
```

For **production** build:

```bash
npm run build
```

---

### 11. Start the Laravel Development Server

```bash
php artisan serve
```

Visit the app at: **http://127.0.0.1:8000**

Staff login is at: **http://127.0.0.1:8000/staff/login**

---

## 🚀 Quick Setup (All-in-One)

You can run all the setup steps in one go using the Composer script:

```bash
composer run setup
```

This will automatically run `composer install`, copy `.env`, generate the app key, migrate the database, install npm packages, and build assets.

> ⚠️ Make sure your `.env` database credentials are configured first before using the quick setup!

---

## 📋 Useful Artisan Commands

| Command | Description |
|---|---|
| `php artisan serve` | Start local dev server |
| `php artisan migrate` | Run all migrations |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations |
| `php artisan migrate:fresh --seed` | Fresh migrate + seed |
| `php artisan db:seed` | Seed the database |
| `php artisan storage:link` | Link the storage folder to public |
| `php artisan route:list` | View all registered routes |
| `php artisan config:clear` | Clear config cache |

---

## 👨‍💻 Contributing (for Teammates)

1. Create your own branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```
2. Make your changes and commit:
   ```bash
   git add .
   git commit -m "feat: describe your change"
   ```
3. Push and open a pull request:
   ```bash
   git push origin feature/your-feature-name
   ```

> Please do **not** push directly to `main`.

---

<div align="center">

Made with ❤️ for our school project — **DreamHome Property Management System**

</div>
