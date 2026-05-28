<!DOCTYPE html>
<html>
<head>
    <title>Test Téléchargement Direct</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test du Système de Téléchargement</h1>

    @php
    // Créer un fichier de test dans storage/app/documents/
    $testContent = "Contenu du document de test\nCeci est un fichier PDF simulé.";
    $documentPath = 'documents/test-document.txt';

    if (!Storage::exists($documentPath)) {
        Storage::put($documentPath, $testContent);
        $fileCreated = true;
    } else {
        $fileCreated = false;
    }
    @endphp

    @if($fileCreated)
        <p>✅ Fichier de test créé: {{ $documentPath }}</p>
    @else
        <p>✅ Fichier de test existe: {{ $documentPath }}</p>
    @endif
    ?>

    <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
        <h3>🧪 Test Direct</h3>
        <p>Cliquez sur le lien ci-dessous pour tester le téléchargement direct :</p>
        <a href="/test-download-direct" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            📥 Télécharger Fichier Test
        </a>
    </div>

    <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
        <h3>📋 Instructions</h3>
        <ol>
            <li>Le fichier de test est créé automatiquement</li>
            <li>Cliquez sur "Télécharger Fichier Test" pour tester</li>
            <li>Le fichier doit se télécharger immédiatement</li>
            <li>Vérifiez que le téléchargement fonctionne dans votre navigateur</li>
        </ol>
    </div>

    <div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
        <h3>🔗 Liens de Test</h3>
        <ul>
            <li><a href="/articles">📰 Liste des Articles</a></li>
            <li><a href="/login">🔐 Page de Connexion</a></li>
            <li><a href="/register">👤 Inscription</a></li>
        </ul>
    </div>
</body>
</html>
