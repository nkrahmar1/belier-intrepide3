<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Affiche la page du panier
     */
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Récupère le nombre d'articles et le HTML du panier
     */
    public function count(): JsonResponse
    {
        try {
            $cart = session('cart', []);
            $cartCount = collect($cart)->sum('quantity');
            $total = 0;
            $html = '';

            if ($cartCount > 0) {
                $total = collect($cart)->sum(function ($item) {
                    return ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                });

                foreach ($cart as $key => $item) {
                    $html .= view('partials.cart-item-enhanced', [
                        'item' => $item,
                        'key' => $key,
                        'total' => $total
                    ])->render();
                }

                $html .= view('partials.cart-total', [
                    'total' => $total
                ])->render();
            } else {
                $html = view('partials.cart-empty')->render();
            }

            return response()->json([
                'success' => true,
                'count' => $cartCount,
                'total' => $total,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'count' => 0,
                'total' => 0,
                'html' => view('partials.cart-empty')->render(),
                'message' => 'Erreur lors du chargement du panier'
            ], 500);
        }
    }

    /**
     * Ajoute un produit au panier
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|integer|min:1',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'quantity' => 'sometimes|integer|min:1|max:999'
            ]);

            $cart = session()->get('cart', []);
            $productId = (string) $validated['product_id'];
            $quantity = $validated['quantity'] ?? 1;

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'name' => $validated['name'],
                    'price' => (float) $validated['price'],
                    'quantity' => $quantity
                ];
            }

            session()->put('cart', $cart);

            $cartCount = collect($cart)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'count' => $cartCount,
                'item' => $cart[$productId]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout au panier'
            ], 500);
        }
    }

    /**
     * Supprime un produit du panier
     */
    public function remove(Request $request, $id)
    {
        try {
            $cart = session()->get('cart', []);

            // Utiliser l'ID tel quel (peut être 'download_1', 'purchase_2', etc.)
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);

                $message = 'Article retiré du panier';

                if ($request->expectsJson()) {
                    $cartCount = collect($cart)->sum('quantity');
                    $total = collect($cart)->sum(function ($item) {
                        return ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                    });

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'count' => $cartCount,
                        'total' => $total
                    ]);
                }

                return back()->with('success', $message);
            }

            $message = 'Article non trouvé dans le panier';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 404);
            }

            return back()->with('error', $message);
        } catch (\Exception $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Vide complètement le panier
     */
    public function clear(Request $request)
    {
        try {
            session()->forget('cart');

            $message = 'Panier vidé avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => 0
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du panier'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression du panier');
        }
    }

    /**
     * Met à jour la quantité d'un produit
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer|min:1',
                'quantity' => 'required|integer|min:1|max:999'
            ]);

            $cart = session()->get('cart', []);
            $productId = (string) $validated['id'];

            if (!isset($cart[$productId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans le panier'
                ], 404);
            }

            $cart[$productId]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);

            $cartCount = collect($cart)->sum('quantity');
            $total = collect($cart)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour',
                'count' => $cartCount,
                'total' => $total
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Récupère le contenu complet du panier
     */
    public function show(): JsonResponse
    {
        try {
            $cart = session('cart', []);
            $total = collect($cart)->sum(function ($item) {
                return ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
            });
            $count = collect($cart)->sum('quantity');

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'total' => $total,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement du panier'
            ], 500);
        }
    }

    /**
     * Ajoute automatiquement un article téléchargé au panier (pour tracking)
     */
    public function addDownloadedArticle($article): bool
    {
        try {
            $cart = session()->get('cart', []);
            $articleId = 'download_' . $article->id; // Préfixe pour différencier des achats

            // Vérifier si l'article n'est pas déjà dans le panier de téléchargements
            if (!isset($cart[$articleId])) {
                $cart[$articleId] = [
                    'name' => $article->titre,
                    'price' => 0, // Prix 0 pour les téléchargements
                    'quantity' => 1,
                    'type' => 'download', // Type spécial pour les téléchargements
                    'article_id' => $article->id,
                    'downloaded_at' => now()->format('Y-m-d H:i:s'),
                    'document_path' => $article->document_path
                ];
            } else {
                // Si déjà téléchargé, on met à jour la date
                $cart[$articleId]['downloaded_at'] = now()->format('Y-m-d H:i:s');
                $cart[$articleId]['quantity'] += 1; // Compte les re-téléchargements
            }

            session()->put('cart', $cart);

            Log::info("Article ajouté au panier de téléchargements", [
                'article_id' => $article->id,
                'article_title' => $article->titre,
                'user_id' => Auth::id(),
                'downloaded_at' => now()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'ajout au panier de téléchargements", [
                'article_id' => $article->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
