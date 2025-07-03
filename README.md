# Laravel + Livewire Starter Kit

## Introduction

Our Laravel + [Livewire](https://livewire.laravel.com) starter kit provides a robust, modern starting point for building Laravel applications with a Livewire frontend.

Livewire is a powerful way of building dynamic, reactive, frontend UIs using just PHP. It's a great fit for teams that primarily use Blade templates and are looking for a simpler alternative to JavaScript-driven SPA frameworks like React and Vue.

This Livewire starter kit utilizes Livewire 3, Laravel Volt (optionally), TypeScript, Tailwind, and the [Flux UI](https://fluxui.dev) component library.

If you are looking for the alternate configurations of this starter kit, they can be found in the following branches:

- [components](https://github.com/laravel/livewire-starter-kit/tree/components) - if Volt is not selected
- [workos](https://github.com/laravel/livewire-starter-kit/tree/workos) - if WorkOS is selected for authentication

## Getting Started with Laravel Sail

Laravel Sail is a lightweight command-line interface for interacting with Laravel's default Docker development environment. It provides a great starting point for building a Laravel application using PHP, MySQL, Redis, and more.

### Prerequisites

Before you begin, ensure you have the following installed on your system:

- **Docker Desktop**: Download and install from [docker.com](https://www.docker.com/products/docker-desktop)
- **Git**: For cloning the repository
- **Composer**: PHP dependency manager (optional if using Sail)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd LujoWebsite
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.dev .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Start Laravel Sail**
   
   You can create an alias to avoid typing `./vendor/bin/` every time:
   ```bash
   alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
   ```
   
   Then simply run:
   ```bash 
   sail up -d
   ```
   ```
   
   This command will:
   - Build and start the Docker containers
   - Run in detached mode (`-d` flag)
   - Set up the database, Redis, and other services

6. **Run database migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

7. **Install and build frontend assets**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

8. **Access your application**
   
   Your Laravel application should now be running at:
   - **Main Application**: http://localhost
   - **PHPMyAdmin**: http://localhost:8080
   - **Database**: localhost:3306 (MySQL)
   - **Redis**: localhost:6379

### Common Sail Commands

Here are some useful commands for working with Laravel Sail:

```bash
# Start the application
./vendor/bin/sail up -d

# Stop the application
./vendor/bin/sail down

# View logs
./vendor/bin/sail logs

# Run Artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:controller ExampleController

# Run tests
./vendor/bin/sail test

# Access the application container
./vendor/bin/sail shell

# Run Composer commands
./vendor/bin/sail composer install
./vendor/bin/sail composer require package-name

# Run NPM commands
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
./vendor/bin/sail npm run build

# Access the database
./vendor/bin/sail mysql
```

### Environment Configuration

The `.env` file contains important configuration settings. Key settings to review:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Troubleshooting

**Port conflicts**: If you encounter port conflicts, you can specify custom ports:
```bash
./vendor/bin/sail up -d --build --force-recreate
```

**Permission issues**: On Linux/macOS, you might need to fix permissions:
```bash
sudo chown -R $USER:$USER .
```

**Container issues**: If containers aren't starting properly:
```bash
./vendor/bin/sail down
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

**Database connection issues**: Ensure the database service is running:
```bash
./vendor/bin/sail ps
```

### Next Steps

Once your application is running:

1. **Register a new user** at http://localhost/register
2. **Explore the dashboard** at http://localhost/dashboard
3. **Check out the settings** at http://localhost/settings/profile
4. **Review the authentication features** including email verification and password reset

### Development Workflow

For ongoing development:

1. **Start the environment**: `./vendor/bin/sail up -d`
2. **Make your changes** to the code
3. **Run tests**: `./vendor/bin/sail test`
4. **Build assets** (if needed): `./vendor/bin/sail npm run dev`
5. **Stop when done**: `./vendor/bin/sail down`

## Official Documentation

Documentation for all Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + Livewire starter kit is open-sourced software licensed under the MIT license.
