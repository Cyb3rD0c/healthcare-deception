#!/usr/bin/env python3
"""
02_co_regularized_clustering.py

Multi-view co-regularized spectral clustering.
Implements the algorithm from Kumar et al. (2011) and as used in our paper (k=8).

Usage:
    python analysis/scripts/02_co_regularized_clustering.py \
        --sim-matrices semantic_similarity.npy categorical_similarity.npy numerical_similarity.npy temporal_similarity.npy \
        --k 8 --lambda 1.0 --iters 5 --seed 42 \
        --out-emb analysis/outputs/co_reg_embedding.npy \
        --out-labels analysis/outputs/co_reg_labels.npy
"""
import argparse
import numpy as np
from numpy.linalg import eigh
from sklearn.cluster import KMeans
import os

def compute_laplacian(S):
    """Compute unnormalized Laplacian L = D - S"""
    D = np.diag(S.sum(axis=1))
    return D - S


def topk_eigenvectors(M, k):
    """Return top-k eigenvectors of symmetric matrix M by largest eigenvalues."""
    vals, vecs = eigh(M)
    idx = np.argsort(vals)[::-1][:k]
    return vecs[:, idx]


def co_regularized_spectral(sim_paths, k, lam, n_iter, random_state):
    """
    sim_paths: list of file paths to similarity matrices (n x n)
    k: number of clusters / eigenvectors
    lam: co-regularization weight
    n_iter: number of alternating updates
    returns: consensus embedding U (n x k)
    """
    V = len(sim_paths)
    # Load similarity matrices and compute Laplacians
    Ls = [compute_laplacian(np.load(p)) for p in sim_paths]
    n = Ls[0].shape[0]
    # Initialize U^{(v)}
    Us = [topk_eigenvectors(L, k) for L in Ls]

    # Iterative co-regularization
    for it in range(n_iter):
        print(f"Co-regularization iteration {it+1}/{n_iter}")
        for v in range(V):
            # Compute penalty matrix sum_{w != v} (I - U_w U_w^T)
            penalty = np.zeros((n, n))
            for w in range(V):
                if w == v: continue
                Uw = Us[w]
                penalty += np.eye(n) - Uw @ Uw.T
            # Combined matrix: L_v + lam * penalty
            Mv = Ls[v] + lam * penalty
            # Update U_v
            Us[v] = topk_eigenvectors(Mv, k)

    # Consensus embedding: average over views
    U_consensus = sum(Us) / V
    return U_consensus


def main():
    parser = argparse.ArgumentParser(description="Multi-view co-regularized spectral clustering")
    parser.add_argument("--sim-matrices", nargs=4, required=True,
                        help="Paths to the four similarity .npy files in order: semantic, categorical, numerical, temporal")
    parser.add_argument("--k", type=int, default=8,
                        help="Number of clusters / eigenvectors (default as paper)")
    parser.add_argument("--lambda", type=float, default=1.0, dest="lam",
                        help="Co-regularization weight λ")
    parser.add_argument("--iters", type=int, default=5,
                        help="Number of alternating iterations")
    parser.add_argument("--seed", type=int, default=42,
                        help="Random seed (for any randomness)")
    parser.add_argument("--out-emb", required=True,
                        help="Output path for consensus embedding (n x k) .npy")
    parser.add_argument("--out-labels", required=True,
                        help="Output path for final cluster labels (n,) .npy")
    args = parser.parse_args()

    np.random.seed(args.seed)

    # Run co-regularized spectral
    U = co_regularized_spectral(
        sim_paths=args.sim_matrices,
        k=args.k,
        lam=args.lam,
        n_iter=args.iters,
        random_state=args.seed
    )

    # Save embedding
    os.makedirs(os.path.dirname(args.out_emb), exist_ok=True)
    np.save(args.out_emb, U)
    print(f"Saved consensus embedding to {args.out_emb}")

    # Final clustering via KMeans
    print(f"Clustering consensus embedding into {args.k} clusters...")
    km = KMeans(n_clusters=args.k, random_state=args.seed)
    labels = km.fit_predict(U)

    # Save labels
    os.makedirs(os.path.dirname(args.out_labels), exist_ok=True)
    np.save(args.out_labels, labels)
    print(f"Saved cluster labels to {args.out_labels}")

if __name__ == '__main__':
    main()

