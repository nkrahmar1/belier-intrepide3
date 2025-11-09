@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('styles')
<canvas id="salesChart"></canvas>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="{{ asset('js/dashboard-advanced.js') }}"></script>
@endsection
<!-- Dans le <head> de votre layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Admin</h1>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 16v-4m-8 4h16a1 1 0 001-1V8a1 1 0 00-1-1H7a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        <span>{{ now()->translatedFormat('l j F Y') }}</span>
                        <span class="ml-2" id="current-time">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Rechercher..." id="search-input" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <select id="period-select" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="7d">7 derniers jours</option>
                        <option value="30d">30 derniers jours</option>
                        <option value="90d">90 derniers jours</option>
                    </select>

                    <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5-5 5h5zm0 0v3"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="px-6 py-6">
        {{-- Tout le contenu supprimé pour ne rien afficher --}}
    </div>
</div>

<!-- Chatbot Widget -->
<div id="chatbot-widget" class="fixed bottom-6 right-6 z-50">
    <div id="chatbot-toggle" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center w-14 h-14 cursor-pointer">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10 0V6a4 4 0 00-8 0v2m8 0H7" />
        </svg>
    </div>
    <div id="chatbot-box" class="bg-white rounded-xl shadow-2xl w-80 h-96 flex flex-col overflow-hidden mt-2">
        <div class="bg-blue-600 text-white px-4 py-3 flex items-center justify-between">
            <span class="font-semibold">Assistant IA</span>
            <button id="chatbot-close" class="text-white hover:text-gray-200 focus:outline-none">&times;</button>
        </div>
        <div id="chatbot-messages" class="flex-1 px-4 py-2 overflow-y-auto text-sm space-y-2 bg-gray-50"></div>
        <form id="chatbot-form" class="flex items-center border-t border-gray-200">
            <input id="chatbot-input" type="text" class="flex-1 px-3 py-2 border-none focus:ring-0 bg-transparent" placeholder="Écrivez votre message..." autocomplete="off" />
            <button type="submit" class="px-4 py-2 text-blue-600 font-semibold hover:text-blue-800">Envoyer</button>
        </form>
    </div>
</div>

@section('scripte')

<script>
// Chatbot widget logic
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('chatbot-toggle');
    const box = document.getElementById('chatbot-box');
    const close = document.getElementById('chatbot-close');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');

    // Initialize chatbot as hidden
    box.classList.add('hidden');

    toggle.addEventListener('click', () => {
        box.classList.toggle('hidden');
        if (!box.classList.contains('hidden')) {
            input.focus();
        }
    });
    close.addEventListener('click', () => box.classList.add('hidden'));

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const userMsg = input.value.trim();
        if (!userMsg) return;
        addMessage('Vous', userMsg, 'right');
        input.value = '';
        // Appel AJAX fictif, à remplacer par votre backend ou API IA
        setTimeout(() => {
            addMessage('Bot', 'Je suis un assistant IA. (Connectez-moi à une API pour des réponses dynamiques)', 'left');
        }, 800);
    });

    function addMessage(sender, text, align = 'left') {
        const msg = document.createElement('div');
        msg.className = `flex ${align === 'right' ? 'justify-end' : 'justify-start'}`;
        msg.innerHTML = `<div class="max-w-xs px-3 py-2 rounded-lg ${align === 'right' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'} shadow">` +
            `<span class="block font-semibold text-xs mb-1">${sender}</span>${text}</div>`;
        messages.appendChild(msg);
        messages.scrollTop = messages.scrollHeight;
    }
});

@endsection


/**
 * Dashboard Admin - Script Amélioré
 * Version: 2.0
 * Fonctionnalités: Graphiques, animations, recherche, notifications, chatbot
 */

class DashboardManager {
    constructor() {
        this.isInitialized = false;
        this.refreshInterval = null;
        this.chartInstance = null;
        this.debounceTimers = {};
        this.config = {
            refreshRate: 300000, // 5 minutes
            animationDuration: 500,
            debounceDelay: 300
        };
        
        this.init();
    }

    // === INITIALISATION ===
    init() {
        if (this.isInitialized) return;
        
        document.addEventListener('DOMContentLoaded', () => {
            this.setupEventListeners();
            this.initializeComponents();
            this.startAutoRefresh();
            this.setupKeyboardShortcuts();
            this.initializeTheme();
            this.preloadContent();
            
            console.log('📊 Dashboard initialisé avec succès');
            this.showToast('Dashboard chargé', 'success');
        });
        
        this.isInitialized = true;
    }

    // === GESTION DU TEMPS ===
    updateClock() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        const dateElement = document.getElementById('current-date');
        
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('fr-FR');
        }
        
        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('fr-FR', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }

    startClock() {
        this.updateClock();
        setInterval(() => this.updateClock(), 1000);
    }

    // === GRAPHIQUES AVANCÉS ===
    drawSalesChart(customData = null) {
        const canvas = document.getElementById('salesCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const data = customData || [4000, 3000, 2000, 2780, 1890, 2390, 3490];
        const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul'];

        // Configuration responsive
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);

        const padding = 50;
        const chartWidth = rect.width - 2 * padding;
        const chartHeight = rect.height - 2 * padding;
        const maxValue = Math.max(...data);
        const minValue = Math.min(...data);
        const valueRange = maxValue - minValue || 1;

        // Nettoyer avec dégradé de fond
        const gradient = ctx.createLinearGradient(0, 0, 0, rect.height);
        gradient.addColorStop(0, '#f8fafc');
        gradient.addColorStop(1, '#ffffff');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, rect.width, rect.height);

        // Grille de fond
        this.drawGrid(ctx, padding, rect.width, rect.height, chartWidth, chartHeight);

        // Courbe avec dégradé
        this.drawSmoothCurve(ctx, data, padding, rect.width, rect.height, chartWidth, chartHeight, minValue, valueRange);

        // Points interactifs
        this.drawDataPoints(ctx, data, padding, rect.width, rect.height, chartWidth, chartHeight, minValue, valueRange);

        // Labels avec style amélioré
        this.drawChartLabels(ctx, labels, padding, rect.width, rect.height, chartWidth);

        // Légende des valeurs
        this.drawValueLabels(ctx, data, padding, rect.width, rect.height, chartWidth, chartHeight, minValue, valueRange);
    }

    drawGrid(ctx, padding, width, height, chartWidth, chartHeight) {
        ctx.strokeStyle = '#e2e8f0';
        ctx.lineWidth = 0.5;
        ctx.setLineDash([2, 2]);

        // Lignes horizontales
        for (let i = 0; i <= 5; i++) {
            const y = padding + (i * chartHeight) / 5;
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(width - padding, y);
            ctx.stroke();
        }

        // Lignes verticales
        for (let i = 0; i <= 6; i++) {
            const x = padding + (i * chartWidth) / 6;
            ctx.beginPath();
            ctx.moveTo(x, padding);
            ctx.lineTo(x, height - padding);
            ctx.stroke();
        }

        ctx.setLineDash([]);
    }

    drawSmoothCurve(ctx, data, padding, width, height, chartWidth, chartHeight, minValue, valueRange) {
        // Zone sous la courbe
        const areaGradient = ctx.createLinearGradient(0, padding, 0, height - padding);
        areaGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        areaGradient.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

        ctx.fillStyle = areaGradient;
        ctx.beginPath();

        const points = data.map((value, index) => ({
            x: padding + (index * chartWidth) / (data.length - 1),
            y: height - padding - ((value - minValue) / valueRange) * chartHeight
        }));

        // Courbe de Bézier lissée
        ctx.moveTo(points[0].x, points[0].y);
        for (let i = 1; i < points.length; i++) {
            const prev = points[i - 1];
            const curr = points[i];
            const cp1x = prev.x + (curr.x - prev.x) * 0.3;
            const cp2x = curr.x - (curr.x - prev.x) * 0.3;
            ctx.bezierCurveTo(cp1x, prev.y, cp2x, curr.y, curr.x, curr.y);
        }

        // Fermer la zone pour le remplissage
        ctx.lineTo(points[points.length - 1].x, height - padding);
        ctx.lineTo(points[0].x, height - padding);
        ctx.closePath();
        ctx.fill();

        // Ligne de la courbe
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(points[0].x, points[0].y);
        for (let i = 1; i < points.length; i++) {
            const prev = points[i - 1];
            const curr = points[i];
            const cp1x = prev.x + (curr.x - prev.x) * 0.3;
            const cp2x = curr.x - (curr.x - prev.x) * 0.3;
            ctx.bezierCurveTo(cp1x, prev.y, cp2x, curr.y, curr.x, curr.y);
        }
        ctx.stroke();
    }

    drawDataPoints(ctx, data, padding, width, height, chartWidth, chartHeight, minValue, valueRange) {
        data.forEach((value, index) => {
            const x = padding + (index * chartWidth) / (data.length - 1);
            const y = height - padding - ((value - minValue) / valueRange) * chartHeight;

            // Cercle extérieur
            ctx.beginPath();
            ctx.arc(x, y, 6, 0, 2 * Math.PI);
            ctx.fillStyle = '#ffffff';
            ctx.fill();
            ctx.strokeStyle = '#3b82f6';
            ctx.lineWidth = 2;
            ctx.stroke();

            // Point central
            ctx.beginPath();
            ctx.arc(x, y, 3, 0, 2 * Math.PI);
            ctx.fillStyle = '#3b82f6';
            ctx.fill();
        });
    }

    drawChartLabels(ctx, labels, padding, width, height, chartWidth) {
        ctx.fillStyle = '#64748b';
        ctx.font = 'bold 11px system-ui, -apple-system, sans-serif';
        ctx.textAlign = 'center';

        labels.forEach((label, index) => {
            const x = padding + (index * chartWidth) / (labels.length - 1);
            ctx.fillText(label, x, height - padding + 25);
        });
    }

    drawValueLabels(ctx, data, padding, width, height, chartWidth, chartHeight, minValue, valueRange) {
        ctx.fillStyle = '#475569';
        ctx.font = '10px system-ui, -apple-system, sans-serif';
        ctx.textAlign = 'left';

        // Valeurs sur l'axe Y
        for (let i = 0; i <= 5; i++) {
            const value = minValue + (valueRange * i) / 5;
            const y = height - padding - (i * chartHeight) / 5;
            ctx.fillText(Math.round(value).toLocaleString() + '€', 10, y + 3);
        }
    }

    // === ANIMATIONS AVANCÉES ===
    animateCounters() {
        const counters = document.querySelectorAll('[data-counter]');
        
        counters.forEach(counter => {
            const target = parseFloat(counter.dataset.counter) || 0;
            const isPercentage = counter.textContent.includes('%');
            const isCurrency = counter.textContent.includes('€');
            
            this.animateValue(counter, 0, target, this.config.animationDuration, (value) => {
                let formatted = Math.floor(value).toLocaleString('fr-FR');
                
                if (isCurrency) {
                    formatted = formatted + '€';
                } else if (isPercentage) {
                    formatted = (value / 100).toFixed(1) + '%';
                }
                
                counter.textContent = formatted;
            });
        });
    }

    animateValue(element, start, end, duration, callback) {
        const startTime = performance.now();
        const change = end - start;

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (ease-out)
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = start + (change * easeOut);
            
            callback(current);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    // === RECHERCHE INTELLIGENTE ===
    setupSmartSearch() {
        const searchInput = document.getElementById('search-input');
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            this.debounce('search', () => {
                this.performSearch(e.target.value);
            }, this.config.debounceDelay);
        });

        // Suggestions en temps réel
        searchInput.addEventListener('focus', () => {
            this.showSearchSuggestions();
        });

        searchInput.addEventListener('blur', () => {
            setTimeout(() => this.hideSearchSuggestions(), 200);
        });
    }

    performSearch(term) {
        const searchTerm = term.toLowerCase().trim();
        
        if (searchTerm.length < 2) {
            this.clearSearchResults();
            return;
        }

        // Recherche dans les éléments visibles
        const searchableElements = document.querySelectorAll('[data-searchable]');
        const results = [];

        searchableElements.forEach(element => {
            const text = element.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                results.push({
                    element,
                    relevance: this.calculateRelevance(text, searchTerm)
                });
            }
        });

        this.displaySearchResults(results.sort((a, b) => b.relevance - a.relevance));
        
        // Analytics de recherche
        this.trackSearch(searchTerm, results.length);
    }

    calculateRelevance(text, term) {
        const exactMatch = text.includes(term) ? 10 : 0;
        const wordStart = text.split(' ').some(word => word.startsWith(term)) ? 5 : 0;
        const frequency = (text.match(new RegExp(term, 'g')) || []).length;
        
        return exactMatch + wordStart + frequency;
    }

    showSearchSuggestions() {
        const suggestions = [
            'Ventes du mois',
            'Commandes récentes',
            'Top produits',
            'Statistiques',
            'Utilisateurs actifs'
        ];

        this.displaySuggestions(suggestions);
    }

    // === NOTIFICATIONS AVANCÉES ===
    initializeNotifications() {
        this.checkForNewNotifications();
        setInterval(() => this.checkForNewNotifications(), 60000); // Chaque minute
    }

    async checkForNewNotifications() {
        try {
            const response = await fetch('/admin/notifications/check', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            
            if (data.newNotifications > 0) {
                this.updateNotificationBadge(data.count);
                this.showDesktopNotification(data.latestNotification);
            }
        } catch (error) {
            console.error('Erreur notifications:', error);
        }
    }

    updateNotificationBadge(count) {
        let badge = document.querySelector('.notification-badge');
        
        if (count > 0) {
            if (!badge) {
                badge = this.createNotificationBadge();
            }
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else if (badge) {
            badge.classList.add('hidden');
        }
    }

    showDesktopNotification(notification) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(notification.title, {
                body: notification.message,
                icon: '/favicon.ico',
                tag: 'dashboard-notification'
            });
        }
    }

    // === SYSTÈME DE TOASTS AVANCÉ ===
    showToast(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${icons[type]}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Container pour les toasts
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }

        container.appendChild(toast);

        // Animation d'entrée
        requestAnimationFrame(() => {
            toast.classList.add('toast-show');
        });

        // Auto-suppression
        setTimeout(() => {
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // === GESTION DES PÉRIODES ET FILTRES ===
    setupPeriodFilter() {
        const periodSelect = document.getElementById('period-select');
        if (!periodSelect) return;

        periodSelect.addEventListener('change', (e) => {
            this.changePeriod(e.target.value);
        });
    }

    async changePeriod(period) {
        this.showLoadingState();
        
        try {
            const response = await fetch(`/admin/dashboard/data?period=${period}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            this.updateDashboardData(data);
            this.showToast(`Données mises à jour pour ${period}`, 'success');
        } catch (error) {
            this.showToast('Erreur lors du changement de période', 'error');
            console.error('Erreur période:', error);
        } finally {
            this.hideLoadingState();
        }
    }

    // === ACTUALISATION AUTOMATIQUE ===
    startAutoRefresh() {
        this.refreshInterval = setInterval(() => {
            this.refreshDashboardData();
        }, this.config.refreshRate);
    }

    async refreshDashboardData() {
        try {
            const response = await fetch('/admin/dashboard/data', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            this.updateDashboardData(data);
            
            // Mise à jour discrète
            const lastUpdate = document.getElementById('last-update');
            if (lastUpdate) {
                lastUpdate.textContent = `Mis à jour: ${new Date().toLocaleTimeString('fr-FR')}`;
            }
        } catch (error) {
            console.error('Erreur rafraîchissement:', error);
        }
    }

    updateDashboardData(data) {
        // Mise à jour des métriques
        if (data.metrics) {
            Object.entries(data.metrics).forEach(([key, value]) => {
                const element = document.querySelector(`[data-metric="${key}"]`);
                if (element) {
                    this.animateMetricUpdate(element, value);
                }
            });
        }

        // Mise à jour du graphique
        if (data.salesData) {
            this.drawSalesChart(data.salesData);
        }

        // Mise à jour des listes
        if (data.recentOrders) {
            this.updateRecentOrders(data.recentOrders);
        }
    }

    // === RACCOURCIS CLAVIER ===
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K : Recherche
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('search-input')?.focus();
            }

            // Ctrl/Cmd + R : Actualiser
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                this.refreshDashboardData();
                this.showToast('Dashboard actualisé', 'info');
            }

            // Échap : Fermer les modales/recherche
            if (e.key === 'Escape') {
                this.closeModals();
                const searchInput = document.getElementById('search-input');
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.blur();
                }
            }

            // F1 : Aide
            if (e.key === 'F1') {
                e.preventDefault();
                this.showHelpModal();
            }
        });
    }

    // === THÈME ET PRÉFÉRENCES ===
    initializeTheme() {
        const savedTheme = localStorage.getItem('dashboard-theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        // Détection automatique du mode sombre
        if (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            this.setTheme('dark');
        }
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('dashboard-theme', theme);
        this.showToast(`Thème ${theme} activé`, 'info');
    }

    // === UTILITAIRES ===
    debounce(key, func, delay) {
        clearTimeout(this.debounceTimers[key]);
        this.debounceTimers[key] = setTimeout(func, delay);
    }

    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    showLoadingState() {
        const loader = document.createElement('div');
        loader.id = 'dashboard-loader';
        loader.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span>Chargement...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loader);
    }

    hideLoadingState() {
        document.getElementById('dashboard-loader')?.remove();
    }

    // === INITIALISATION DES COMPOSANTS ===
    initializeComponents() {
        this.startClock();
        this.drawSalesChart();
        this.setupSmartSearch();
        this.setupPeriodFilter();
        this.initializeNotifications();
        
        // Animation initiale des compteurs
        setTimeout(() => {
            this.animateCounters();
        }, 500);

        // Effets sur les cartes
        this.setupCardEffects();
        
        // Performance monitoring
        this.monitorPerformance();
    }

    setupCardEffects() {
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
                card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '';
            });
        });
    }

    monitorPerformance() {
        // Observer les performances
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.duration > 100) {
                        console.warn(`Performance: ${entry.name} took ${entry.duration}ms`);
                    }
                });
            });

            observer.observe({ entryTypes: ['measure', 'navigation'] });
        }
    }

    preloadContent() {
        // Préchargement intelligent des ressources
        const links = document.querySelectorAll('a[href^="/admin/"]');
        links.forEach(link => {
            link.addEventListener('mouseenter', () => {
                const preloadLink = document.createElement('link');
                preloadLink.rel = 'prefetch';
                preloadLink.href = link.href;
                document.head.appendChild(preloadLink);
            }, { once: true });
        });
    }

    // === MÉTHODES D'EXPORT ===
    async exportData(type = 'csv') {
        const period = document.getElementById('period-select')?.value || '30d';
        
        try {
            this.showLoadingState();
            
            const response = await fetch(`/admin/dashboard/export?type=${type}&period=${period}`, {
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `dashboard-export-${period}.${type}`;
                a.click();
                window.URL.revokeObjectURL(url);
                
                this.showToast(`Export ${type.toUpperCase()} réussi`, 'success');
            } else {
                throw new Error('Erreur d\'export');
            }
        } catch (error) {
            this.showToast('Erreur lors de l\'export', 'error');
            console.error('Erreur export:', error);
        } finally {
            this.hideLoadingState();
        }
    }

    // === NETTOYAGE ===
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        Object.values(this.debounceTimers).forEach(timer => {
            clearTimeout(timer);
        });
        
        console.log('Dashboard détruit proprement');
    }
}

// === STYLES CSS INJECTÉS ===
const dashboardStyles = `
<style>
.toast {
    @apply bg-white rounded-lg shadow-lg border p-4 transform translate-x-full transition-transform duration-300 ease-out;
}

.toast-show {
    @apply translate-x-0;
}

.toast-hide {
    @apply translate-x-full opacity-0;
}

.toast-success { @apply border-green-200 bg-green-50; }
.toast-error { @apply border-red-200 bg-red-50; }
.toast-warning { @apply border-yellow-200 bg-yellow-50; }
.toast-info { @apply border-blue-200 bg-blue-50; }

.toast-content {
    @apply flex items-center space-x-3;
}

.toast-close {
    @apply ml-auto text-gray-400 hover:text-gray-600 font-bold text-lg leading-none;
}

.dashboard-card {
    @apply transition-all duration-200 ease-out;
}

[data-theme="dark"] {
    @apply bg-gray-900 text-white;
}

[data-theme="dark"] .bg-white {
    @apply bg-gray-800;
}

[data-theme="dark"] .text-gray-900 {
    @apply text-gray-100;
}

@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-pulse-slow {
    animation: pulse-slow 2s ease-in-out infinite;
}
</style>
`;

// Injection des styles
document.head.insertAdjacentHTML('beforeend', dashboardStyles);

// === INITIALISATION GLOBALE ===
const dashboard = new DashboardManager();

// Exposition globale pour utilisation dans les templates
window.Dashboard = dashboard;

// === FONCTIONS UTILITAIRES GLOBALES ===
window.exportData = (type) => dashboard.exportData(type);
window.toggleTheme = () => {
    const current = document.documentElement.getAttribute('data-theme');
    dashboard.setTheme(current === 'dark' ? 'light' : 'dark');
};
window.refreshDashboard = () => dashboard.refreshDashboardData();

// Nettoyage à la fermeture
window.addEventListener('beforeunload', () => {
    dashboard.destroy();
});

console.log('🚀 Dashboard script avancé chargé !');

// Accès global au dashboard
window.Dashboard.showToast('Message personnalisé', 'success');
window.Dashboard.refreshDashboardData();
window.Dashboard.setTheme('dark');

// Export de données
window.exportData('csv');
window.refreshDashboard();
</script>
@endsection
