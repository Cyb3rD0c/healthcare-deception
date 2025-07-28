#!/usr/bin/env python3
import os
import sys
import textwrap

# ——— Configuration —————————————————————————————————————
APPS_DIR = os.path.abspath(os.path.join(__file__, "..", "..", "apps"))
ELK_NET  = "elk_network"
DB_IMAGE = "mariadb:10.5"

# Apps to omit from the auto-generated Compose
SKIP_APPS = {
    "docker-nosh",
    # add any other app directory names you wish to skip
}


def svc_name(name: str) -> str:
    """Normalize directory name to a valid Docker Compose service name."""
    return name.lower().replace("-", "_")


def find_dockerfile(app_path: str) -> (str, str):
    """
    Search for a Dockerfile (case-insensitive) anywhere in the app directory.
    Returns (relative_dir, dockerfile_name) or (None, None) if not found.
    """
    for root, dirs, files in os.walk(app_path):
        for f in files:
            if f.lower() == 'dockerfile':
                rel_dir = os.path.relpath(root, APPS_DIR)
                return rel_dir, f
    return None, None


# ——— Header —————————————————————————————————————————————
print(textwrap.dedent("""\
version: "3.8"

services:
"""))

# ——— Generate each app + its DB, with healthchecks ———————————————————————
for name in sorted(os.listdir(APPS_DIR)):
    path = os.path.join(APPS_DIR, name)
    if name in SKIP_APPS or not os.path.isdir(path):
        if name in SKIP_APPS:
            print(f"# Skipping {name} (configured in SKIP_APPS)", file=sys.stderr)
        continue

    rel_dir, dockerfile = find_dockerfile(path)
    if not dockerfile:
        print(f"# Skipping {name}: no Dockerfile found in {path}", file=sys.stderr)
        continue

    context_path = os.path.join('..', 'apps', rel_dir)
    base = svc_name(name)
    db_svc = f"{base}_db"
    app_svc = f"{base}_app"
    user = f"{base}_user"
    pwd  = f"{base}_pass"
    db   = base

    # App service block
    service_block = textwrap.dedent(f"""\
      {app_svc}:
        build:
          context: {context_path}
          dockerfile: {dockerfile}
        ports:
          - "80"
        environment:
          DB_HOST: {db_svc}
          DB_USER: {user}
          DB_PASS: {pwd}
          DB_NAME: {db}
          DB_PORT: 3306
          SEED_ROWS: 50
        depends_on:
          {db_svc}:
            condition: service_healthy
        healthcheck:
          test: ["CMD-SHELL", "curl -f http://localhost/ || exit 1"]
          interval: 30s
          timeout: 10s
          retries: 3
        networks:
          - {ELK_NET}
        entrypoint: >
          bash -lc "python3 /usr/local/bin/seeder.py && exec docker-php-entrypoint apache2-foreground"
    """)
    print(textwrap.indent(service_block, '  '))

    # DB service block
    db_block = textwrap.dedent(f"""\
      {db_svc}:
        image: {DB_IMAGE}
        environment:
          MYSQL_ROOT_PASSWORD: rootpassword
          MYSQL_DATABASE: {db}
          MYSQL_USER: {user}
          MYSQL_PASSWORD: {pwd}
        healthcheck:
          test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
          interval: 10s
          timeout: 5s
          retries: 5
        volumes:
          - {db_svc}_data:/var/lib/mysql
        networks:
          - {ELK_NET}
    """)
    print(textwrap.indent(db_block, '  '))

# ——— Mirth Connect —————————————————————————————————————————————
mirth_block = textwrap.dedent(f"""\
      mirth:
        image: nextgenhealthcare/mirthconnect:4.3.0
        container_name: mirth_connect
        environment:
          MIRTH_ADMIN_USER: admin
          MIRTH_ADMIN_PASSWORD: admin123
        ports:
          - "8081:8080"
          - "6661:6661"
        volumes:
          - mirth_data:/opt/mirth-connect/appdata
        networks:
          - {ELK_NET}
"""
)
print(textwrap.indent(mirth_block, '  '))

# ——— HL7 Simulator —————————————————————————————————————————————
hl7_block = textwrap.dedent(f"""\
      hl7_simulator:
        build:
          context: ./hl7-sim
          dockerfile: Dockerfile
        environment:
          HL7_HOST: fhmssp_app
          HL7_PORT: 6661
          HL7_INTERVAL: 2
        networks:
          - {ELK_NET}
"""
)
print(textwrap.indent(hl7_block, '  '))

# ——— Suricata ————————————————————————————————————————————————
suricata_block = textwrap.dedent(f"""\
      suricata:
        image: oisf/suricata:latest
        container_name: suricata
        volumes:
          - ../config/suricata/suricata.yaml:/etc/suricata/suricata.yaml:ro
          - ../config/suricata/rules:/etc/suricata/rules:ro
          - suricata_logs:/var/log/suricata
        networks:
          - {ELK_NET}
"""
)
print(textwrap.indent(suricata_block, '  '))

# ——— ELK Stack ————————————————————————————————————————————————
elk_block = textwrap.dedent(f"""\
      elasticsearch:
        image: docker.elastic.co/elasticsearch/elasticsearch:8.5.0
        environment:
          - discovery.type=single-node
        ports:
          - "9200:9200"
        networks:
          - {ELK_NET}

      kibana:
        image: docker.elastic.co/kibana/kibana:8.5.0
        ports:
          - "5601:5601"
        networks:
          - {ELK_NET}
"""
)
print(textwrap.indent(elk_block, '  '))

# ——— Networks & Volumes —————————————————————————————————————————
print(textwrap.dedent(f"""\

networks:
  {ELK_NET}:
    external: true

volumes:
"""))

# Volume entries for each app DB
for name in sorted(os.listdir(APPS_DIR)):
    if name in SKIP_APPS:
        continue
    path = os.path.join(APPS_DIR, name)
    if not os.path.isdir(path):
        continue
    rel_dir, dockerfile = find_dockerfile(path)
    if not dockerfile:
        continue
    base = svc_name(name)
    print(f"  {base}_db_data:")

# Extras: top-level volumes mirth_data & suricata_logs
print("  mirth_data:")
print("  suricata_logs:")
