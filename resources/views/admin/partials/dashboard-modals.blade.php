{{-- Modals pour le Dashboard Administrateur --}}

{{-- Modal principal (utilise le système test-modal.html) --}}
<div id="admin-modal" 
     class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 hidden items-end sm:items-center justify-center z-50 p-0 sm:p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="admin-modal-title"
     x-data="modalData()"
     x-cloak>
    
    <div class="modal-content bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-7xl max-h-[85vh] sm:max-h-[90vh] overflow-hidden shadow-2xl transform transition-all duration-200">
        
        {{-- Header du modal --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-3 sm:p-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                <span id="admin-modal-icon" class="text-xl sm:text-2xl flex-shrink-0" x-text="modalIcon"></span>
                <h3 id="admin-modal-title" class="text-base sm:text-lg font-bold truncate" x-text="modalTitle"></h3>
            </div>
            <button @click="closeModal()" 
                    class="text-white hover:text-gray-200 text-2xl sm:text-3xl font-bold transition-colors ml-2 flex-shrink-0 w-8 h-8 flex items-center justify-center"
                    aria-label="Fermer le modal">
                ×
            </button>
        </div>

        {{-- Contenu du modal --}}
        <div id="admin-modal-content" class="p-4 sm:p-6 overflow-y-auto custom-scrollbar" style="max-height: calc(85vh - 56px);">
            
            {{-- Section Articles --}}
            <div x-show="section === 'articles'" x-cloak>
                @include('admin.partials.sections.articles-section')
            </div>

            {{-- Section Messages --}}
            <div x-show="section === 'messages'" x-cloak>
                @include('admin.partials.sections.messages-section')
            </div>

            {{-- Section Abonnements --}}
            <div x-show="section === 'subscriptions'" x-cloak>
                @include('admin.partials.sections.subscriptions-section')
            </div>

            {{-- Section Paramètres --}}
            <div x-show="section === 'settings'" x-cloak>
                @include('admin.partials.sections.settings-section')
            </div>

        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Scrollbar personnalisée */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Animation modal */
    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-content {
        animation: slideUp 0.3s ease-out;
    }
</style>

@push('scripts')
<script>
function modalData() {
    return {
        section: null,
        modalTitle: 'Chargement...',
        modalIcon: '📊',
        
        sections: {
            articles: { title: '📰 Gestion des Articles', icon: '📰' },
            messages: { title: '✉️ Gestion des Messages', icon: '✉️' },
            subscriptions: { title: '💳 Gestion des Abonnements', icon: '💳' },
            settings: { title: '⚙️ Paramètres', icon: '⚙️' }
        },
        
        openModal(sectionName) {
            this.section = sectionName;
            const config = this.sections[sectionName];
            
            if (config) {
                this.modalTitle = config.title;
                this.modalIcon = config.icon;
            }
            
            const modal = document.getElementById('admin-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        },
        
        closeModal() {
            const modal = document.getElementById('admin-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
            this.section = null;
        }
    };
}

// Fonction globale pour ouvrir le modal (appelée depuis le dashboard)
function openAdminModal(section, event) {
    if (event) {
        event.preventDefault();
    }
    
    // Déclencher l'ouverture du modal Alpine
    const modalEl = document.querySelector('[x-data*="modalData"]');
    if (modalEl && modalEl.__x) {
        modalEl.__x.$data.openModal(section);
    }
}

// Fermer le modal avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modalEl = document.querySelector('[x-data*="modalData"]');
        if (modalEl && modalEl.__x) {
            modalEl.__x.$data.closeModal();
        }
    }
});
</script>
@endpush
