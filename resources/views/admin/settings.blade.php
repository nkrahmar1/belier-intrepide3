@extends('layouts.admin')

@section('title', 'Paramètres')

@push('styles')
<style>
    .setting-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .setting-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .toggle-switch {
        appearance: none;
        width: 50px;
        height: 24px;
        background: #e5e7eb;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .toggle-switch:checked {
        background: #10b981;
    }
    .toggle-switch::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        top: 2px;
        left: 2px;
        transition: transform 0.3s;
    }
    .toggle-switch:checked::before {
        transform: translateX(26px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-yellow-50 to-red-50 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4">
                    ⚙️
                </span>
                Paramètres du Site
            </h1>
            <p class="text-gray-600 mt-2">Configurez tous les aspects de votre site de journal</p>
        </div>
        <button class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <i class="fas fa-save mr-2"></i>Sauvegarder
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Configuration générale -->
        <div class="setting-card bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-blue-600 text-lg">🌐</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Configuration Générale</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du site</label>
                    <input type="text" value="{{ $configs['site_name'] ?? 'Bélier Intrépide' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" rows="3">{{ $configs['site_description'] ?? 'Journal d\'actualités en ligne' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                    <input type="email" value="{{ $configs['contact_email'] ?? 'contact@belierintrepide.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Paramètres des articles -->
        <div class="setting-card bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-green-600 text-lg">📰</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Articles</h3>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Modération des commentaires</label>
                        <p class="text-xs text-gray-500">Approuver manuellement les commentaires</p>
                    </div>
                    <input type="checkbox" class="toggle-switch" {{ ($configs['moderate_comments'] ?? true) ? 'checked' : '' }}>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Articles en vedette</label>
                        <p class="text-xs text-gray-500">Afficher une section articles en vedette</p>
                    </div>
                    <input type="checkbox" class="toggle-switch" {{ ($configs['featured_articles'] ?? true) ? 'checked' : '' }}>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Articles par page</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="10" {{ ($configs['articles_per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ ($configs['articles_per_page'] ?? 10) == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ ($configs['articles_per_page'] ?? 10) == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Paramètres d'email -->
        <div class="setting-card bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-purple-600 text-lg">✉️</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Configuration Email</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Serveur SMTP</label>
                    <input type="text" value="{{ $configs['smtp_host'] ?? 'smtp.gmail.com' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Port</label>
                        <input type="number" value="{{ $configs['smtp_port'] ?? 587 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chiffrement</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="tls" {{ ($configs['smtp_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($configs['smtp_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sécurité -->
        <div class="setting-card bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-red-600 text-lg">🔒</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Sécurité</h3>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Double authentification</label>
                        <p class="text-xs text-gray-500">Activer 2FA pour les administrateurs</p>
                    </div>
                    <input type="checkbox" class="toggle-switch" {{ ($configs['two_factor_auth'] ?? false) ? 'checked' : '' }}>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Connexions automatiques</label>
                        <p class="text-xs text-gray-500">Se souvenir de moi pendant 30 jours</p>
                    </div>
                    <input type="checkbox" class="toggle-switch" {{ ($configs['remember_me'] ?? true) ? 'checked' : '' }}>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée de session (minutes)</label>
                    <input type="number" value="{{ $configs['session_lifetime'] ?? 120 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Analytics -->
        <div class="setting-card bg-white rounded-2xl shadow-lg p-6 lg:col-span-2">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-indigo-600 text-lg">📊</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Analytics & Suivi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Google Analytics</label>
                            <p class="text-xs text-gray-500">Activer le suivi des visiteurs</p>
                        </div>
                        <input type="checkbox" class="toggle-switch" {{ ($configs['google_analytics'] ?? false) ? 'checked' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID de suivi Google</label>
                        <input type="text" value="{{ $configs['google_analytics_id'] ?? '' }}" placeholder="GA-XXXXXXXXX-X" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Cookies</label>
                            <p class="text-xs text-gray-500">Afficher le bandeau de consentement</p>
                        </div>
                        <input type="checkbox" class="toggle-switch" {{ ($configs['cookie_consent'] ?? true) ? 'checked' : '' }}>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Maintenance</label>
                            <p class="text-xs text-gray-500">Activer le mode maintenance</p>
                        </div>
                        <input type="checkbox" class="toggle-switch" {{ ($configs['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions Système</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-3 rounded-xl font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-download mr-2"></i>Sauvegarde BDD
            </button>
            <button class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-3 rounded-xl font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-sync mr-2"></i>Vider le cache
            </button>
            <button class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-3 rounded-xl font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-cog mr-2"></i>Optimiser BDD
            </button>
        </div>
    </div>
</div>
@endsectionyouts.admin')

@section('title', 'Paramètres')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Paramètres</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-700">Configurez ici les paramètres de votre dashboard, gestion des accès, préférences, etc.</p>
    </div>
</div>
@endsection
