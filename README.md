# 🚀 Laravel Modular System

A clean and scalable **modular structure** for Laravel that helps you separate features into **independent modules**. Each module includes its own controllers, models, views, routes, migrations, seeders, and even public assets (CSS, JS, images).

---

## 📦 Features

- 🔧 Artisan command to create modules: `php artisan make:module ModuleName`
- 🧱 MVC structure generated automatically
- 📁 Public asset folders (`CSS`, `JS`, `Images`, `Docs`) inside each module
- 🔄 Routes auto-registered via `routes/modules.php`
- 🎨 Beautiful default `index.blade.php` with Tailwind CSS and SVG design

---

## 🛠️ Installation

### 1. Clone the project 
### 2. Run composer install
### 3. Run php artisan migrate
### 4. Run php artisan make:module 'modulename'
### 5. Run 'http://localhost:8000/modulenameroute'
