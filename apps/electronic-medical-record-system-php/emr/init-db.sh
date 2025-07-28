#!/bin/bash
set -e

# Wait for MariaDB to be ready
until mysql -h emr-db -u root -pyourpassword -e "SHOW DATABASES;"; do
  >&2 echo "MariaDB is unavailable - sleeping"
  sleep 1
done

# Import the SQL file
mysql -h emr-db -u root -pyourpassword Clinic < /var/www/html/sql/clinic.sql

echo "Database initialization complete."
