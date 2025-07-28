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
    cron

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application files to the working directory
COPY . /var/www/html

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install Wazuh agent
RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
    echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
    apt-get update && \
    apt-get install -y wazuh-agent

# Copy the Wazuh agent config
COPY ossec.conf /var/ossec/etc/ossec.conf

# Copy the log collection script
COPY log_collector.py /usr/local/bin/log_collector.py
RUN chmod +x /usr/local/bin/log_collector.py

# Copy start.sh script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Copy crontab file
COPY crontab /etc/cron.d/log_collector_cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/log_collector_cron

# Apply cron job
RUN crontab /etc/cron.d/log_collector_cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Expose port 80
EXPOSE 80

# Start the container with the custom script
CMD ["/start.sh"]
