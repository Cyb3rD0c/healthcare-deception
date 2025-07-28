#!/usr/bin/env python3
"""
04_evaluate_clusters.py

Evaluate clustering quality with:
 - Silhouette Score on the embedding space
 - (Optional) NMI & F1-Score against ground truth labels

Usage:
    python analysis/scripts/04_evaluate_clusters.py \
      --embedding analysis/outputs/co_reg_embedding.npy \
      --predicted-labels analysis/outputs/co_reg_labels.npy \
      --true-label-csv data/master_with_clusters.csv \
      --true-label-col actual_attack_label \
      --metric cosine
"""
import argparse
import numpy as np
import pandas as pd
from sklearn.metrics import silhouette_score, normalized_mutual_info_score, f1_score
from scipy.optimize import linear_sum_assignment

def map_clusters_to_labels(true_labels, clusters):
    """
    Map each cluster to the most frequent true label via Hungarian matching.
    Returns a dict: {cluster_id: mapped_true_label}
    """
    uniq_clusters = np.unique(clusters)
    uniq_labels   = np.unique(true_labels)
    # Build contingency table: clusters × labels
    contingency = np.zeros((len(uniq_clusters), len(uniq_labels)), dtype=int)
    for i, c in enumerate(uniq_clusters):
        mask = clusters == c
        counts = pd.Series(true_labels[mask]).value_counts()
        for j, lab in enumerate(uniq_labels):
            contingency[i, j] = counts.get(lab, 0)
    # Hungarian to maximize matches
    row_ind, col_ind = linear_sum_assignment(-contingency)
    return { uniq_clusters[i]: uniq_labels[j] for i, j in zip(row_ind, col_ind) }

def evaluate(emb, preds, true_labels=None, metric="cosine"):
    """
    Compute Silhouette (always), and if true_labels provided, NMI & F1.
    Returns (silhouette, nmi, f1) where nmi/f1 may be None.
    """
    sil = silhouette_score(emb, preds, metric=metric)
    nmi = f1 = None
    if true_labels is not None:
        nmi = normalized_mutual_info_score(true_labels, preds)
        # Map clusters → labels
        mapping = map_clusters_to_labels(true_labels, preds)
        mapped = np.vectorize(mapping.get)(preds)
        f1 = f1_score(true_labels, mapped, average="macro")
    return sil, nmi, f1

def main():
    p = argparse.ArgumentParser(description="Evaluate clustering results")
    p.add_argument("--embedding",       "-e", required=True, help="Path to embedding .npy (n×k)")
    p.add_argument("--predicted-labels", "-p", required=True, help="Path to predicted labels .npy (n,)")
    p.add_argument("--true-label-csv",  "-c", help="(Optional) CSV with ground-truth labels")
    p.add_argument("--true-label-col",  "-l", help="(Optional) column name in CSV for true labels")
    p.add_argument("--metric",          "-m", default="cosine",
                   choices=["cosine","euclidean"], help="Silhouette distance metric")
    args = p.parse_args()

    # Load embedding and predictions
    emb = np.load(args.embedding)
    preds = np.load(args.predicted_labels)

    if emb.shape[0] != preds.shape[0]:
        raise ValueError(f"Embeddings have {emb.shape[0]} rows but labels have {preds.shape[0]}")

    # Load true labels if requested
    true_labels = None
    if args.true_label_csv or args.true_label_col:
        if not (args.true_label_csv and args.true_label_col):
            raise ValueError("Both --true-label-csv and --true-label-col must be provided together")
        df = pd.read_csv(args.true_label_csv)
        if args.true_label_col not in df.columns:
            raise KeyError(f"Column '{args.true_label_col}' not found in {args.true_label_csv}")
        true_labels = df[args.true_label_col].astype(str).values
        if true_labels.shape[0] != preds.shape[0]:
            raise ValueError(
                f"CSV has {true_labels.shape[0]} rows but labels file has {preds.shape[0]}"
            )

    # Evaluate
    sil, nmi, f1 = evaluate(emb, preds, true_labels, metric=args.metric)

    # Print summary
    print("\nClustering Evaluation Results:")
    print(f"  Silhouette ({args.metric}): {sil:.4f}")
    if true_labels is not None:
        print(f"  Normalized Mutual Information: {nmi:.4f}")
        print(f"  F1 (macro):                   {f1:.4f}")
    print()

if __name__ == "__main__":
    main()

