# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**SIIAlmacen** is a warehouse/inventory management system built with Laravel 8 + MySQL, served via XAMPP (Apache + PHP). The system manages product inventory, stock entries (entradas), exits (salidas), suppliers, warehouses, and provides Kardex-based audit trails.

## Common Commands

```bash
# Install dependencies
composer install
npm install

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:rollback

# Development server (alternative to XAMPP)
php artisan serve

# Cache clearing (do this after config changes)
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Asset compilation
npm run dev          # Single build for development
npm run watch        # Watch mode
npm run production   # Minified production build

# Tests
php artisan test
./vendor/bin/phpunit
./vendor/bin/phpunit --filter TestName   # Single test
```

## Architecture

### Request Flow
HTTP Request → Apache (XAMPP) → `public/index.php` → `bootstrap/app.php` → `Http/Kernel` → Routes (`routes/web.php`) → Controllers → Models → Views (Blade)

### Database: `bdsiialmacen` (MySQL, root/root local)
Core entity relationships:
- `productos` ← `categoriaproductos` (category FK)
- `entradas` ↔ `productos` via `entrada_productos` (junction table)
- `salidas` ↔ `productos` via `salidaproductos` (junction table)
- `kardexes` logs every stock movement with `tipomovimiento`, `id_movimiento`, `cantidad`
- Both `users` (Laravel auth) and `usuarios` (custom app users) tables exist

### Key Controllers
| Controller | Responsibility |
|---|---|
| `SalidaController` | Stock exits/sales — largest controller (18KB) |
| `EntradaController` | Stock entries/purchases |
| `ProductoController` | Product CRUD + status management |
| `InventarioController` | Inventory reports + PDF generation (uses DomPDF) |
| `KardexController` | Stock ledger — tracks all movements |
| `CancelacionController` | Order cancellations / returns |

### Frontend Stack
- **Blade** templates for all views, organized under `resources/views/` by domain (entradas, salidas, producto, inventario, medico, usuarios)
- **Vue 2** for reactive components (loaded via `resources/js/app.js`)
- **Livewire 2** for reactive server-side components — components live in `app/Http/Livewire/`
- **Bootstrap 5** for styling
- Assets compiled via Laravel Mix (`webpack.mix.js`) → outputs to `public/css/` and `public/js/`

### PDF Generation
Reports (entradas, salidas, inventory) are generated with `barryvdh/laravel-dompdf`. PDF views are Blade templates returned via `PDF::loadView()` in the controllers.

### Authentication
Uses Laravel's built-in auth + Sanctum for API tokens. Users have a `tipo_usuario` field for role-based access. The `app/Http/Middleware/` directory contains custom middleware for auth checks.

### Helpers
`app/Helpers/Convertidor.php` — utility functions available globally (number-to-words conversion, etc.).
