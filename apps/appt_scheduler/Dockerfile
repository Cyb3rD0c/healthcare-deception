# Use the official PHP image with Apache
FROM local/php-seeder-base:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    wget \
    nano \
    gnupg \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    python3 \
    python3-pip \
    cron \
    default-mysql-client  # Add this line to install MySQL client

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli

# Set the working directory
WORKDIR /var/www/html

# Copy the source code to the working directory
COPY . .
# Copy the custom Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the custom configuration
RUN a2ensite 000-default
# Set the correct permissions for the web files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose the port
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
