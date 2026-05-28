<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Traiter un message du chatbot
     */
    public function handleMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $userId = Auth::id();

        // Sauvegarder le message utilisateur
        $this->saveMessage($userId, $message, 'user');

        // Générer une réponse du chatbot
        $response = $this->generateBotResponse($message);

        // Sauvegarder la réponse du bot
        $this->saveMessage($userId, $response, 'bot');

        return response()->json([
            'success' => true,
            'response' => $response,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtenir l'historique des conversations
     */
    public function getHistory(Request $request)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 50);

        // Ici vous récupéreriez l'historique depuis votre table de messages
        // Pour l'exemple, je retourne des données factices
        $history = [
            [
                'id' => 1,
                'message' => 'Bonjour, comment puis-je vous aider ?',
                'type' => 'bot',
                'created_at' => now()->subMinutes(30)->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'message' => 'J\'ai besoin d\'aide avec les commandes',
                'type' => 'user',
                'created_at' => now()->subMinutes(25)->format('Y-m-d H:i:s')
            ]
        ];

        return response()->json([
            'history' => $history,
            'total' => count($history)
        ]);
    }

    /**
     * Exécuter une action rapide
     */
    public function quickAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:stats,orders,products,users',
        ]);

        $action = $request->input('action');
        $response = '';

        switch ($action) {
            case 'stats':
                $response = 'Voici les statistiques du jour : 50 commandes, 25 nouveaux utilisateurs.';
                break;
            case 'orders':
                $response = 'Vous avez 12 nouvelles commandes en attente de traitement.';
                break;
            case 'products':
                $response = '5 produits sont en rupture de stock.';
                break;
            case 'users':
                $response = '150 utilisateurs actifs cette semaine.';
                break;
        }

        return response()->json([
            'success' => true,
            'response' => $response
        ]);
    }

    /**
     * Sauvegarder un message
     */
    private function saveMessage($userId, $message, $type)
    {
        // Ici vous sauvegarderiez dans votre table de messages
        // Exemple de structure :
        /*
        DB::table('chatbot_messages')->insert([
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        */
    }

    /**
     * Générer une réponse du chatbot
     */
    private function generateBotResponse($message)
    {
        $message = strtolower($message);

        // Réponses prédéfinies simples
        if (str_contains($message, 'bonjour') || str_contains($message, 'salut')) {
            return 'Bonjour ! Comment puis-je vous aider aujourd\'hui ?';
        }
        
        if (str_contains($message, 'commande')) {
            return 'Pour les commandes, vous pouvez consulter la section "Commandes" du dashboard.';
        }
        
        if (str_contains($message, 'aide')) {
            return 'Je peux vous aider avec les commandes, produits, utilisateurs et statistiques.';
        }

        return 'Je ne suis pas sûr de comprendre. Pouvez-vous reformuler votre question ?';
    }
}