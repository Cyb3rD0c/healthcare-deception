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
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files to the web directory
COPY source/ /var/www/html/

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy Apache configuration file
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Wazuh agent
#RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
#    echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
#    apt-get update && \
#    apt-get install -y wazuh-agent

# Copy the Wazuh agent config
#COPY ossec.conf /var/ossec/etc/ossec.conf

# Ensure start.sh is executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start the container with the custom script
CMD ["/start.sh"]
