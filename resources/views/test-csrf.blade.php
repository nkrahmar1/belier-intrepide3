@extends('home.base')

@section('title', 'Test CSRF')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>🔐 Test du Token CSRF</h2>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations de Session</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Session ID:</strong></td>
                            <td><code>{{ session()->getId() }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>CSRF Token:</strong></td>
                            <td><code>{{ csrf_token() }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Session Driver:</strong></td>
                            <td><code>{{ config('session.driver') }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Session Lifetime:</strong></td>
                            <td><code>{{ config('session.lifetime') }} minutes</code></td>
                        </tr>
                        <tr>
                            <td><strong>Auth Check:</strong></td>
                            <td>
                                @auth
                                    <span class="badge bg-success">✅ Connecté: {{ Auth::user()->email }}</span>
                                @else
                                    <span class="badge bg-warning">⚠️ Non connecté</span>
                                @endauth
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Test de Formulaire avec CSRF</h5>
                </div>
                <div class="card-body">
                    <form id="testForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" name="test_csrf" value="1">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="test@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" value="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Tester Login avec CSRF</button>
                        <button type="button" class="btn btn-warning" onclick="testCsrfAjax()">Test AJAX CSRF</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Test Direct du Token</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-info" onclick="checkToken()">Vérifier Token CSRF</button>
                    <button class="btn btn-secondary" onclick="regenerateToken()">Régénérer Token</button>

                    <div id="tokenResults" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Debug Console</h5>
                </div>
                <div class="card-body">
                    <pre id="debugOutput" style="background: #f8f9fa; padding: 10px; font-size: 12px; height: 400px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const debugOutput = document.getElementById('debugOutput');

    function addDebug(message) {
        debugOutput.textContent += new Date().toLocaleTimeString() + ': ' + message + '\n';
        debugOutput.scrollTop = debugOutput.scrollHeight;
    }

    function checkToken() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formToken = document.querySelector('input[name="_token"]').value;

        addDebug('Meta CSRF Token: ' + token);
        addDebug('Form CSRF Token: ' + formToken);
        addDebug('Tokens Match: ' + (token === formToken));

        document.getElementById('tokenResults').innerHTML = `
            <div class="alert ${token === formToken ? 'alert-success' : 'alert-danger'}">
                <strong>Meta Token:</strong> ${token}<br>
                <strong>Form Token:</strong> ${formToken}<br>
                <strong>Match:</strong> ${token === formToken ? '✅ Oui' : '❌ Non'}
            </div>
        `;
    }

    function regenerateToken() {
        fetch('/test-csrf-regenerate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            addDebug('Nouveau token reçu: ' + data.token);
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
            document.querySelector('input[name="_token"]').value = data.token;
        })
        .catch(error => {
            addDebug('Erreur régénération: ' + error);
        });
    }

    function testCsrfAjax() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route('login') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: 'test@example.com',
                password: 'password'
            })
        })
        .then(response => {
            addDebug('Response Status: ' + response.status);
            if (response.status === 419) {
                addDebug('❌ Erreur 419 - Token CSRF invalide');
            } else if (response.status === 422) {
                addDebug('⚠️ Erreur 422 - Validation failed (normal)');
            } else {
                addDebug('✅ Autre statut: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            addDebug('Response: ' + JSON.stringify(data));
        })
        .catch(error => {
            addDebug('Erreur AJAX: ' + error);
        });
    }

    // Intercepter la soumission du formulaire
    document.getElementById('testForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addDebug('🚀 Soumission du formulaire interceptée');

        const formData = new FormData(this);
        addDebug('Token du formulaire: ' + formData.get('_token'));

        // Faire la requête
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            addDebug('Status: ' + response.status);
            if (response.status === 419) {
                addDebug('❌ ERREUR 419 - CSRF Token manquant/invalide');
            }
            return response.text();
        })
        .then(html => {
            addDebug('Réponse reçue (longueur: ' + html.length + ' chars)');
        })
        .catch(error => {
            addDebug('❌ Erreur: ' + error);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        addDebug('🚀 Page de test CSRF chargée');
        checkToken();
    });
</script>
@endsection
