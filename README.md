# Food and Beverage Management System

A Laravel-based web application for managing food and beverage inventory, orders, and business operations.

## Features

- Product catalog management
- Order processing system  
- Inventory tracking
- User authentication and authorization
- Real-time notifications with Livewire
- MongoDB integration for flexible data storage
- Responsive design with Tailwind CSS

## Technology Stack

- **Backend**: Laravel 12.0, PHP 8.2+
- **Frontend**: Livewire 3.6, Tailwind CSS 3.4
- **Database**: MongoDB with Laravel MongoDB package
- **Authentication**: Laravel Jetstream with Sanctum
- **Build Tools**: Vite 7.0, PostCSS, Autoprefixer

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MongoDB

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd food-and-beverage
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Set up environment configuration:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database settings in `.env` file

6. Run database migrations:
```bash
php artisan migrate
```

7. Build frontend assets:
```bash
npm run build
```

## Development

To start the development environment:

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev
```

## Testing

Run the test suite:

```bash
php artisan test
```

## Deployment

The application includes configuration for Railway deployment. See the deployment scripts:
- `deploy.sh` - Main deployment script
- `railway-build.sh` - Railway-specific build script

## License

This project is licensed under the MIT License.