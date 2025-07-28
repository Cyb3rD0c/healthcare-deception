#!/usr/bin/env python3
"""
Enrich IP addresses and file hashes with multiple threat-intel sources:

  • GeoIP (MaxMind GeoIP2)
  • GreyNoise
  • VirusTotal
  • AlienVault OTX
  • Hybrid Analysis
  • Malware Bazaar

Usage:
  python enrichment.py \
    --input data/processed/all_logs.csv \
    --ip-col src_ip \
    --hash-col file_hash \
    --output data/enriched/logs_enriched.csv
"""

import os
import time
import argparse
import pandas as pd
import requests
import geoip2.database

# Environment variables for API keys
MAXMIND_DB_PATH    = os.getenv("MAXMIND_DB_PATH", "config/GeoLite2-City.mmdb")
GREYNOISE_API_KEY  = os.getenv("GREYNOISE_API_KEY", "")
VT_API_KEY         = os.getenv("VIRUSTOTAL_API_KEY", "")
OTX_API_KEY        = os.getenv("OTX_API_KEY", "")
HYBRID_API_KEY     = os.getenv("HYBRID_API_KEY", "")
MALWAREBAZAAR_KEY  = os.getenv("MALWAREBAZAAR_KEY", "")

# Rate‐limit delays (seconds) to avoid throttling
VT_DELAY           = 15
OTX_DELAY          = 1
HYBRID_DELAY       = 2
MZ_DELAY           = 1

def geoip_lookup(reader, ip):
    try:
        rec = reader.city(ip)
        return {
            "geo_country": rec.country.iso_code,
            "geo_city":    rec.city.name,
            "geo_lat":     rec.location.latitude,
            "geo_lon":     rec.location.longitude
        }
    except Exception:
        return {"geo_country": None, "geo_city": None, "geo_lat": None, "geo_lon": None}

def greynoise_lookup(ip):
    if not GREYNOISE_API_KEY: return {}
    url = f"https://api.greynoise.io/v3/community/{ip}"
    headers = {"Accept": "application/json", "GN-API-KEY": GREYNOISE_API_KEY}
    r = requests.get(url, headers=headers, timeout=10)
    if r.status_code == 200:
        js = r.json()
        return {
            "gn_reputation": js.get("reputation"),
            "gn_classification": js.get("classification")
        }
    return {}

def virustotal_lookup(entity, type_="ip"):
    if not VT_API_KEY: return {}
    base = "https://www.virustotal.com/api/v3"
    url = f"{base}/{ 'ip_addresses' if type_=='ip' else 'files' }/{entity}"
    headers = {"x-apikey": VT_API_KEY}
    r = requests.get(url, headers=headers, timeout=15)
    time.sleep(VT_DELAY)
    if r.status_code == 200:
        data = r.json().get("data", {}).get("attributes", {})
        return {
            f"vt_{type_}_malicious": data.get("last_analysis_stats", {}).get("malicious", 0),
            f"vt_{type_}_harmless":  data.get("last_analysis_stats", {}).get("harmless", 0)
        }
    return {}

def otx_lookup(entity, type_="ip"):
    if not OTX_API_KEY: return {}
    base = "https://otx.alienvault.com/api/v1"
    url = f"{base}/{ 'indicators/' + type_ + '/' + entity + '/general' }"
    headers = {"X-OTX-API-KEY": OTX_API_KEY}
    r = requests.get(url, headers=headers, timeout=10)
    time.sleep(OTX_DELAY)
    if r.status_code == 200:
        js = r.json()
        return {f"otx_{type_}_pulse_info": len(js.get("pulse_info", []))}
    return {}

def hybrid_lookup(hash_):
    if not HYBRID_API_KEY: return {}
    url = "https://www.hybrid-analysis.com/api/v2/search/hash"
    headers = {"API-Key": HYBRID_API_KEY, "User-Agent": "Falcon"}
    r = requests.post(url, headers=headers, data={"hash": hash_}, timeout=10)
    time.sleep(HYBRID_DELAY)
    if r.status_code == 200 and r.json():
        entry = r.json()[0]
        return {"hybrid_malicious": entry.get("threat_score", 0)}
    return {}

def malwarebazaar_lookup(hash_):
    if not MALWAREBAZAAR_KEY: return {}
    url = "https://mb-api.abuse.ch/api/v1/"
    data = {"query": "get_info", "hash": hash_}
    r = requests.post(url, data=data, timeout=10)
    time.sleep(MZ_DELAY)
    js = r.json()
    if js.get("query_status") == "ok":
        entry = js.get("data", [])[0]
        return {"mb_tags": entry.get("tags")}
    return {}

def enrich_row(row, geo_reader):
    out = {}
    ip = row.get(args.ip_col)
    if pd.notna(ip):
        out.update(geoip_lookup(geo_reader, ip))
        out.update(greynoise_lookup(ip))
        out.update(virustotal_lookup(ip, type_="ip"))
        out.update(otx_lookup(ip,    type_="ip"))
    hash_ = row.get(args.hash_col)
    if pd.notna(hash_):
        out.update(virustotal_lookup(hash_, type_="hash"))
        out.update(otx_lookup(hash_,         type_="hash"))
        out.update(hybrid_lookup(hash_))
        out.update(malwarebazaar_lookup(hash_))
    return out

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("--input",    required=True, help="Path to input CSV")
    parser.add_argument("--ip-col",   default="src_ip",  help="Column name for IPs")
    parser.add_argument("--hash-col", default="file_hash",
                        help="Column name for file hashes")
    parser.add_argument("--output",   default="data/enriched/enriched.csv",
                        help="Path to write enriched CSV")
    args = parser.parse_args()

    # load
    df = pd.read_csv(args.input)
    reader = geoip2.database.Reader(MAXMIND_DB_PATH)

    # enrich
    enrichments = df.apply(lambda r: enrich_row(r, reader), axis=1, result_type="expand")
    out = pd.concat([df, enrichments], axis=1)

    # write
    out.to_csv(args.output, index=False)
    print(f"✅  Written enriched data to {args.output}")

