<!DOCTYPE html>
<html>
<head>
    <title>Créer Article Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Créer un Article de Test avec Document</h1>
    
    <form action="/create-test-article" method="POST" style="max-width: 500px; margin: 20px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Titre de l'article:</label><br>
            <input type="text" name="titre" value="Article Test avec Document Téléchargeable" style="width: 100%; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Contenu:</label><br>
            <textarea name="contenu" rows="5" style="width: 100%; padding: 8px;">
Ceci est un article de test avec un document téléchargeable.

Ce document peut être téléchargé selon les règles suivantes:
- L'utilisateur doit être connecté
- Si l'article est premium, l'utilisateur doit avoir un abonnement actif

Testez le système de téléchargement en cliquant sur le bouton "Télécharger" ci-dessous.
            </textarea>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>
                <input type="checkbox" name="is_premium" value="1"> Article Premium (nécessite un abonnement)
            </label>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>
                <input type="checkbox" name="is_published" value="1" checked> Article publié
            </label>
        </div>
        
        <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
            Créer Article Test
        </button>
    </form>
    
    <div style="border: 1px solid #ccc; padding: 15px; margin: 20px 0;">
        <h3>📋 Après création</h3>
        <p>Une fois l'article créé, vous pourrez:</p>
        <ul>
            <li>Voir l'article dans la <a href="/articles">liste des articles</a></li>
            <li>Tester le téléchargement selon votre statut de connexion</li>
            <li>Vérifier les redirections d'abonnement</li>
        </ul>
    </div>
</body>
</html>
