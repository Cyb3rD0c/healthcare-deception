# ── Start from the official Alpine-based OpenEMR 5.0.1 image
FROM openemr/openemr:5.0.1

LABEL maintainer="LibreHealth Infrastructure Team <infrastructure@librehealth.io>"

USER root

# ── 1) Install Python3 & pip via apk (Alpine package manager)
RUN apk update && \
    apk add --no-cache \
      python3 \
      py3-pip && \
    ln -sf /usr/bin/python3 /usr/bin/python

# ── 2) Install Faker and PyMySQL
RUN pip3 install Faker pymysql

# ── 3) Copy in your seeder script
COPY seeder.py /usr/local/bin/seeder.py
RUN chmod +x /usr/local/bin/seeder.py

# ── 4) Create a wrapper entrypoint that waits for MySQL, seeds, then
#       defers to the upstream docker-php-entrypoint + Apache
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
