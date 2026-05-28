<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    /**
     * Statut du système
     */
    public function status()
    {
        $status = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStoragePermissions(),
            'queue' => $this->checkQueueStatus(),
            'mail' => $this->checkMailConfiguration(),
            'system_info' => $this->getSystemInfo(),
            'performance' => $this->getPerformanceMetrics(),
        ];

        return response()->json($status);
    }

    /**
     * Logs système
     */
    public function logs(Request $request)
    {
        $type = $request->get('type', 'laravel'); // laravel, apache, nginx, etc.
        $lines = $request->get('lines', 100);
        
        try {
            $logPath = $this->getLogPath($type);
            
            if (!File::exists($logPath)) {
                return response()->json([
                    'error' => 'Fichier de log non trouvé',
                    'path' => $logPath
                ], 404);
            }

            $logs = $this->readLastLines($logPath, $lines);
            
            return response()->json([
                'logs' => $logs,
                'file_size' => File::size($logPath),
                'last_modified' => File::lastModified($logPath),
                'path' => $logPath
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la lecture des logs : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vider le cache
     */
    public function clearCache(Request $request)
    {
        $type = $request->get('type', 'all'); // all, config, route, view, cache
        
        try {
            switch ($type) {
                case 'config':
                    Artisan::call('config:clear');
                    $message = 'Cache de configuration vidé';
                    break;
                    
                case 'route':
                    Artisan::call('route:clear');
                    $message = 'Cache des routes vidé';
                    break;
                    
                case 'view':
                    Artisan::call('view:clear');
                    $message = 'Cache des vues vidé';
                    break;
                    
                case 'cache':
                    Artisan::call('cache:clear');
                    $message = 'Cache applicatif vidé';
                    break;
                    
                case 'all':
                default:
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');
                    $message = 'Tous les caches ont été vidés';
                    break;
            }

            Log::info('Cache cleared', ['type' => $type, 'user' => Auth::id()]);
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du vidage du cache : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mode maintenance
     */
    public function maintenance($action, Request $request)
    {
        try {
            switch ($action) {
                case 'enable':
                    $message = $request->get('message', 'Site en maintenance');
                    $retry = $request->get('retry', 60);
                    
                    Artisan::call('down', [
                        '--message' => $message,
                        '--retry' => $retry,
                        '--allow' => $request->get('allow_ips', [])
                    ]);
                    
                    Log::warning('Maintenance mode enabled', ['user' => Auth::id()]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Mode maintenance activé'
                    ]);
                    
                case 'disable':
                    Artisan::call('up');
                    
                    Log::info('Maintenance mode disabled', ['user' => Auth::id()]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Mode maintenance désactivé'
                    ]);
                    
                default:
                    return response()->json([
                        'error' => 'Action non reconnue'
                    ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la gestion du mode maintenance : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier la connexion à la base de données
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'OK',
                'message' => 'Connexion à la base de données réussie'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => 'Erreur de connexion à la base de données : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier la connexion au cache
     */
    private function checkCacheConnection()
    {
        try {
            Cache::put('system_check', 'test', 10);
            $value = Cache::get('system_check');
            Cache::forget('system_check');
            
            return [
                'status' => $value === 'test' ? 'OK' : 'ERROR',
                'message' => $value === 'test' ? 'Cache fonctionnel' : 'Problème avec le cache'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => 'Erreur de cache : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier les permissions de stockage
     */
    private function checkStoragePermissions()
    {
        $paths = [
            storage_path('app'),
            storage_path('logs'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        $issues = [];
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $issues[] = $path;
            }
        }

        return [
            'status' => empty($issues) ? 'OK' : 'WARNING',
            'message' => empty($issues) ? 'Toutes les permissions sont correctes' : 'Problèmes de permissions',
            'issues' => $issues
        ];
    }

    /**
     * Vérifier le statut de la queue
     */
    private function checkQueueStatus()
    {
        try {
            // Vérifier s'il y a des jobs en échec
            $failedJobs = DB::table('failed_jobs')->count();
            $pendingJobs = DB::table('jobs')->count();
            
            return [
                'status' => 'OK',
                'message' => 'Queue opérationnelle',
                'failed_jobs' => $failedJobs,
                'pending_jobs' => $pendingJobs
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => 'Erreur de queue : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier la configuration mail
     */
    private function checkMailConfiguration()
    {
        $mailDriver = config('mail.default');
        
        return [
            'status' => $mailDriver ? 'OK' : 'WARNING',
            'message' => $mailDriver ? "Mail configuré ($mailDriver)" : 'Mail non configuré',
            'driver' => $mailDriver
        ];
    }

    /**
     * Obtenir les informations système
     */
    private function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'disk_space' => [
                'free' => $this->formatBytes(disk_free_space('/')),
                'total' => $this->formatBytes(disk_total_space('/'))
            ]
        ];
    }

    /**
     * Obtenir les métriques de performance
     */
    private function getPerformanceMetrics()
    {
        return [
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
            'load_time' => round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms'
        ];
    }

    /**
     * Obtenir le chemin du fichier de log
     */
    private function getLogPath($type)
    {
        return match($type) {
            'laravel' => storage_path('logs/laravel.log'),
            'apache' => '/var/log/apache2/error.log',
            'nginx' => '/var/log/nginx/error.log',
            default => storage_path('logs/laravel.log'),
        };
    }

    /**
     * Lire les dernières lignes d'un fichier
     */
    private function readLastLines($file, $lines = 100)
    {
        $handle = fopen($file, 'r');
        $buffer = '';
        $linesRead = 0;
        
        // Se positionner à la fin du fichier
        fseek($handle, -1, SEEK_END);
        
        while ($linesRead < $lines && ftell($handle) > 0) {
            $char = fgetc($handle);
            if ($char === "\n") {
                $linesRead++;
            }
            $buffer = $char . $buffer;
            fseek($handle, -2, SEEK_CUR);
        }
        
        fclose($handle);
        return explode("\n", trim($buffer));
    }

    /**
     * Formater les bytes en unité lisible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}