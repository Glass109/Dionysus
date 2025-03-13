FROM php:8.2-cli

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    libmcrypt-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

# Install PHP dependencies
RUN composer install

# Install NPM dependencies and build assets
RUN npm install && npm run build

CMD php artisan serve --host=0.0.0.0 --port=8000
