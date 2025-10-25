<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Accueil
    public function home()
    {
        // Récupération des articles publiés sur la homepage avec tous leurs éléments
        $featuredArticles = Article::where('is_published', true)
            ->where('featured_on_homepage', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'user'])
            ->orderBy('homepage_featured_at', 'desc')
            ->get();

        // Grouper les articles par catégorie pour l'affichage
        $articlesByCategory = $featuredArticles->groupBy(function($article) {
            return $article->category ? $article->category->nom : 'Non classé';
        });

        // Récupération des derniers articles publiés (pour la section générale)
        $latestArticles = Article::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('category', 'user')
            ->latest('published_at')
            ->take(6)
            ->get();

        $featuredProducts = Product::where('featured', true)->take(4)->get();
        $categories = Category::withCount('products')->get();

        // Pour le panier
        $cartCount = session('cart', []);
        $cartItemCount = collect($cartCount)->sum('quantity');

        // Utilisateur connecté
        $user = Auth::user();

        return view('home.home', compact(
            'user',
            'featuredArticles',
            'articlesByCategory',
            'latestArticles',
            'featuredProducts',
            'categories',
            'cartItemCount'
        ));
    }

    // À propos
    public function about()
    {
        return view('home.about');
    }

    // Tableau de bord
    public function dashboard()
    {
        return view('home.dashboard');
    }

    // Page de téléchargement de fichiers
    public function files()
    {
        return view('home.files');
    }

    // Page spéciale PDCI-RDA
    public function pdcirda()
    {
        return view('home.pdcirda');
    }

    /**
     * Affiche les articles d'une catégorie donnée.
     * Si la catégorie est "International", on regroupe les articles de "Society" et "Political".
     */
    public function showByCategorie($categorie)
    {
        // Cas spécial pour PDCI-RDA
        if ($categorie === 'PDCI-RDA') {
            return $this->pdcirda();
        }

        // Cas spécial pour International (grouper Society et Political)
        if ($categorie === 'International') {
            $articles = Article::whereHas('category', function($query) {
                $query->whereIn('nom', ['Society', 'Political']);
            })->published()->latest()->get();
        } else {
            // Pour les autres catégories, filtrer par le nom exact
            $articles = Article::whereHas('category', function($query) use ($categorie) {
                $query->where('nom', $categorie);
            })->published()->latest()->get();
        }

        return view('home.category', compact('articles', 'categorie'));
    }
}
