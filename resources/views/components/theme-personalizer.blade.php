<!-- Theme Personalization Component -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🎨 Personnalisation des Thèmes</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Sélectionnez un thème de couleur pour personnaliser votre interface</p>
    </div>

    <div x-data="themeManager()" class="space-y-6">
        <!-- Theme Selection -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Green Theme (Default) -->
            <button 
                @click="setTheme('green')" 
                :class="currentTheme === 'green' ? 'ring-2 ring-offset-2 ring-green-500' : ''"
                class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-green-500 transition-all text-center"
            >
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Vert</p>
            </button>

            <!-- Blue Theme -->
            <button 
                @click="setTheme('blue')" 
                :class="currentTheme === 'blue' ? 'ring-2 ring-offset-2 ring-blue-500' : ''"
                class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 transition-all text-center"
            >
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Bleu</p>
            </button>

            <!-- Orange Theme -->
            <button 
                @click="setTheme('orange')" 
                :class="currentTheme === 'orange' ? 'ring-2 ring-offset-2 ring-orange-500' : ''"
                class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-orange-500 transition-all text-center"
            >
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Orange</p>
            </button>
        </div>

        <!-- Display Options -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Options d'Affichage</h4>
            <div class="space-y-3">
                <!-- Dark Mode Toggle -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            @change="toggleDarkMode()" 
                            x-model="darkMode"
                            class="sr-only"
                        >
                        <div class="w-10 h-6 bg-gray-300 rounded-full transition-colors" :class="darkMode ? 'bg-green-600' : 'bg-gray-300'"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform" :style="darkMode ? 'transform: translateX(16px)' : ''"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mode Sombre</span>
                </label>

                <!-- Compact Sidebar Toggle -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            @change="toggleCompactMode()" 
                            x-model="compactMode"
                            class="sr-only"
                        >
                        <div class="w-10 h-6 bg-gray-300 rounded-full transition-colors" :class="compactMode ? 'bg-green-600' : 'bg-gray-300'"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform" :style="compactMode ? 'transform: translateX(16px)' : ''"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Barre latérale Compacte</span>
                </label>

                <!-- Animations Toggle -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            @change="toggleAnimations()" 
                            x-model="animationsEnabled"
                            class="sr-only"
                        >
                        <div class="w-10 h-6 bg-gray-300 rounded-full transition-colors" :class="animationsEnabled ? 'bg-green-600' : 'bg-gray-300'"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform" :style="animationsEnabled ? 'transform: translateX(16px)' : ''"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Animations</span>
                </label>
            </div>
        </div>

        <!-- Preview -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Aperçu</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg" :class="getThemeClass('bg')">
                    <p class="text-white font-semibold">Exemple de gradient</p>
                    <p class="text-white text-sm opacity-80">Texte bien lisible sur fond coloré</p>
                </div>
                <div class="p-4 rounded-lg border-2" :class="getThemeClass('border')">
                    <p class="font-semibold text-gray-900 dark:text-white">Exemple de bordure</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Avec accent de couleur et texte visible</p>
                </div>
            </div>
        </div>

        <!-- Save Settings -->
        <div class="flex gap-2 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button 
                @click="saveSettings()" 
                class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-lg font-medium transition-all"
            >
                💾 Enregistrer les Paramètres
            </button>
            <button 
                @click="resetSettings()" 
                class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors"
            >
                🔄 Réinitialiser
            </button>
        </div>
    </div>
</div>

<script>
function themeManager() {
    return {
        currentTheme: localStorage.getItem('appTheme') || 'green',
        darkMode: localStorage.getItem('darkMode') === 'true' || false,
        compactMode: localStorage.getItem('compactMode') === 'true' || false,
        animationsEnabled: localStorage.getItem('animationsEnabled') !== 'false',

        setTheme(theme) {
            this.currentTheme = theme;
            this.applyTheme();
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        toggleCompactMode() {
            this.compactMode = !this.compactMode;
        },

        toggleAnimations() {
            this.animationsEnabled = !this.animationsEnabled;
            if (!this.animationsEnabled) {
                document.documentElement.style.animation = 'none';
            }
        },

        getThemeClass(type) {
            const themeClasses = {
                green: { bg: 'bg-gradient-to-r from-green-500 to-green-600', text: 'text-white', border: 'border-green-500' },
                blue: { bg: 'bg-gradient-to-r from-blue-500 to-blue-600', text: 'text-white', border: 'border-blue-500' },
                orange: { bg: 'bg-gradient-to-r from-orange-500 to-orange-600', text: 'text-white', border: 'border-orange-500' }
            };
            return themeClasses[this.currentTheme][type];
        },

        applyTheme() {
            // Apply theme to document
            document.documentElement.setAttribute('data-theme', this.currentTheme);
        },

        saveSettings() {
            localStorage.setItem('appTheme', this.currentTheme);
            localStorage.setItem('darkMode', this.darkMode);
            localStorage.setItem('compactMode', this.compactMode);
            localStorage.setItem('animationsEnabled', this.animationsEnabled);
            
            // Show success message
            alert('✅ Paramètres enregistrés avec succès!');
        },

        resetSettings() {
            this.currentTheme = 'green';
            this.darkMode = false;
            this.compactMode = false;
            this.animationsEnabled = true;
            localStorage.clear();
            location.reload();
        },

        init() {
            this.applyTheme();
        }
    };
}
</script>
