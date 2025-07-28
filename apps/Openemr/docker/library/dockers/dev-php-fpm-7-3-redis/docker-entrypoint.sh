#!/bin/sh
set -e

# 1) Show us what env vars the seeder will see:
echo "🔧 ENV: DB_HOST=$DB_HOST  DB_USER=$DB_USER  DB_PASS=$DB_PASS  DB_NAME=$DB_NAME"

# 2) Wait for MariaDB
echo "⏳ Waiting for DB at $DB_HOST:$DB_PORT…"
for i in $(seq 1 15); do
  if mysqladmin ping -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent; then
    break
  fi
  echo "… still waiting ($i/15)"
  sleep 2
done

# 3) Run the seeder
echo "🧪 Running Faker seeder…"
python3 /usr/local/bin/seeder.py

# 4) Launch PHP-FPM (not Apache)
echo "🚀 Starting PHP-FPM…"
exec docker-php-entrypoint php-fpm
