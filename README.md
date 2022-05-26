# Job Portal API

Backend Test Laravel

## Requirements

### PHP
- Recomended PHP version ^7.3 || ^8.0

### Composer
- Recomended Composer version ^2.0

### Database
- MySQL

## Installation & Update

Install php dependencies
``` bash
composer install
```

Make .env
```bash
cp .env.example .env
```

Generate app key
```bash
php artisan key:generate
```

Configure DB on .env
```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations
```bash
php artisan migrate
```

Create symbolic link from public/storage to storage/app/public
```bash
php artisan storage:link
```

Run Seeder
```bash
php artisan db:seed --ClassSeeder
```

### For development purpose (Optional)

Run fresh migrations with seeds
```bash
php artisan migrate:fresh --seed
```

### This step is required in every deployment

Run migrations
```bash
php artisan migrate
```

Sync versioning on .env
```bash
APP_VERSION=x.x.x
```

Clear/optimize system
```bash
php artisan optimize:clear
```

## Run Server

Run server
```bash
php artisan serve

# Configuration 

Role : Company, Candidate
Status Job : Draft, Open, Close
Status Candidate : Review, Interview, Assessment, Accepted To Company, Rejected

# Documentation Rest API Postman

https://documenter.getpostman.com/view/7017766/Uz5ArJnh
