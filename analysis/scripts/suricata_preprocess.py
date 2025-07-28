#!/usr/bin/env python3
"""
Load and preprocess Suricata EVE JSON logs for one or more instances.
Produces a consolidated CSV of all alerts and statistics, with optional
flattening of nested fields.
"""

import os
import json
import glob
import argparse
import pandas as pd

def load_eve_json(file_path):
    """Load a single eve.json file into a DataFrame, skipping bad lines."""
    logs = []
    with open(file_path, 'r') as f:
        for i, line in enumerate(f):
            try:
                logs.append(json.loads(line))
            except json.JSONDecodeError:
                # skip invalid JSON
                continue
    return pd.DataFrame(logs)

def flatten_stats(df, stats_col='stats'):
    """Expand the 'stats' dict column into separate columns."""
    if stats_col in df.columns:
        stats_df = pd.json_normalize(df[stats_col]).add_prefix(f"{stats_col}_")
        return pd.concat([df.drop(columns=[stats_col]), stats_df], axis=1)
    return df

def main(log_dirs, output_csv):
    all_dfs = []
    for d in log_dirs:
        eve_path = os.path.join(d, 'eve.json')
        if not os.path.isfile(eve_path):
            print(f"⚠️  No eve.json in {d}, skipping")
            continue
        df = load_eve_json(eve_path)
        df['instance'] = os.path.basename(d)
        df = flatten_stats(df)
        all_dfs.append(df)

    if not all_dfs:
        print("❌  No logs loaded, exiting.")
        return

    df_all = pd.concat(all_dfs, ignore_index=True)
    # optional: drop any fields you deem too sensitive, e.g. public_ip
    # df_all = df_all.drop(columns=['public_ip'], errors='ignore')
    df_all.to_csv(output_csv, index=False)
    print(f"✅  Written consolidated logs to {output_csv}")

if __name__ == "__main__":
    p = argparse.ArgumentParser(description="Preprocess AWS/Suricata eve.json logs")
    p.add_argument("log_dirs", nargs="+", help="Paths to Suricata instance directories")
    p.add_argument("-o", "--output", default="data/processed/aws_suricata_all.csv",
                   help="Output CSV path")
    args = p.parse_args()
    main(args.log_dirs, args.output)

