FROM php:8.1-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install zip xml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.* ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy source code
COPY . .

# Install dev dependencies for testing
RUN composer install

# Set default command
CMD ["vendor/bin/phpunit", "Test/Unit/"]
