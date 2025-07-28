cd install/sql
echo 'source /docker-entrypoint-initdb.d/access_log_tbl.sql;' > init.sql
echo 'source /docker-entrypoint-initdb.d/deleted_patient_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/history_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/medical_test_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/problem_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/relative_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/setting_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/theme_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/connection_problem_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/deleted_problem_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/patient_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/record_log_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/session_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/staff_tbl.sql;' >> init.sql
echo 'source /docker-entrypoint-initdb.d/user_tbl.sql;' >> init.sql
