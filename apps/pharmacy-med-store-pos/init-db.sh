#!/bin/bash
set -e

# Wait for MariaDB to be ready
until mysql -h medical-db -u root -pyourpassword -e "SHOW DATABASES;"; do
  >&2 echo "MariaDB is unavailable - sleeping"
  sleep 1
done

# Import the SQL file
mysql -h medical-db -u root -pyourpassword medical < /var/www/html/assets/store.sql

echo "Database initialization complete."
