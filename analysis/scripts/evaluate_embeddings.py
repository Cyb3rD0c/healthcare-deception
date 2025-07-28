#!/usr/bin/env python3
"""
evaluate_embeddings.py

Generic evaluation of embedding models on any text dataset.

For each model you supply, it will:
  • Encode your rows into embeddings.
  • Cluster them (default: KMeans).
  • Compute Silhouette Score (always).
  • If you provide --label-col:
      – Compute NMI and F1 against those labels.

Usage:
  python evaluate_embeddings.py \
    --data path/to/your.csv \
    --text-cols col1 col2 ... \
    [--label-col YOUR_LABEL_COLUMN] \
    [--n-clusters 5] \
    [--models modelA modelB ...] \
    [--batch-size 32]
"""

import argparse
import numpy as np
import pandas as pd
from sentence_transformers import SentenceTransformer
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score, normalized_mutual_info_score, f1_score
from scipy.optimize import linear_sum_assignment

def map_clusters_to_labels(true_labels, clusters):
    """Map each cluster to the most common true label via Hungarian matching."""
    labels = np.unique(true_labels)
    uniq_clusters = np.unique(clusters)
    # build count matrix: cluster x label
    M = np.zeros((len(uniq_clusters), len(labels)), dtype=int)
    for i, c in enumerate(uniq_clusters):
        mask = clusters == c
        counts = pd.Series(true_labels[mask]).value_counts()
        for j, lab in enumerate(labels):
            M[i, j] = counts.get(lab, 0)
    # find best assignment
    row_ind, col_ind = linear_sum_assignment(-M)
    return { uniq_clusters[r]: labels[c] for r, c in zip(row_ind, col_ind) }

def eval_model(embeds, true_labels=None, n_clusters=None):
    """
    Cluster embeddings and compute metrics.
    - Silhouette (always)
    - NMI & F1 if true_labels is provided
    """
    # decide k
    if n_clusters is None:
        if true_labels is None:
            raise ValueError("Must supply --n-clusters if no label column")
        n_clusters = len(np.unique(true_labels))

    # KMeans clustering
    km = KMeans(n_clusters=n_clusters, random_state=42)
    preds = km.fit_predict(embeds)

    # Silhouette
    sil = silhouette_score(embeds, preds, metric="cosine")

    nmi = f1 = None
    if true_labels is not None:
        nmi = normalized_mutual_info_score(true_labels, preds)
        # map clusters→labels for F1
        mapping = map_clusters_to_labels(true_labels, preds)
        mapped = np.vectorize(mapping.get)(preds)
        f1 = f1_score(true_labels, mapped, average="macro")

    return sil, nmi, f1

def main():
    p = argparse.ArgumentParser(description="Evaluate embedding models generically")
    p.add_argument("--data",       required=True, help="CSV file with your data")
    p.add_argument(
        "--text-cols", nargs="+", required=True,
        help="One or more text columns to concatenate"
    )
    p.add_argument(
        "--label-col", help="(Optional) column name for ground-truth labels"
    )
    p.add_argument(
        "--n-clusters", type=int,
        help="(Optional) number of clusters to form (overrides label-col count)"
    )
    p.add_argument(
        "--models",
        nargs="+",
        default=[
            "basel/ATTACK-BERT",
            "microsoft/SecRoBERTa",
            "mrm8488/SentSecBERT",
        ],
        help="List of HuggingFace SentenceTransformer model names"
    )
    p.add_argument("--batch-size", type=int, default=32, help="Embedding batch size")
    args = p.parse_args()

    # Load & prepare
    df = pd.read_csv(args.data)
    # concatenate text columns into one field
    df["__text__"] = (
        df[args.text_cols]
        .fillna("")
        .agg(" ".join, axis=1)
    )
    embeddings = None
    labels = None
    if args.label_col:
        labels = df[args.label_col].astype(str).values

    print(f"Loaded {len(df)} rows; will embed {len(args.models)} models")

    # Iterate models
    results = []
    for model_name in args.models:
        print(f"\n→ Model: {model_name}")
        model = SentenceTransformer(model_name)
        embeds = model.encode(
            df["__text__"].tolist(),
            batch_size=args.batch_size,
            show_progress_bar=True
        )
        sil, nmi, f1 = eval_model(
            np.array(embeds),
            true_labels=labels,
            n_clusters=args.n_clusters
        )
        results.append((model_name, sil, nmi, f1))

    # Output nicely
    header = ["Model", "Silhouette"]
    if labels is not None or args.n_clusters:
        header += ["NMI", "F1 (macro)"]
    print("\n" + " | ".join(header))
    print("-" * (len(header) * 15))
    for name, sil, nmi, f1 in results:
        row = [f"{name:30}", f"{sil:6.3f}"]
        if nmi is not None:
            row += [f"{nmi:6.3f}", f"{f1:6.3f}"]
        print(" | ".join(row))

if __name__ == "__main__":
    main()

