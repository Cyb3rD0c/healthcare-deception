#!/usr/bin/env python3
import os, random
import pymysql
from faker import Faker
from faker.providers import BaseProvider

# --- Custom provider for ICD-10 & NDC codes ---
class MedicalProvider(BaseProvider):
    ICD10_CODES = [
        "A00", "B20", "C34.1", "E11.9", "I10", "J45.909", 
        # …add the set you need…
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
    return pymysql.connect(
        host     = os.environ["DB_HOST"],
        port     = int(os.environ.get("DB_PORT", 3306)),
        user     = os.environ["DB_USER"],
        password = os.environ["DB_PASS"],
        database = os.environ["DB_NAME"],
        cursorclass = pymysql.cursors.DictCursor
    )

def introspect(conn, table):
    with conn.cursor() as c:
        c.execute(f"DESCRIBE `{table}`;")
        return c.fetchall()

def seed_table(conn, table, fake, rows):
    cols = introspect(conn, table)
    fields = [c["Field"] for c in cols if "auto_increment" not in c["Extra"]]
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
    rows = int(os.environ.get("SEED_ROWS", 20))
    with conn:
        # get your tables
        tables = [r[f"Tables_in_{os.environ['DB_NAME']}"]
                  for r in conn.cursor().execute("SHOW TABLES;") or conn.cursor().fetchall()]
        print(f"Seeding {len(tables)} tables with {rows} rows each…")
        for t in tables:
            seed_table(conn, t, fake, rows)
    conn.close()

if __name__ == "__main__":
    main()

