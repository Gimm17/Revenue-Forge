#syntax=docker/dockerfile:1.4
FROM serversideup/php:8.4-fpm-nginx

# Switch to root to install dependencies
USER root

# Install Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Switch back to the non-root user (sail/www-data)
USER www-data

# Copy composer files and install PHP dependencies first (better caching)
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copy package.json and install Node dependencies
COPY --chown=www-data:www-data package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application
COPY --chown=www-data:www-data . .

# Build the frontend assets
RUN npm run build

# Run composer scripts now that application code is present (e.g., post-autoload-dump)
RUN composer run-script post-autoload-dump

# Optimize Laravel (Use dummy DB_CONNECTION to prevent SQLite missing database error during build)
ENV DB_CONNECTION=mysql
RUN php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Railway expects apps to bind to PORT env variable if dynamic, but ServerSideUp image uses 8080 by default
ENV PORT=8080

EXPOSE 8080
