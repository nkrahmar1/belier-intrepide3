<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        // Le middleware est déjà appliqué dans les routes
        // $this->middleware(['auth', 'admin']);
    }

    /**
     * Afficher la liste des utilisateurs avec pagination et filtres
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('status', 'active');
                    break;
                case 'inactive':
                    $query->where('status', 'inactive');
                    break;
                case 'pending':
                    $query->whereNull('email_verified_at');
                    break;
                case 'premium':
                    $query->where('is_premium', true);
                    break;
            }
        }

        // Filtrage par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Tri par défaut : les plus récents en premier
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques pour le dashboard
        $stats = [
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'pending_users' => User::whereNull('email_verified_at')->count(),
            'premium_users' => User::where('is_premium', true)->count(),
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'users_this_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'stats' => $stats,
                'html' => view('admin.partials.users-table', compact('users'))->render()
            ]);
        }

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:user,admin,editor',
                'status' => 'required|in:active,inactive,pending',
                'email_verified' => 'boolean',
                'is_premium' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'is_premium' => $request->boolean('is_premium', false),
                'email_verified_at' => $request->boolean('email_verified', false) ? now() : null,
            ];

            $user = User::create($userData);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès !',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Calculer les statistiques de l'utilisateur
            $userStats = [
                'articles_count' => 0, // À remplacer par le vrai count des articles
                'views_count' => 0,    // À remplacer par le vrai count des vues
                'downloads_count' => 0, // À remplacer par le vrai count des téléchargements
                'last_login' => $user->updated_at, // À remplacer par last_login_at si disponible
            ];

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'stats' => $userStats
                ]);
            }

            return view('admin.users.show', compact('user', 'userStats'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:user,admin,editor',
                'status' => 'required|in:active,inactive,pending',
                'email_verified' => 'boolean',
                'is_premium' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'is_premium' => $request->boolean('is_premium', false),
                'email_verified_at' => $request->boolean('email_verified', false) ? now() : null,
            ];

            // Mettre à jour le mot de passe seulement s'il est fourni
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès !',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Empêcher la suppression de son propre compte
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
                ], 403);
            }

            // Empêcher la suppression du dernier admin
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer le dernier administrateur.'
                    ], 403);
                }
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // Empêcher la désactivation de son propre compte
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas désactiver votre propre compte.'
                ], 403);
            }

            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->status = $newStatus;
            $user->save();

            $statusText = $newStatus === 'active' ? 'activé' : 'désactivé';

            return response()->json([
                'success' => true,
                'message' => "Utilisateur {$statusText} avec succès !",
                'status' => $user->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les utilisateurs (CSV)
     */
    public function export(Request $request)
    {
        $users = User::all();
        
        $filename = 'utilisateurs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'Rôle',
                'Statut',
                'Email vérifié',
                'Premium',
                'Date d\'inscription',
                'Dernière mise à jour'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status === 'active' ? 'Actif' : 'Inactif',
                    $user->email_verified_at ? 'Oui' : 'Non',
                    $user->is_premium ? 'Oui' : 'Non',
                    $user->created_at->format('d/m/Y H:i'),
                    $user->updated_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function getStats()
    {
        $stats = [
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'pending_users' => User::whereNull('email_verified_at')->count(),
            'premium_users' => User::where('is_premium', true)->count(),
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'users_this_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'users_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Actions groupées sur les utilisateurs
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,make_premium,remove_premium',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;
        $currentUserId = Auth::id();

        // Empêcher les actions sur son propre compte
        if (in_array($currentUserId, $userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas effectuer cette action sur votre propre compte.'
            ], 403);
        }

        try {
            $count = 0;
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                switch ($action) {
                    case 'activate':
                        $user->update(['status' => 'active']);
                        $count++;
                        break;
                    case 'deactivate':
                        $user->update(['status' => 'inactive']);
                        $count++;
                        break;
                    case 'delete':
                        // Ne pas supprimer les admins s'il n'en reste qu'un
                        if ($user->role !== 'admin' || User::where('role', 'admin')->count() > 1) {
                            $user->delete();
                            $count++;
                        }
                        break;
                    case 'make_premium':
                        $user->update(['is_premium' => true]);
                        $count++;
                        break;
                    case 'remove_premium':
                        $user->update(['is_premium' => false]);
                        $count++;
                        break;
                }
            }

            $actionNames = [
                'activate' => 'activés',
                'deactivate' => 'désactivés',
                'delete' => 'supprimés',
                'make_premium' => 'passés en Premium',
                'remove_premium' => 'retirés du Premium'
            ];

            return response()->json([
                'success' => true,
                'message' => "{$count} utilisateur(s) {$actionNames[$action]} avec succès !"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'action groupée : ' . $e->getMessage()
            ], 500);
        }
    }
}
