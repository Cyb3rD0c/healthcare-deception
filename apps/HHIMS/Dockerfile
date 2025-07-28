# Use an official PHP image as the base image
FROM local/php-seeder-base:latest

# Install necessary PHP extensions and other dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy the HHIMS application source code to the container
COPY . /var/www/html

# Create necessary directories and set permissions for the web server
RUN mkdir -p /var/www/html/application/config /var/www/html/logs && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copy the custom Apache configuration
COPY hhims.conf /etc/apache2/sites-available/000-default.conf

# Enable the custom configuration
RUN a2ensite 000-default

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
