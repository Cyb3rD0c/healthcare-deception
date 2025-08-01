#
# Copyright (C) 2018-2019 Brady Miller <brady.g.miller@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# php-fpm Dockerfile build for openemr development docker environment
# This docker is hosted here: https://hub.docker.com/r/openemr/dev-php-fpm/ <tag is 7.2>
#
FROM php:7.3-fpm
# ── Install Python3 + pip and our seeder deps ───────────────────────────────
RUN apt-get update && \
    apt-get install -y python3 python3-pip && \
    pip3 install Faker pymysql && \
    rm -rf /var/lib/apt/lists/*

# ── Copy the global seeder script into the image ────────────────────────────
COPY seeder.py /usr/local/bin/seeder.py
RUN chmod +x /usr/local/bin/seeder.py

# ── Copy in our new entrypoint wrapper ──────────────────────────────────────
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ── Override entrypoint to run seeder then PHP-FPM ──────────────────────────
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]








# Update
RUN apt-get update

# Add mariadb-client package that is needed in the OpenEMR Backup gui, which does direct command mysql commands
# Add imagemagick that is needed for some image processing in OpenEMR
# Note this basically add 160MB of space to the docker, so would be nice for OpenEMR to not require this stuff
#  and instead rely on php scripts, if possible.
RUN apt-get install -y mariadb-client \
                       imagemagick

# Add the php extensions (note using a very cool script by mlocati to do this)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_mysql \
                           ldap \
                           xsl \
                           gd \
                           zip \
                           soap \
                           gettext \
                           mysqli \
                           sockets \
                           tokenizer \
                           xmlreader \
                           calendar \
                           intl \
                           redis

# Copy over the php.ini conf
COPY php.ini /usr/local/etc/php/php.ini

# Needed to ensure permissions work across shared volumes with openemr, nginx, and php-fpm dockers
RUN usermod -u 1000 www-data
