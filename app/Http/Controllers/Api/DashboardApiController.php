<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    /**
     * Métriques principales du dashboard
     */
    public function metrics(Request $request)
    {
        $period = $request->get('period', '30days');
        
        $metrics = [
            'overview' => [
                'total_revenue' => $this->getTotalRevenue($period),
                'total_orders' => $this->getTotalOrders($period),
                'total_customers' => $this->getTotalCustomers($period),
                'average_order_value' => $this->getAverageOrderValue($period),
            ],
            'growth' => [
                'revenue_growth' => $this->getRevenueGrowth($period),
                'orders_growth' => $this->getOrdersGrowth($period),
                'customers_growth' => $this->getCustomersGrowth($period),
                'conversion_rate' => $this->getConversionRate($period),
            ],
            'products' => [
                'total_products' => $this->getTotalProducts(),
                'low_stock_count' => $this->getLowStockCount(),
                'out_of_stock_count' => $this->getOutOfStockCount(),
                'top_selling' => $this->getTopSellingProducts(5),
            ],
            'traffic' => [
                'total_visitors' => $this->getTotalVisitors($period),
                'unique_visitors' => $this->getUniqueVisitors($period),
                'page_views' => $this->getPageViews($period),
                'bounce_rate' => $this->getBounceRate($period),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $metrics,
            'period' => $period,
            'generated_at' => now()->toISOString()
        ]);
    }

    /**
     * Données pour les graphiques
     */
    public function chartData($type, Request $request)
    {
        $period = $request->get('period', '30days');
        $data = [];

        switch ($type) {
            case 'sales':
                $data = $this->getSalesChartData($period);
                break;
                
            case 'orders':
                $data = $this->getOrdersChartData($period);
                break;
                
            case 'visitors':
                $data = $this->getVisitorsChartData($period);
                break;
                
            case 'products':
                $data = $this->getProductsChartData($period);
                break;
                
            case 'revenue-by-category':
                $data = $this->getRevenueByCategoryData($period);
                break;
                
            case 'customer-acquisition':
                $data = $this->getCustomerAcquisitionData($period);
                break;
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Type de graphique non supporté'
                ], 400);
        }

        return response()->json([
            'success' => true,
            'type' => $type,
            'data' => $data,
            'period' => $period
        ]);
    }

    /**
     * Rapport complet
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $includeCharts = $request->get('include_charts', false);

        $report = [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'days_count' => now()->parse($startDate)->diffInDays(now()->parse($endDate)) + 1
            ],
            'summary' => [
                'total_revenue' => $this->getRevenueForPeriod($startDate, $endDate),
                'total_orders' => $this->getOrdersForPeriod($startDate, $endDate),
                'total_customers' => $this->getCustomersForPeriod($startDate, $endDate),
                'average_order_value' => $this->getAverageOrderValueForPeriod($startDate, $endDate),
            ],
            'performance' => [
                'best_selling_products' => $this->getBestSellingProducts($startDate, $endDate),
                'top_customers' => $this->getTopCustomers($startDate, $endDate),
                'revenue_by_day' => $this->getRevenueByDay($startDate, $endDate),
                'orders_by_status' => $this->getOrdersByStatus($startDate, $endDate),
            ],
            'analytics' => [
                'traffic_sources' => $this->getTrafficSources($startDate, $endDate),
                'conversion_funnel' => $this->getConversionFunnel($startDate, $endDate),
                'geographical_data' => $this->getGeographicalData($startDate, $endDate),
            ]
        ];

        if ($includeCharts) {
            $report['charts'] = [
                'revenue_trend' => $this->getRevenueTrendChart($startDate, $endDate),
                'orders_trend' => $this->getOrdersTrendChart($startDate, $endDate),
                'product_categories' => $this->getProductCategoriesChart($startDate, $endDate),
            ];
        }

        return response()->json([
            'success' => true,
            'report' => $report,
            'generated_at' => now()->toISOString()
        ]);
    }

    // Méthodes privées pour les métriques

    private function getTotalRevenue($period)
    {
        // Exemple de données - remplacez par votre logique réelle
        return [
            'current' => rand(50000, 100000),
            'previous' => rand(40000, 80000),
            'change_percent' => rand(-10, 25)
        ];
    }

    private function getTotalOrders($period)
    {
        return [
            'current' => rand(500, 1500),
            'previous' => rand(400, 1200),
            'change_percent' => rand(-5, 30)
        ];
    }

    private function getTotalCustomers($period)
    {
        return [
            'current' => rand(200, 800),
            'previous' => rand(180, 700),
            'change_percent' => rand(0, 20)
        ];
    }

    private function getAverageOrderValue($period)
    {
        return [
            'current' => rand(80, 150),
            'previous' => rand(70, 140),
            'change_percent' => rand(-5, 15)
        ];
    }

    private function getRevenueGrowth($period)
    {
        return rand(-10, 35);
    }

    private function getOrdersGrowth($period)
    {
        return rand(-5, 25);
    }

    private function getCustomersGrowth($period)
    {
        return rand(0, 20);
    }

    private function getConversionRate($period)
    {
        return round(rand(200, 500) / 100, 2);
    }

    private function getTotalProducts()
    {
        return rand(100, 500);
    }

    private function getLowStockCount()
    {
        return rand(5, 25);
    }

    private function getOutOfStockCount()
    {
        return rand(0, 10);
    }

    private function getTopSellingProducts($limit)
    {
        $products = [];
        for ($i = 1; $i <= $limit; $i++) {
            $products[] = [
                'id' => $i,
                'name' => "Produit Top $i",
                'sales' => rand(50, 200),
                'revenue' => rand(1000, 5000)
            ];
        }
        return $products;
    }

    private function getTotalVisitors($period)
    {
        return rand(1000, 5000);
    }

    private function getUniqueVisitors($period)
    {
        return rand(800, 4000);
    }

    private function getPageViews($period)
    {
        return rand(5000, 20000);
    }

    private function getBounceRate($period)
    {
        return round(rand(30, 70), 1);
    }

    private function getSalesChartData($period)
    {
        $data = [];
        $days = $this->getPeriodDays($period);
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = [
                'date' => now()->subDays($i)->format('Y-m-d'),
                'value' => rand(500, 2000)
            ];
        }
        
        return $data;
    }

    private function getOrdersChartData($period)
    {
        $data = [];
        $days = $this->getPeriodDays($period);
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = [
                'date' => now()->subDays($i)->format('Y-m-d'),
                'value' => rand(10, 50)
            ];
        }
        
        return $data;
    }

    private function getVisitorsChartData($period)
    {
        $data = [];
        $days = $this->getPeriodDays($period);
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = [
                'date' => now()->subDays($i)->format('Y-m-d'),
                'unique_visitors' => rand(50, 200),
                'page_views' => rand(200, 800)
            ];
        }
        
        return $data;
    }

    private function getProductsChartData($period)
    {
        return [
            ['category' => 'Électronique', 'sales' => rand(100, 300)],
            ['category' => 'Vêtements', 'sales' => rand(80, 250)],
            ['category' => 'Maison', 'sales' => rand(60, 200)],
            ['category' => 'Sports', 'sales' => rand(40, 150)],
            ['category' => 'Livres', 'sales' => rand(30, 100)],
        ];
    }

    private function getRevenueByCategoryData($period)
    {
        return [
            ['category' => 'Électronique', 'revenue' => rand(10000, 30000)],
            ['category' => 'Vêtements', 'revenue' => rand(8000, 25000)],
            ['category' => 'Maison', 'revenue' => rand(6000, 20000)],
            ['category' => 'Sports', 'revenue' => rand(4000, 15000)],
            ['category' => 'Livres', 'revenue' => rand(2000, 8000)],
        ];
    }

    private function getCustomerAcquisitionData($period)
    {
        $data = [];
        $weeks = min(12, ceil($this->getPeriodDays($period) / 7));
        
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $data[] = [
                'week' => 'Semaine ' . ($weeks - $i),
                'new_customers' => rand(20, 80),
                'returning_customers' => rand(30, 100)
            ];
        }
        
        return $data;
    }

    private function getPeriodDays($period)
    {
        return match($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '365days' => 365,
            default => 30,
        };
    }

    // Méthodes pour le rapport complet

    private function getRevenueForPeriod($startDate, $endDate)
    {
        return rand(20000, 80000);
    }

    private function getOrdersForPeriod($startDate, $endDate)
    {
        return rand(200, 1000);
    }

    private function getCustomersForPeriod($startDate, $endDate)
    {
        return rand(100, 500);
    }

    private function getAverageOrderValueForPeriod($startDate, $endDate)
    {
        return round(rand(6000, 12000) / 100, 2);
    }

    private function getBestSellingProducts($startDate, $endDate)
    {
        return [
            ['name' => 'Produit A', 'quantity' => 150, 'revenue' => 15000],
            ['name' => 'Produit B', 'quantity' => 120, 'revenue' => 12000],
            ['name' => 'Produit C', 'quantity' => 100, 'revenue' => 10000],
        ];
    }

    private function getTopCustomers($startDate, $endDate)
    {
        return [
            ['name' => 'Client Premium', 'orders' => 15, 'total_spent' => 2500],
            ['name' => 'Client VIP', 'orders' => 12, 'total_spent' => 2200],
            ['name' => 'Client Fidèle', 'orders' => 10, 'total_spent' => 1800],
        ];
    }

    private function getRevenueByDay($startDate, $endDate)
    {
        $data = [];
        $start = now()->parse($startDate);
        $end = now()->parse($endDate);
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => rand(500, 2000)
            ];
        }
        
        return $data;
    }

    private function getOrdersByStatus($startDate, $endDate)
    {
        return [
            'pending' => rand(10, 50),
            'processing' => rand(15, 60),
            'shipped' => rand(20, 80),
            'delivered' => rand(100, 300),
            'cancelled' => rand(5, 25),
        ];
    }

    private function getTrafficSources($startDate, $endDate)
    {
        return [
            'organic' => rand(40, 60),
            'social' => rand(20, 35),
            'direct' => rand(15, 25),
            'referral' => rand(10, 20),
            'email' => rand(5, 15),
        ];
    }

    private function getConversionFunnel($startDate, $endDate)
    {
        return [
            'visitors' => 10000,
            'product_views' => 8000,
            'add_to_cart' => 2000,
            'checkout' => 800,
            'purchase' => 500,
        ];
    }

    private function getGeographicalData($startDate, $endDate)
    {
        return [
            ['country' => 'France', 'orders' => rand(200, 500), 'revenue' => rand(20000, 50000)],
            ['country' => 'Belgique', 'orders' => rand(50, 150), 'revenue' => rand(5000, 15000)],
            ['country' => 'Suisse', 'orders' => rand(30, 100), 'revenue' => rand(3000, 10000)],
            ['country' => 'Canada', 'orders' => rand(20, 80), 'revenue' => rand(2000, 8000)],
        ];
    }

    private function getRevenueTrendChart($startDate, $endDate)
    {
        return $this->getRevenueByDay($startDate, $endDate);
    }

    private function getOrdersTrendChart($startDate, $endDate)
    {
        $data = [];
        $start = now()->parse($startDate);
        $end = now()->parse($endDate);
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'orders' => rand(5, 25)
            ];
        }
        
        return $data;
    }

    private function getProductCategoriesChart($startDate, $endDate)
    {
        return $this->getRevenueByCategoryData('');
    }
}