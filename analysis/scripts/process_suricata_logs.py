#!/usr/bin/env python3
"""
Load and merge Suricata EVE JSON logs (both uncompressed and .gz),
deduplicate by timestamp+event_type, and output a single CSV.

Usage:
  python process_suricata_logs.py \
    --input-dir /data/logs/v1 \
    --input-dir /data/logs/v2 \
    --output /data/processed/deduped_logs.csv
"""
import argparse
import os
import gzip
import json
import pandas as pd
from glob import glob


def load_json_file(path):
    """Load a plain .json EVE file into a DataFrame, skipping bad lines."""
    records = []
    with open(path) as f:
        for i, line in enumerate(f):
            try:
                records.append(json.loads(line))
            except json.JSONDecodeError:
                continue
    return pd.DataFrame(records)


def load_json_gz_file(path):
    """Load a .json.gz EVE file into a DataFrame, skipping bad lines."""
    records = []
    with gzip.open(path, 'rt') as f:
        for i, line in enumerate(f):
            try:
                records.append(json.loads(line))
            except json.JSONDecodeError:
                continue
    return pd.DataFrame(records)


def load_all_logs(directory):
    """Load all eve.json* files in a directory into one DataFrame."""
    dfs = []
    # plain JSON
    base = os.path.join(directory, 'eve.json')
    if os.path.isfile(base):
        dfs.append(load_json_file(base))
    # rotated gz files
    for path in sorted(glob(os.path.join(directory, 'eve.json.*.gz'))):
        dfs.append(load_json_gz_file(path))
    return pd.concat(dfs, ignore_index=True)


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument(
        '--input-dir',
        action='append',
        required=True,
        help="Path to a directory containing eve.json and .gz logs"
    )
    parser.add_argument(
        '--output',
        required=True,
        help="Path to write deduplicated CSV"
    )
    args = parser.parse_args()

    # Load and concatenate logs from all input dirs
    all_df = []
    for d in args.input_dir:
        print(f"Loading logs from {d}")
        df = load_all_logs(d)
        all_df.append(df)

    combined = pd.concat(all_df, ignore_index=True)
    print(f"Total rows before dedupe: {len(combined)}")

    # Deduplicate on timestamp + event_type
    if 'timestamp' in combined.columns and 'event_type' in combined.columns:
        before = len(combined)
        deduped = combined.drop_duplicates(
            subset=['timestamp', 'event_type'], keep='first'
        )
        print(f"Dropped {before - len(deduped)} duplicates; {len(deduped)} rows remain")
    else:
        deduped = combined

    # Save
    deduped.to_csv(args.output, index=False)
    print(f"Written deduplicated logs to {args.output}")


if __name__ == '__main__':
    main()

