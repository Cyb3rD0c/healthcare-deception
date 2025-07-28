#!/usr/bin/env python3
import os
import re
import json
import random
import pymysql
from faker import Faker
from faker.providers import BaseProvider

# ── Custom ICD-10 / NDC codes ─────────────────────────────────────────────────
class MedicalProvider(BaseProvider):
    ICD10_CODES = ["A00","B20","C34.1","E11.9","I10","J45.909"]
    NDC_CODES   = ["0002-0800-01","0003-1234-05","0004-5678-90"]
    def icd10(self): return self.random_element(self.ICD10_CODES)
    def ndc(self):   return self.random_element(self.NDC_CODES)

# ── Get a pymysql connection from ENV ─────────────────────────────────────────
def get_conn():
    return pymysql.connect(
        host        = os.environ["DB_HOST"],
        port        = int(os.environ.get("DB_PORT", 3306)),
        user        = os.environ["DB_USER"],
        password    = os.environ["DB_PASS"],
        database    = os.environ["DB_NAME"],
        cursorclass = pymysql.cursors.DictCursor
    )

# ── DESCRIBE a table ──────────────────────────────────────────────────────────
def introspect(conn, table):
    with conn.cursor() as c:
        c.execute(f"DESCRIBE `{table}`")
        return c.fetchall()

# ── Pull ENUM options out of "enum('x','y')" ────────────────────────────────────
def parse_enum(typ):
    return re.findall(r"'([^']*)'", typ)

# ── Pick the right fake for each column ────────────────────────────────────────
def choose_provider(col, fake):
    name = col["Field"].lower()
    typ  = col["Type"].lower()

    # 1) Skip AUTO_INCREMENT columns entirely
    if "auto_increment" in col["Extra"]:
        return None

    # 2) ENUM → random choice
    if typ.startswith("enum"):
        opts = parse_enum(typ)
        return lambda: random.choice(opts) if opts else None

    # 3) Integers → small random int
    if any(t in typ for t in ("tinyint","smallint","mediumint","int","bigint")):
        return lambda: random.randint(1, 1000)

    # 4) Decimals/floats → random float
    if any(t in typ for t in ("decimal","float","double")):
        return lambda: round(random.uniform(0, 10000), 2)

    # 5) JSON → empty object
    if "json" in typ:
        return lambda: json.dumps({})

    # 6) Dates & times
    if typ.startswith("date"):
        return fake.date
    if typ.startswith("time"):
        return fake.time
    if any(t in typ for t in ("datetime","timestamp")):
        return fake.date_time
    if typ.startswith("year"):
        return fake.year

    # 7) UUID / GUID
    if "char(" in typ and ("uuid" in name or "guid" in name):
        return fake.uuid4

    # 8) By field name
    if "email" in name:
        return fake.email
    if "phone" in name or "mobile" in name:
        return fake.phone_number
    if "first_name" in name or name=="firstname":
        return fake.first_name
    if "last_name" in name or name=="lastname":
        return fake.last_name
    if name=="name" or "full_name" in name:
        return fake.name
    if "address" in name or "street" in name:
        return fake.address
    if "city" in name:
        return fake.city
    if "state" in name:
        return fake.state
    if "zip" in name or "postal" in name:
        return fake.postcode
    if "country" in name:
        return fake.country
    if "gender" in name:
        return lambda: random.choice(["M","F"])
    if "ssn" in name:
        return fake.ssn
    if "dob" in name or "birth" in name:
        return fake.date_of_birth
    if "url" in name:
        return fake.url
    if "ip" in name:
        return fake.ipv4
    if "note" in name or "desc" in name or "text" in name:
        return fake.text

    # 9) Fall back to a single word
    return fake.word

# ── Seed one table with `rows` rows ────────────────────────────────────────────
def seed_table(conn, table, fake, rows):
    cols = introspect(conn, table)

    # build lists of fields & their providers
    fields, providers = [], []
    for col in cols:
        p = choose_provider(col, fake)
        if p is not None:
            fields.append(col["Field"])
            providers.append(p)

    if not fields:
        print(f"  → {table}: no seedable columns, skipping")
        return

    placeholder = ", ".join("%s" for _ in fields)
    sql = f"INSERT IGNORE INTO `{table}` ({', '.join(fields)}) VALUES ({placeholder})"

    # *** here is the corrected block ***
    with conn.cursor() as c:
        for _ in range(rows):
            vals = [prov() for prov in providers]
            try:
                c.execute(sql, vals)
            except pymysql.err.IntegrityError as e:
                # silently skip dup‐key or NOT NULL violations
                if e.args[0] in (1062, 1048):
                    continue
                else:
                    raise
    # commit once per table
    conn.commit()
    print(f"  → {table}: {rows} rows")

# ── Entrypoint ─────────────────────────────────────────────────────────────────
def main():
    fake = Faker()
    fake.unique.clear()
    fake.add_provider(MedicalProvider)

    conn = get_conn()
    rows = int(os.environ.get("SEED_ROWS", 20))

    # fetch your tables
    with conn.cursor() as c:
        c.execute("SHOW TABLES")
        tables = [r[f"Tables_in_{os.environ['DB_NAME']}"] for r in c.fetchall()]

    print(f"Seeding {len(tables)} tables with {rows} rows each…")
    for t in tables:
        seed_table(conn, t, fake, rows)

    conn.close()

if __name__ == "__main__":
    main()
