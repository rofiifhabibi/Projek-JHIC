#!/bin/sh

# 1. Salin .env dari .env.example jika belum ada
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    
    # Otomatis sesuaikan isi .env lokal dengan konfigurasi database docker
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/g' .env
    sed -i .s/.*DB_HOST=.*
    sed -i .s/.*DB_PORT=.*
    sed -i .s/.*DB_DATABASE=.*
    sed -i .s/.*DB_USERNAME=.*
    sed -i .s/.*DB_PASSWORD=.*
fi

# 2. Jalankan composer install jika autoload.php belum ada
if [ ! -f vendor/autoload.php ]; then
    echo "Installing PHP dependencies (Composer)..."
    composer install --no-interaction --optimize-autoloader
fi

# 3. Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating Laravel Application Key..."
    php artisan key:generate --force
fi

# 4. Tunggu koneksi database siap
echo "Waiting for database connection to be ready..."
until php -r "try { new PDO('mysql:host=db;port=3306;dbname=laravel', 'laravel_user', 'laravel_password'); exit(0); } catch (Exception \$e) { exit(1); }"; do
    echo "Database not ready yet, retrying in 2 seconds..."
    sleep 2
done

# 5. Jalankan migrasi & seeding database
echo "Running database migrations and seeders..."
php artisan migrate:fresh --seed --force

# 6. Berikan hak akses (permissions) yang benar untuk Apache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 7. Jalankan Apache di foreground
echo "Starting Apache Web Server..."
exec apache2-foreground