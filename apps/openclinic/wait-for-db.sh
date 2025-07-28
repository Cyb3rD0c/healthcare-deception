#!/bin/sh

# Wait for the database to be ready
until php -r "new mysqli('$1', '$2', '$3', '$4', '$5');"; do
  >&2 echo "MariaDB is unavailable - sleeping"
  sleep 1
done

# Execute the main container command
exec "$6"
