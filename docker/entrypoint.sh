#!/bin/sh

# Wait for database to be ready if needed (optional but recommended)
# sleep 10 

# Fix for local volume mapping shadowing the vendor/ and public/build directories built in Dockerfile
if [ ! -f "vendor/autoload.php" ]; then
    echo "vendor/autoload.php not found. Installing composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

if [ ! -d "public/build" ]; then
    echo "public/build not found. Installing NPM dependencies and building assets..."
    npm install
    npm run build
fi

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8000
