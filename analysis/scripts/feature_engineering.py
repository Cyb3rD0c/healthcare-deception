#!/usr/bin/env python3
"""
Feature engineering for healthcare network logs.
Transforms raw Suricata/AWS log CSV into four feature sets:
 - semantic embeddings (src_ip_org, signature, category, cve_info)
 - categorical (protocol, direction, geo_ip country/city)
 - temporal (time_of_day, timestamp_hour cyclic)
 - numerical (entropy, distances, durations, counts)
Outputs an .npz with named arrays for downstream similarity and clustering.
"""
import os
import numpy as np
import pandas as pd
from sklearn.preprocessing import OneHotEncoder, StandardScaler


def cyclic_encode(values, period: float):
    """Return sine and cosine encoding of a cyclical feature."""
    radians = 2 * np.pi * (values / period)
    return np.sin(radians), np.cos(radians)


def load_embeddings(series: pd.Series):
    """Parse stringified lists or arrays into a 2D numpy array."""
    # If values are stored as strings like "[0.1, 0.2,...]"
    return np.stack(
        series.apply(lambda x: np.array(eval(x), dtype=float) if isinstance(x, str) else np.array(x, dtype=float))
    )


def main():
    # Paths and parameters
    input_csv = os.getenv("INPUT_CSV", "data/sample-logs.csv")
    output_npz = os.getenv("OUTPUT_FEATURES", "analysis/features.npz")

    # Load raw data
    df = pd.read_csv(input_csv, low_memory=False)

    # --- Semantic embeddings ---
    sem_cols = [
        'src_ip_org_embedding',
        'signature_embedding',
        'category_embedding',
        'cve_info_embedding'
    ]
    semantic_arrays = [load_embeddings(df[col]) for col in sem_cols]
    semantic = np.hstack(semantic_arrays)

    # --- Categorical features ---
    cat_cols = ['protocol', 'direction', 'dest_ip_country_code', 'dest_ip_city_name']
    cat_df = df[cat_cols].fillna('NA').astype(str)
    ohe = OneHotEncoder(sparse=False, handle_unknown='ignore')
    categorical = ohe.fit_transform(cat_df)

    # --- Temporal cyclic encoding ---
    # time_of_day already within [0,24) if present; else compute from timestamp
    if 'time_of_day' in df.columns:
        tod = df['time_of_day'].astype(float)
    else:
        tod = pd.to_datetime(df['timestamp']).dt.hour + pd.to_datetime(df['timestamp']).dt.minute/60.0
    tod_sin, tod_cos = cyclic_encode(tod.values, 24.0)

    # hour-of-day cyclic
    if 'timestamp_hour' in df.columns:
        th = df['timestamp_hour'].astype(float)
    else:
        th = pd.to_datetime(df['timestamp']).dt.hour
    th_sin, th_cos = cyclic_encode(th.values, 24.0)

    temporal = np.vstack([tod_sin, tod_cos, th_sin, th_cos]).T

    # --- Numerical scaling ---
    num_cols = [
        'protocol_entropy', 'geo_distance', 'activity_duration',
        'ip_activity_count', 'ip_unique_destinations',
        'ip_asn_changes', 'ip_persistence_duration'
    ]
    num_df = df[num_cols].fillna(0).astype(float)
    scaler = StandardScaler()
    numerical = scaler.fit_transform(num_df)

    # Save all feature groups
    os.makedirs(os.path.dirname(output_npz), exist_ok=True)
    np.savez(
        output_npz,
        semantic=semantic,
        categorical=categorical,
        temporal=temporal,
        numerical=numerical
    )
    print(f"Saved features to {output_npz}")


if __name__ == '__main__':
    main()

