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
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Wazuh agent
#RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
 #   echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
  #  apt-get update && apt-get install -y wazuh-agent

# Copy Wazuh configuration
#COPY source/ossec.conf /var/ossec/etc/ossec.conf

# Copy source code to the container's web directory
COPY source/ /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Copy Apache configuration
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy and set permissions for the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set the entrypoint
ENTRYPOINT ["/start.sh"]

# Expose port 80
EXPOSE 80
