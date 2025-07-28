FROM local/php-seeder-base:latest

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli

# Copy the application code to the container
COPY hospital /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy custom Apache configuration file
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
