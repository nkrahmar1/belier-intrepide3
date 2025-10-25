@extends('home.base')

@section('title', 'Debug Scripts Bootstrap')

@section('content')
<div class="container mt-5">
    <h1>Debug Scripts Bootstrap</h1>
    
    <div class="row">
        <div class="col-12">
            <h2>Ordre de chargement des scripts</h2>
            <div id="script-order" class="alert alert-info">
                <ul id="script-list"></ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2>Tests de fonctionnalité</h2>
            
            <!-- Test Bootstrap Modal -->
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Test Modal Bootstrap
            </button>
            
            <!-- Test Bootstrap Dropdown -->
            <div class="dropdown d-inline-block me-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Test Dropdown
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
            
            <!-- Test Bootstrap Toast -->
            <button type="button" class="btn btn-warning" id="liveToastBtn">Afficher Toast</button>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2>Console JavaScript</h2>
            <pre id="console-output" class="bg-dark text-light p-3" style="max-height: 300px; overflow-y: auto;"></pre>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Test Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Si vous voyez cette modal, Bootstrap JS fonctionne !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Bootstrap</strong>
            <small>maintenant</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Toast Bootstrap fonctionne !
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let consoleOutput = document.getElementById('console-output');
let scriptList = document.getElementById('script-list');

function logToConsole(message) {
    consoleOutput.textContent += new Date().toLocaleTimeString() + ': ' + message + '\n';
    consoleOutput.scrollTop = consoleOutput.scrollHeight;
}

function addToScriptList(scriptName, status) {
    let li = document.createElement('li');
    li.innerHTML = `<span class="${status === 'loaded' ? 'text-success' : 'text-danger'}">${scriptName}: ${status}</span>`;
    scriptList.appendChild(li);
}

document.addEventListener('DOMContentLoaded', function() {
    logToConsole('DOM Content Loaded');
    
    // Vérifier Bootstrap
    if (typeof bootstrap !== 'undefined') {
        logToConsole('✅ Bootstrap is loaded');
        addToScriptList('Bootstrap JS', 'loaded');
        
        // Tester les composants Bootstrap
        try {
            // Test Dropdown
            let dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            let dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            logToConsole('✅ Dropdowns initialized');
            
            // Test Modal
            let modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            logToConsole('✅ Modal initialized');
            
            // Test Toast
            let toastElList = [].slice.call(document.querySelectorAll('.toast'));
            let toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl);
            });
            logToConsole('✅ Toast initialized');
            
        } catch (error) {
            logToConsole('❌ Error initializing Bootstrap components: ' + error.message);
        }
    } else {
        logToConsole('❌ Bootstrap is NOT loaded');
        addToScriptList('Bootstrap JS', 'NOT loaded');
    }
    
    // Vérifier jQuery
    if (typeof $ !== 'undefined') {
        logToConsole('✅ jQuery is loaded (version: ' + $.fn.jquery + ')');
        addToScriptList('jQuery', 'loaded (v' + $.fn.jquery + ')');
    } else {
        logToConsole('❌ jQuery is NOT loaded');
        addToScriptList('jQuery', 'NOT loaded');
    }
    
    // Test du bouton Toast
    document.getElementById('liveToastBtn').addEventListener('click', function () {
        if (typeof bootstrap !== 'undefined') {
            let toastLive = document.getElementById('liveToast');
            let toast = new bootstrap.Toast(toastLive);
            toast.show();
            logToConsole('Toast displayed');
        } else {
            logToConsole('Cannot display toast - Bootstrap not loaded');
        }
    });
});

// Capturer les erreurs
window.addEventListener('error', function(e) {
    logToConsole('❌ JavaScript Error: ' + e.message + ' (line ' + e.lineno + ')');
});
</script>
@endsection