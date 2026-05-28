<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AnalyticsController extends Controller
{
    /**
     * Données de performance
     */
    public function performance(Request $request)
    {
        $period = $request->get('period', '7days'); // 7days, 30days, 90days

        $data = [
            'sales' => $this->getSalesData($period),
            'visitors' => $this->getVisitorsData($period),
            'conversion_rate' => $this->getConversionRate($period),
            'average_order_value' => $this->getAverageOrderValue($period),
            'top_products' => $this->getTopProducts($period),
            'traffic_sources' => $this->getTrafficSources($period),
        ];

        return response()->json($data);
    }

    /**
     * Suivre les actions utilisateur
     */
    public function track(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'category' => 'required|string',
            'label' => 'nullable|string',
            'value' => 'nullable|numeric',
        ]);

        // Sauvegarder l'événement de tracking
        DB::table('analytics_events')->insert([
            'user_id' => Auth::id(),
            'action' => $request->input('action'),
            'category' => $request->input('category'),
            'label' => $request->input('label'),
            'value' => $request->input('value'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Rapport d'utilisation
     */
    public function usage(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $data = [
            'page_views' => $this->getPageViews($startDate, $endDate),
            'unique_visitors' => $this->getUniqueVisitors($startDate, $endDate),
            'session_duration' => $this->getAverageSessionDuration($startDate, $endDate),
            'bounce_rate' => $this->getBounceRate($startDate, $endDate),
            'most_visited_pages' => $this->getMostVisitedPages($startDate, $endDate),
            'user_activity' => $this->getUserActivity($startDate, $endDate),
        ];

        return response()->json($data);
    }

    /**
     * Obtenir les données de ventes
     */
    private function getSalesData($period)
    {
        $days = $this->getPeriodDays($period);
        
        // Exemple de données - à adapter selon votre modèle
        return [
            'total' => 15000,
            'previous_period' => 12000,
            'growth_percentage' => 25,
            'daily_data' => [
                // Génération de données d'exemple
                ['date' => now()->subDays(6)->format('Y-m-d'), 'amount' => 2100],
                ['date' => now()->subDays(5)->format('Y-m-d'), 'amount' => 2300],
                ['date' => now()->subDays(4)->format('Y-m-d'), 'amount' => 1900],
                ['date' => now()->subDays(3)->format('Y-m-d'), 'amount' => 2800],
                ['date' => now()->subDays(2)->format('Y-m-d'), 'amount' => 2400],
                ['date' => now()->subDays(1)->format('Y-m-d'), 'amount' => 2200],
                ['date' => now()->format('Y-m-d'), 'amount' => 1300],
            ]
        ];
    }

    /**
     * Obtenir les données de visiteurs
     */
    private function getVisitorsData($period)
    {
        return [
            'total' => 1250,
            'unique' => 980,
            'returning' => 270,
            'new' => 710,
        ];
    }

    /**
     * Obtenir le taux de conversion
     */
    private function getConversionRate($period)
    {
        return [
            'rate' => 3.2,
            'previous_rate' => 2.8,
            'improvement' => 0.4,
        ];
    }

    /**
     * Obtenir la valeur moyenne des commandes
     */
    private function getAverageOrderValue($period)
    {
        return [
            'value' => 85.50,
            'previous_value' => 78.20,
            'change' => 7.30,
        ];
    }

    /**
     * Obtenir les produits les plus vendus
     */
    private function getTopProducts($period)
    {
        return [
            ['name' => 'Produit A', 'sales' => 120, 'revenue' => 2400],
            ['name' => 'Produit B', 'sales' => 95, 'revenue' => 1900],
            ['name' => 'Produit C', 'sales' => 78, 'revenue' => 1560],
        ];
    }

    /**
     * Obtenir les sources de trafic
     */
    private function getTrafficSources($period)
    {
        return [
            ['source' => 'Recherche organique', 'visitors' => 450, 'percentage' => 45],
            ['source' => 'Réseaux sociaux', 'visitors' => 280, 'percentage' => 28],
            ['source' => 'Email marketing', 'visitors' => 150, 'percentage' => 15],
            ['source' => 'Publicité payante', 'visitors' => 120, 'percentage' => 12],
        ];
    }

    /**
     * Obtenir le nombre de jours selon la période
     */
    private function getPeriodDays($period)
    {
        return match($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 7,
        };
    }

    /**
     * Obtenir les vues de pages
     */
    private function getPageViews($startDate, $endDate)
    {
        // Implémenter selon votre système de tracking
        return 25000;
    }

    /**
     * Obtenir les visiteurs uniques
     */
    private function getUniqueVisitors($startDate, $endDate)
    {
        return 5600;
    }

    /**
     * Obtenir la durée moyenne des sessions
     */
    private function getAverageSessionDuration($startDate, $endDate)
    {
        return '00:04:35'; // Format mm:ss
    }

    /**
     * Obtenir le taux de rebond
     */
    private function getBounceRate($startDate, $endDate)
    {
        return 42.5; // Pourcentage
    }

    /**
     * Obtenir les pages les plus visitées
     */
    private function getMostVisitedPages($startDate, $endDate)
    {
        return [
            ['page' => '/dashboard', 'views' => 1200],
            ['page' => '/products', 'views' => 850],
            ['page' => '/orders', 'views' => 720],
        ];
    }

    /**
     * Obtenir l'activité des utilisateurs
     */
    private function getUserActivity($startDate, $endDate)
    {
        return [
            'active_users' => 1250,
            'new_registrations' => 85,
            'returning_users' => 1165,
        ];
    }
}