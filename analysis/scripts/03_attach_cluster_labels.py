#!/usr/bin/env python3
"""
03_attach_cluster_labels.py

Append cluster labels from a .npy file to your master dataset CSV.

Usage:
    python analysis/scripts/03_attach_cluster_labels.py \
        --csv data/master_dataset.csv \
        --labels analysis/outputs/co_reg_labels.npy \
        --col-name cluster_co_reg \
        --out data/master_with_clusters.csv
"""
import argparse
import numpy as np
import pandas as pd
import os

def main():
    parser = argparse.ArgumentParser(
        description="Attach cluster labels to CSV."
    )
    parser.add_argument(
        '--csv', '-c', required=True,
        help='Path to input CSV file.'
    )
    parser.add_argument(
        '--labels', '-l', required=True,
        help='Path to .npy cluster labels file.'
    )
    parser.add_argument(
        '--col-name', '-n', required=True,
        help='Column name for cluster labels to be added.'
    )
    parser.add_argument(
        '--out', '-o', required=True,
        help='Path for output CSV with labels appended.'
    )
    args = parser.parse_args()

    # Load data
    df = pd.read_csv(args.csv)
    labels = np.load(args.labels)

    if len(df) != len(labels):
        raise ValueError(
            f"Row count mismatch: CSV has {len(df)} rows, labels file has {len(labels)} entries"
        )

    # Attach labels
    df[args.col_name] = labels

    # Save new CSV
    os.makedirs(os.path.dirname(args.out), exist_ok=True)
    df.to_csv(args.out, index=False)
    print(f"Saved dataset with '{args.col_name}' appended to {args.out}")

if __name__ == '__main__':
    main()

