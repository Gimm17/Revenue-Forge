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

# Optimize Laravel (Use dummy env vars to prevent missing database error during build)
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=:memory:
ENV CACHE_STORE=file
ENV SESSION_DRIVER=file
RUN php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Auto-run Migrations & Seeders during boot
ENV AUTORUN_LARAVEL_MIGRATION=true
ENV AUTORUN_LARAVEL_SEED=true

# Setup ServerSideUp to listen to Railway's dynamic PORT variable, or fallback to 8080
ENV PORT=${PORT:-8080}
