#!/usr/bin/env python3
"""
01_nystrom_clustering.py

Single-view spectral clustering via Nyström approximation.

Usage:
    python analysis/scripts/01_nystrom_clustering.py \
        --matrix path/to/<view>_similarity.npy \
        --sample-size 5000 \
        --k 8 \
        --seed 42 \
        --emb-out analysis/outputs/nystrom_<view>_embedding.npy \
        --labels-out analysis/outputs/nystrom_<view>_labels.npy
"""
import argparse
import numpy as np
from sklearn.cluster import KMeans
from numpy.linalg import eigh


def nystrom_approximation(sim_path, sample_size, n_components, random_state=None):
    """
    Compute Nyström approximation for eigen-decomposition of a large similarity matrix.

    Args:
        sim_path (str): Path to .npy similarity matrix (n x n).
        sample_size (int): Number of landmark points (m).
        n_components (int): Number of eigen-components to approximate (k).
        random_state (int): Seed for reproducibility.

    Returns:
        U_full (ndarray): NumPy array of shape (n, k) with approximated eigenvectors.
    """
    # memory-map the similarity matrix
    W = np.load(sim_path, mmap_mode='r')
    n = W.shape[0]
    # if sample_size >= n, fall back to full decomposition
    if sample_size >= n:
        W_mat = np.array(W)
        eigvals, eigvecs = eigh(W_mat)
        idx = np.argsort(eigvals)[::-1][:n_components]
        return eigvecs[:, idx]

    # choose landmark indices
    rng = np.random.default_rng(random_state)
    landmark_idx = rng.choice(n, size=sample_size, replace=False)
    landmark_idx.sort()

    # submatrix W_mm and cross-similarities W_mn
    W_mm = W[np.ix_(landmark_idx, landmark_idx)]          # (m, m)
    W_mn = W[np.ix_(landmark_idx, np.arange(n))]          # (m, n)

    # eigen-decomp of W_mm
    eigvals_m, eigvecs_m = eigh(W_mm)
    # take top k eigenvalues/vectors
    top_idx = np.argsort(eigvals_m)[::-1][:n_components]
    lambdas = eigvals_m[top_idx]                         # (k,)
    U_m = eigvecs_m[:, top_idx]                          # (m, k)

    # approximate full eigenvectors
    U_full = np.zeros((n, n_components), dtype=float)
    # fill in landmark rows
    U_full[landmark_idx, :] = U_m
    # extend to all points
    for j in range(n_components):
        U_full[:, j] = (W_mn.T @ U_m[:, j]) / lambdas[j]
    return U_full


def main():
    parser = argparse.ArgumentParser(
        description="Single-view Nyström spectral clustering"
    )
    parser.add_argument("--matrix", "-m", required=True,
                        help="Path to .npy similarity matrix")
    parser.add_argument("--sample-size", "-s", type=int, required=True,
                        help="Number of landmark points for Nyström (m)")
    parser.add_argument("--k", type=int, required=True,
                        help="Number of clusters / eigencomponents (k)")
    parser.add_argument("--seed", type=int, default=42,
                        help="Random seed for reproducibility")
    parser.add_argument("--emb-out", required=True,
                        help="Where to save the embedding (n x k) .npy")
    parser.add_argument("--labels-out", required=True,
                        help="Where to save the cluster labels (n,) .npy")
    args = parser.parse_args()

    # compute Nyström embedding
    print(f"Loading similarity matrix from {args.matrix}…")
    U = nystrom_approximation(
        args.matrix,
        sample_size=args.sample_size,
        n_components=args.k,
        random_state=args.seed
    )

    # save embedding
    os.makedirs(os.path.dirname(args.emb_out), exist_ok=True)
    np.save(args.emb_out, U)
    print(f"Saved embedding (n×k) to {args.emb_out}")

    # K-Means clustering on embedding
    print(f"Clustering into {args.k} clusters…")
    kmeans = KMeans(n_clusters=args.k, random_state=args.seed)
    labels = kmeans.fit_predict(U)

    # save labels
    os.makedirs(os.path.dirname(args.labels_out), exist_ok=True)
    np.save(args.labels-out, labels)
    print(f"Saved cluster labels (n,) to {args.labels_out}")


if __name__ == '__main__':
    main()
    parser = argparse.ArgumentParser(description="Compute similarity matrices for feature groups.")
    parser.add_argument('--features', '-f', required=True, help='Path to features.npz produced by feature_engineering.py')
    parser.add_argument('--outdir', '-o', required=True, help='Directory to save similarity .npy files')
    args = parser.parse_args()
    main(args.features, args.outdir)

