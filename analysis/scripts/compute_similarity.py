#!/usr/bin/env python3
"""
compute_similarity.py

Compute pairwise similarity matrices for each feature group:
 - semantic: cosine similarity
 - categorical: 1 - Hamming distance
 - temporal: 1 / (1 + Euclidean distance)
 - numerical: 1 / (1 + Euclidean distance)

Usage:
    python analysis/scripts/compute_similarity.py \
        --features analysis/features.npz \
        --outdir analysis/similarity
"""
import os
import argparse
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity, pairwise_distances

def load_features(npz_path):
    data = np.load(npz_path)
    return data['semantic'], data['categorical'], data['temporal'], data['numerical']


def save_matrix(matrix, name, outdir):
    path = os.path.join(outdir, f"{name}_similarity.npy")
    np.save(path, matrix)
    print(f"Saved {name} similarity matrix to {path}, shape={matrix.shape}")


def main(features, outdir):
    os.makedirs(outdir, exist_ok=True)
    sem, cat, temp, num = load_features(features)

    # Semantic: cosine similarity
    print("Computing semantic cosine similarity...")
    sem_sim = cosine_similarity(sem)
    save_matrix(sem_sim, 'semantic', outdir)

    # Categorical: 1 - Hamming distance
    print("Computing categorical Hamming similarity...")
    cat_dist = pairwise_distances(cat, metric='hamming')
    cat_sim = 1 - cat_dist
    save_matrix(cat_sim, 'categorical', outdir)

    # Temporal: 1 / (1 + Euclidean distance)
    print("Computing temporal similarity (1/(1+d))...")
    temp_dist = pairwise_distances(temp, metric='euclidean')
    temp_sim = 1.0 / (1.0 + temp_dist)
    save_matrix(temp_sim, 'temporal', outdir)

    # Numerical: 1 / (1 + Euclidean distance)
    print("Computing numerical similarity (1/(1+d))...")
    num_dist = pairwise_distances(num, metric='euclidean')
    num_sim = 1.0 / (1.0 + num_dist)
    save_matrix(num_sim, 'numerical', outdir)

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description="Compute similarity matrices for feature groups.")
    parser.add_argument('--features', '-f', required=True, help='Path to features.npz produced by feature_engineering.py')
    parser.add_argument('--outdir', '-o', required=True, help='Directory to save similarity .npy files')
    args = parser.parse_args()
    main(args.features, args.outdir)

