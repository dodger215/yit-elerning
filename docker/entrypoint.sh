#!/bin/sh

# Wait for database to be ready if needed (optional but recommended)
# sleep 10 

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8000
