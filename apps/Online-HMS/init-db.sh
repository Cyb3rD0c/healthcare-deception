#!/bin/bash
set -e

# Wait for MariaDB to be ready
until mysql -h hospital-db -u root -pyourpassword -e "SHOW DATABASES;"; do
  >&2 echo "MariaDB is unavailable - sleeping"
  sleep 1
done

# Import the SQL file
mysql -h hospital-db -u root -pyourpassword onlinehospitalmanagementsystem < /var/www/html/backup/onlinehospitalmanagementsystem.sql

echo "Database initialization complete."
