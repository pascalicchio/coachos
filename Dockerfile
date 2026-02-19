FROM php:8.2-cli

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y git curl libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy app
COPY . .

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install Laravel
RUN composer install --no-scripts --no-dev --no-interaction

# Create startup script
RUN echo '#!/bin/bash\nphp artisan migrate:fresh --force\nphp -S 0.0.0.0:8080 -t public' > /start.sh && chmod +x /start.sh

# Expose
EXPOSE 8080

# Run
CMD ["/start.sh"]
