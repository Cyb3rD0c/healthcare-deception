#!/usr/bin/env python3
"""
Scan multiple CSV directories and:
  1. Display the head of the first few files in each directory.
  2. Compute the union of all column names across all files.
Usage:
  python consolidate_columns.py \
    --dirs aws1:/path/to/aws1/chunks azure1:/path/to/azure1/chunks \
    --show-head 3
"""
import argparse
import os
import pandas as pd


def display_csv_heads(directory, num_files=5):
    """Prints the head of up to num_files CSVs in directory."""
    files = sorted(f for f in os.listdir(directory) if f.endswith('.csv'))
    for fname in files[:num_files]:
        path = os.path.join(directory, fname)
        print(f"\n--- Head of {path} ---")
        df = pd.read_csv(path)
        print(df.head())


def get_unique_columns(directory):
    """Returns a set of all column names across CSVs in directory."""
    cols = set()
    for fname in os.listdir(directory):
        if not fname.endswith('.csv'):
            continue
        df = pd.read_csv(os.path.join(directory, fname), nrows=0)
        cols.update(df.columns)
    return cols


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument(
        '--dirs',
        nargs='+',
        required=True,
        help="List of name:directory pairs, e.g. aws1:/data/aws1/chunks"
    )
    parser.add_argument(
        '--show-head',
        type=int,
        default=0,
        help="If >0, display that many CSV heads per directory"
    )
    args = parser.parse_args()

    all_columns = set()
    for pair in args.dirs:
        name, directory = pair.split(':', 1)
        if args.show_head > 0:
            print(f"\n## Showing head for {name} ##")
            display_csv_heads(directory, num_files=args.show_head)
        cols = get_unique_columns(directory)
        print(f"\nColumns in {name}: {sorted(cols)}")
        all_columns.update(cols)

    print(f"\n\nAll unique columns across all instances ({len(all_columns)}):")
    print(sorted(all_columns))


if __name__ == '__main__':
    main()

