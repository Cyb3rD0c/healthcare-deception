# Use the official PHP image with Apache
FROM local/php-seeder-base:latest

# Install required PHP extensions and necessary packages
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
    libzip-dev \
    cron \
    python3 \
    python3-pip

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

# Copy the source code to the /var/www/html directory
COPY source/ /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Copy the custom Apache configuration file
COPY apache-config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy Python log collector script
#COPY log_collector.py /usr/local/bin/log_collector.py
#RUN chmod +x /usr/local/bin/log_collector.py

# Copy and set permissions for the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set up cron job for log collection
#RUN echo "* * * * * python3 /usr/local/bin/log_collector.py >> /var/log/cron.log 2>&1" > /etc/cron.d/log_collector \
 #   && chmod 0644 /etc/cron.d/log_collector \
  #  && crontab /etc/cron.d/log_collector

# Ensure cron and start script are executable
RUN chmod +x /start.sh

# Start cron and the application
CMD apache2-foreground

# Expose port 80
EXPOSE 80
