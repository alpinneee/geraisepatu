# KickVerse

A modern e-commerce application built with **Laravel** for selling shoes online. This application provides a complete shopping experience with user authentication, product management, shopping cart, checkout process, and order management.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database](#database)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

- **User Authentication** - Register, login, and user account management
- **Product Management** - Browse products with filters and search
- **Product Details** - View detailed product information with images and reviews
- **Shopping Cart** - Add/remove items, manage quantities, persistent cart storage
- **Wishlist** - Save favorite products for later
- **Checkout Process** - Secure checkout with multiple payment methods
- **Payment Integration** - Midtrans payment gateway integration
- **Order Management** - View order history, order status tracking
- **Shipping** - Multiple shipping zones and delivery tracking
- **Reviews & Ratings** - User reviews and product ratings
- **Email Notifications** - Order confirmations, payment status, delivery updates
- **Admin Dashboard** - Manage products, orders, and users
- **Role-Based Access Control** - Admin, seller, and customer roles
- **Google OAuth** - Social login integration

## 🛠 Tech Stack

### Backend
- **PHP 8.2+** - Server-side language
- **Laravel 12.0** - Web framework
- **MySQL** - Database
- **Laravel Sanctum** - API authentication
- **Laravel Socialite** - OAuth integration
- **Midtrans PHP SDK** - Payment gateway
- **Spatie Laravel Permission** - Role & permission management
- **DomPDF** - PDF generation for invoices
- **Resend** - Email service

### Frontend
- **Vue.js / Alpine.js** - Frontend framework
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool
- **Axios** - HTTP client

## 📁 Project Structure

```
geraisepatu/
├── app/                          # Application core logic
│   ├── Console/                  # Artisan commands
│   ├── Facades/                  # Facade definitions
│   │   └── Cart.php             # Cart facade
│   ├── Http/                     # HTTP handling
│   │   ├── Controllers/         # Request handlers
│   │   ├── Kernel.php           # HTTP kernel
│   │   ├── Middleware/          # HTTP middleware
│   │   └── Requests/            # Form requests
│   ├── Listeners/               # Event listeners
│   │   └── MergeGuestCart.php   # Merge guest cart on login
│   ├── Mail/                    # Mailable classes
│   │   ├── CheckoutSuccessMail.php
│   │   ├── ContactFormMail.php
│   │   ├── OrderDeliveredMail.php
│   │   ├── PaymentFailedMail.php
│   │   ├── PaymentSuccessMail.php
│   │   └── CustomResendTransport.php
│   ├── Models/                  # Eloquent models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── ProductImage.php
│   │   ├── ProductSize.php
│   │   ├── Category.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Review.php
│   │   ├── Wishlist.php
│   │   ├── Banner.php
│   │   ├── Contact.php
│   │   ├── ShippingAddress.php
│   │   ├── ShippingZone.php
│   │   ├── ActivityLog.php
│   │   └── UserSession.php
│   ├── Providers/               # Service providers
│   ├── Services/                # Business logic services
│   │   ├── CartService.php
│   │   └── (other services)
│   └── View/                    # View composers
│
├── bootstrap/                    # Application bootstrap
│   ├── app.php                  # Bootstrap the application
│   ├── providers.php            # Service provider arrangement
│   └── cache/                   # Bootstrap cache
│
├── config/                       # Configuration files
│   ├── app.php                  # Application configuration
│   ├── auth.php                 # Authentication
│   ├── cache.php                # Caching
│   ├── database.php             # Database connection
│   ├── filesystems.php          # File storage
│   ├── logging.php              # Logging
│   ├── mail.php                 # Email configuration
│   ├── midtrans.php             # Midtrans payment config
│   ├── permission.php           # Spatie permission config
│   ├── queue.php                # Job queue
│   ├── resend.php               # Resend email config
│   ├── sanctum.php              # API token authentication
│   ├── services.php             # Third-party services
│   └── session.php              # Session configuration
│
├── database/                     # Database layer
│   ├── factories/               # Model factories for testing
│   ├── migrations/              # Database schema migrations
│   └── seeders/                 # Database seeders
│
├── public/                       # Web accessible files
│   ├── index.php                # Application entry point
│   ├── robots.txt               # SEO robots file
│   └── build/                   # Compiled assets
│
├── resources/                    # Frontend resources
│   ├── css/                     # CSS files
│   ├── js/                      # JavaScript files
│   └── views/                   # Blade templates
│
├── routes/                       # Route definitions
│   ├── web.php                  # Web routes
│   ├── api.php                  # API routes
│   ├── auth.php                 # Authentication routes
│   ├── console.php              # Console commands
│   └── (debug/test routes)
│
├── storage/                      # Application storage
│   ├── app/                     # Application files
│   ├── framework/               # Framework files
│   ├── logs/                    # Application logs
│   └── debugbar/                # Debug bar data
│
├── tests/                        # Test suite
│   ├── Feature/                 # Feature tests
│   ├── Unit/                    # Unit tests
│   └── TestCase.php             # Base test case
│
├── vendor/                       # Composer dependencies
│
├── .env                         # Environment variables (local)
├── .env.example                 # Environment template
├── artisan                      # Laravel CLI
├── composer.json                # PHP dependencies
├── package.json                 # Node dependencies
├── vite.config.js               # Vite configuration
├── tailwind.config.js           # Tailwind configuration
├── postcss.config.js            # PostCSS configuration
├── phpunit.xml                  # PHPUnit configuration
│
└── deploy/                       # Deployment scripts
    ├── deploy.sh
    ├── deploy-without-composer.php
    ├── Dockerfile
    ├── vercel.json
    └── (other deployment configs)
```

## 📋 Prerequisites

- **PHP** >= 8.2
- **Composer** - PHP package manager
- **Node.js** >= 16 & **npm** - JavaScript package manager
- **MySQL** >= 5.7
- **Git** - Version control

## 🚀 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd geraisepatu
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Frontend Assets
```bash
npm run build
```

## ⚙️ Configuration

### Environment Variables (.env)
Key configurations needed:

```env
# App Configuration
APP_NAME=KickVerse
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_commerce_toko_sepatu
DB_USERNAME=root
DB_PASSWORD=

# Mail Service
MAIL_DRIVER=resend
RESEND_API_KEY=your_resend_api_key

# Midtrans Payment
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_ENVIRONMENT=sandbox

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

### Database Configuration
Edit `config/database.php` and `.env` for MySQL connection details.

### Mail Configuration
The application uses **Resend** for email services. Configure in `config/mail.php` and `.env`.

### Payment Gateway
Midtrans is configured in `config/midtrans.php`. Set up your merchant credentials in `.env`.

## 🗄️ Database

### Run Migrations
```bash
php artisan migrate
```

### Seed Database
```bash
php artisan db:seed
```

### Rollback Migrations
```bash
php artisan migrate:rollback
```

## ▶️ Running the Application

### Development Server
```bash
php artisan serve
```
Access at: `http://localhost:8000`

### Frontend Development
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

### Windows Quick Start
```bash
serve.bat
```

## 📚 API Documentation

API endpoints are defined in:
- `routes/api.php` - Main API routes
- `routes/auth.php` - Authentication routes

### Authentication
The application uses **Laravel Sanctum** for API token authentication.

### Key Endpoints
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/products` - List products
- `POST /api/cart/add` - Add to cart
- `POST /api/checkout` - Create order
- `GET /api/orders` - User orders

## 🧪 Testing

### Run PHPUnit Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/YourTest.php
```

## 🤝 Contributing

1. Create a new branch: `git checkout -b feature/your-feature`
2. Make changes and commit: `git commit -m 'Add feature'`
3. Push to branch: `git push origin feature/your-feature`
4. Submit a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

**Built with ❤️ for shoe enthusiasts**
