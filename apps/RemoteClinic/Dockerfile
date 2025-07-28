FROM local/php-seeder-base:latest

# Install required PHP extensions and utilities
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    wget \
    gnupg \
    cron \
    python3 \
    python3-pip \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Wazuh agent
RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
    echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
    apt-get update && apt-get install -y wazuh-agent

# Copy Wazuh configuration
COPY ossec.conf /var/ossec/etc/ossec.conf

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Create required directories
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Apache config file
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy Python log collector script
COPY log_collector.py /usr/local/bin/log_collector.py
RUN chmod +x /usr/local/bin/log_collector.py

# Copy and set permissions for the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set up cron job for log collection
RUN echo "*/15 * * * * python3 /usr/local/bin/log_collector.py >> /var/log/cron.log 2>&1" > /etc/cron.d/log_collector \
    && chmod 0644 /etc/cron.d/log_collector \
    && crontab /etc/cron.d/log_collector

# Ensure cron and start script are executable
RUN chmod +x /start.sh

# Start cron and the application
CMD ["sh", "-c", "service cron start && /start.sh"]

EXPOSE 80
