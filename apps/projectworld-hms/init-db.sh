#!/bin/bash
mysql -u root -p${MYSQL_ROOT_PASSWORD} -h hms-db <<EOF
CREATE DATABASE IF NOT EXISTS hospitaldb;
USE hospitaldb;
SOURCE /docker-entrypoint-initdb.d/SQL/hospitaldb.sql;
EOF
