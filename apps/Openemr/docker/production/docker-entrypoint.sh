cat << 'EOF' > apps/Openemr/docker/library/dockers/dev-php-fpm-7-3-redis/docker-entrypoint.sh
#!/bin/sh
set -e

echo "⏳ Waiting for DB at $DB_HOST:$DB_PORT…"
for i in $(seq 1 15); do
  if mysqladmin ping -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent; then
    break
  fi
  echo "… still waiting ($i/15)"
  sleep 2
done

echo "🧪 Running Faker seeder…"
python3 /usr/local/bin/seeder.py

echo "🚀 Starting PHP-FPM…"
exec docker-php-entrypoint php-fpm
EOF
chmod +x apps/Openemr/docker/library/dockers/dev-php-fpm-7-3-redis/docker-entrypoint.sh
