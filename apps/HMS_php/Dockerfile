# Use the official PHP image with Apache
FROM local/php-seeder-base:latest

# Install necessary packages
RUN apt-get update && apt-get install -y \
    wget \
    nano \
    gnupg \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Wazuh agent
#RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
 #   echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
  #  apt-get update && apt-get install -y wazuh-agent

# Copy Wazuh configuration
#COPY ossec.conf /var/ossec/etc/ossec.conf

# Copy application files to the web directory
COPY . /var/www/html/

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy Apache configuration file
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Copy and set permissions for the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set the entrypoint
ENTRYPOINT ["/start.sh"]
