#!/usr/bin/env python3
import os
import pymysql
from faker import Faker
from faker.providers import BaseProvider

# --- Custom provider for ICD-10 & NDC codes ---
class MedicalProvider(BaseProvider):
    ICD10_CODES = [
        "A00", "B20", "C34.1", "E11.9", "I10", "J45.909",
        # …add more as needed…
    ]
    NDC_CODES = [
        "0002-0800-01", "0003-1234-05", "0004-5678-90",
        # …your realistic list…
    ]
    def icd10(self):
        return self.random_element(self.ICD10_CODES)
    def ndc(self):
        return self.random_element(self.NDC_CODES)

def get_conn():
    """Connect using the env vars set by your Docker-Compose per-app."""
    host     = os.getenv("DB_HOST",   "localhost")
    port     = int(os.getenv("DB_PORT",   3306))
    user     = os.getenv("DB_USER",   "root")
    password = os.getenv("DB_PASS",   "")
    database = os.getenv("DB_NAME",   None)

    return pymysql.connect(
        host        = host,
        port        = port,
        user        = user,
        password    = password,
        database    = database,
        cursorclass = pymysql.cursors.DictCursor
    )

def introspect(conn, table):
    """Return the DESCRIBE output for a given table."""
    with conn.cursor() as c:
        c.execute(f"DESCRIBE `{table}`;")
        return c.fetchall()

def seed_table(conn, table, fake, rows):
    cols = introspect(conn, table)
    # skip auto-incs
    fields = [c["Field"] for c in cols if "auto_increment" not in c["Extra"]]

    # pick a provider per field
    providers = []
    for c in cols:
        name = c["Field"].lower()
        typ  = c["Type"]
        if name.endswith(("_id", "id")) and "int" in typ:
            providers.append(lambda: None)
        elif "date" in name:
            providers.append(fake.date)
        elif "email" in name:
            providers.append(fake.email)
        elif "phone" in name:
            providers.append(fake.phone_number)
        elif "icd" in name:
            providers.append(fake.icd10)
        elif "ndc" in name:
            providers.append(fake.ndc)
        else:
            providers.append(fake.word)

    placeholders = ", ".join(["%s"] * len(fields))
    sql = f"INSERT INTO `{table}` ({','.join(fields)}) VALUES ({placeholders})"

    with conn.cursor() as c:
        for _ in range(rows):
            vals = [p() for p in providers]
            c.execute(sql, vals)
    conn.commit()
    print(f"  → {table}: {rows} rows")

def main():
    fake = Faker()
    fake.add_provider(MedicalProvider)

    conn = get_conn()
    rows = int(os.getenv("SEED_ROWS", 20))

    # fetch tables the right way
    with conn.cursor() as cursor:
        cursor.execute("SHOW TABLES;")
        result = cursor.fetchall()

    # extract the single column name dynamically
    tables = [ list(r.values())[0] for r in result ]
    print(f"Seeding {len(tables)} tables with {rows} rows each…")

    for t in tables:
        seed_table(conn, t, fake, rows)

    conn.close()

if __name__ == "__main__":
    main()
