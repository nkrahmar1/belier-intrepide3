<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\ChatbotMessage;

// Chargement de l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Créer des messages de test pour le chatbot
    $user = User::first();
    
    if ($user) {
        // Messages de l'utilisateur
        ChatbotMessage::create([
            'user_id' => $user->id,
            'message' => 'Bonjour, j\'ai une question concernant votre journal.',
            'type' => 'user',
            'created_at' => now()->subMinutes(30)
        ]);
        
        ChatbotMessage::create([
            'user_id' => $user->id,
            'message' => 'Comment puis-je m\'abonner à votre version premium ?',
            'type' => 'user',
            'created_at' => now()->subMinutes(25)
        ]);
        
        ChatbotMessage::create([
            'user_id' => $user->id,
            'message' => 'Est-ce que vous avez des articles sur la technologie ?',
            'type' => 'user',
            'created_at' => now()->subMinutes(20)
        ]);
        
        // Réponse de l'admin
        ChatbotMessage::create([
            'user_id' => $user->id,
            'message' => 'Bonjour ! Merci pour votre intérêt. Notre version premium offre un accès illimité à tous nos articles.',
            'type' => 'admin',
            'metadata' => ['admin_id' => 1],
            'created_at' => now()->subMinutes(15)
        ]);
        
        echo "✅ Messages de test créés avec succès pour le chatbot!\n";
        echo "- Utilisateur: {$user->name} (ID: {$user->id})\n";
        echo "- Messages créés: " . ChatbotMessage::where('user_id', $user->id)->count() . "\n";
        
    } else {
        echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
