FROM local/php-seeder-base:7.2-apache
LABEL maintainer="LibreHealth Infrastructure Team <infrastructure@librehealth.io>"

VOLUME /var/www/html/sites

COPY ./docker/docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

# Install system dependencies and Wazuh agent
RUN set -ex; \
    apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    wget \
    nano \
    gnupg \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    mariadb-client \
    libjpeg-dev \
    libpng-dev \
    && wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add - \
    && echo "deb https://packages.wazuh.com/4.x/apt/ stable main" | tee -a /etc/apt/sources.list.d/wazuh.list \
    && apt-get update \
    && apt-get install -y wazuh-agent \
    && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
    && docker-php-ext-install gd mysqli opcache zip \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/*

# Set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Installing latest LibreHealth EHR (master)
RUN set -ex; rm -fr /var/www/html
RUN a2enmod rewrite expires

COPY --chown=www-data:www-data . /var/www/html

# Copy Wazuh agent configuration file
COPY ossec.conf /var/ossec/etc/ossec.conf

# Ensure start.sh is executable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start the container with the custom script
CMD ["/start.sh"]
