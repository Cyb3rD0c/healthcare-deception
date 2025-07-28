FROM local/php-seeder-base:latest

# Install SQLite3 and PHP extensions
RUN apt-get update && \
    apt-get install -y sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo pdo_sqlite

# Create db directory
RUN mkdir -p /var/www/html/db

# Set permissions
RUN chown -R www-data:www-data /var/www/html/db
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the custom configuration
RUN a2ensite 000-default

# Copy application source code to the container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
