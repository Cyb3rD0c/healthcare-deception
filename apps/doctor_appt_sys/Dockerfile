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
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Install Wazuh agent
#RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - && \
 #   echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list && \
  #  apt-get update && apt-get install -y wazuh-agent

# Copy Wazuh configuration
#COPY ossec.conf /var/ossec/etc/ossec.conf

# Copy application files
COPY . /var/www/html/
# Copy the custom Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the custom configuration
RUN a2ensite 000-default
# Install PHP dependencies
RUN cd /var/www/html && composer install

# Copy and set permissions for the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set the entrypoint
ENTRYPOINT ["/start.sh"]
