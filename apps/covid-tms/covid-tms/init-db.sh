#!/bin/bash
set -e

# Wait for MariaDB to be ready
until mysql -h covidtms-db -u root -pyourpassword -e "SHOW DATABASES;"; do
  >&2 echo "MariaDB is unavailable - sleeping"
  sleep 1
done

# Import the SQL file
mysql -h covidtms-db -u root -pyourpassword covidtmsdb < /var/www/html/SQL/covidtmsdb.sql

echo "Database initialization complete."
