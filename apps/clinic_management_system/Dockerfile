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
    ca-certificates

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application files to the working directory
COPY sourcecode/ .

# Set the correct permissions for the Composer
RUN chown -R www-data:www-data /var/www/html

# Copy custom Apache configuration
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Enable the site configuration
RUN a2ensite 000-default

# Install Wazuh agent
#RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - \
 #   && echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee /etc/apt/sources.list.d/wazuh.list \
  #  && apt-get update \
   # && apt-get install wazuh-agent -y

# Copy Wazuh configuration file
#COPY ossec.conf /var/ossec/etc/ossec.conf

# Start Wazuh agent
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start Apache and Wazuh agent
CMD ["/start.sh"]
