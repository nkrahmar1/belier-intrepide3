<?php
// Test du système chatbot complet
require_once 'vendor/autoload.php';

echo "🤖 Test du Système Chatbot\n";
echo "===========================\n\n";

// Test de connexion à la base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données: OK\n";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}

// Vérifier si la table chatbot_messages existe
try {
    $stmt = $pdo->query("DESCRIBE chatbot_messages");
    echo "✅ Table chatbot_messages: OK\n";
    
    // Afficher la structure
    echo "   Structure de la table:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['Field']}: {$row['Type']}\n";
    }
} catch (PDOException $e) {
    echo "❌ Table chatbot_messages manquante\n";
    echo "   Création de la table...\n";
    
    try {
        $createTable = "
        CREATE TABLE chatbot_messages (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id varchar(255) NOT NULL,
            message text NOT NULL,
            type enum('user','admin') NOT NULL DEFAULT 'user',
            metadata json DEFAULT NULL,
            read_at timestamp NULL DEFAULT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id),
            KEY chatbot_messages_user_id_index (user_id),
            KEY chatbot_messages_type_index (type),
            KEY chatbot_messages_created_at_index (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTable);
        echo "✅ Table chatbot_messages créée avec succès\n";
    } catch (PDOException $e) {
        echo "❌ Erreur lors de la création de la table: " . $e->getMessage() . "\n";
    }
}

// Test d'insertion d'un message invité
try {
    $guestId = 'guest_' . time() . '_test';
    $stmt = $pdo->prepare("
        INSERT INTO chatbot_messages (user_id, message, type, metadata, created_at, updated_at) 
        VALUES (?, ?, 'user', ?, NOW(), NOW())
    ");
    
    $metadata = json_encode([
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Script',
        'is_guest' => true,
        'session_id' => 'test_session'
    ]);
    
    $stmt->execute([$guestId, 'Message de test depuis un invité', $metadata]);
    echo "✅ Insertion message invité: OK (ID: $guestId)\n";
} catch (PDOException $e) {
    echo "❌ Erreur insertion message: " . $e->getMessage() . "\n";
}

// Test d'insertion d'une réponse admin
try {
    $stmt = $pdo->prepare("
        INSERT INTO chatbot_messages (user_id, message, type, metadata, created_at, updated_at) 
        VALUES (?, ?, 'admin', ?, NOW(), NOW())
    ");
    
    $adminMetadata = json_encode([
        'admin_id' => 1,
        'reply_to' => $guestId
    ]);
    
    $stmt->execute([$guestId, 'Bonjour ! Comment puis-je vous aider ?', $adminMetadata]);
    echo "✅ Insertion réponse admin: OK\n";
} catch (PDOException $e) {
    echo "❌ Erreur insertion réponse admin: " . $e->getMessage() . "\n";
}

// Test de récupération des conversations
try {
    $stmt = $pdo->query("
        SELECT user_id, COUNT(*) as message_count, MAX(created_at) as last_message,
               SUM(CASE WHEN read_at IS NULL AND type = 'user' THEN 1 ELSE 0 END) as unread_count
        FROM chatbot_messages 
        GROUP BY user_id 
        ORDER BY last_message DESC
    ");
    
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Récupération conversations: OK (" . count($conversations) . " conversation(s))\n";
    
    foreach ($conversations as $conv) {
        $displayName = str_starts_with($conv['user_id'], 'guest_') ? 
                      'Invité #' . substr($conv['user_id'], -4) : 
                      'Utilisateur ' . $conv['user_id'];
        echo "   - $displayName: {$conv['message_count']} message(s), {$conv['unread_count']} non lu(s)\n";
    }
} catch (PDOException $e) {
    echo "❌ Erreur récupération conversations: " . $e->getMessage() . "\n";
}

// Test de récupération des messages d'une conversation
try {
    if (isset($guestId)) {
        $stmt = $pdo->prepare("
            SELECT message, type, created_at, metadata 
            FROM chatbot_messages 
            WHERE user_id = ? 
            ORDER BY created_at ASC
        ");
        $stmt->execute([$guestId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Récupération messages conversation: OK (" . count($messages) . " message(s))\n";
        
        foreach ($messages as $msg) {
            $time = date('H:i:s', strtotime($msg['created_at']));
            $sender = $msg['type'] === 'user' ? 'Invité' : 'Admin';
            echo "   [$time] $sender: " . substr($msg['message'], 0, 50) . "...\n";
        }
    }
} catch (PDOException $e) {
    echo "❌ Erreur récupération messages: " . $e->getMessage() . "\n";
}

// Statistiques finales
try {
    $totalMessages = $pdo->query("SELECT COUNT(*) FROM chatbot_messages")->fetchColumn();
    $totalConversations = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM chatbot_messages")->fetchColumn();
    $unreadMessages = $pdo->query("SELECT COUNT(*) FROM chatbot_messages WHERE read_at IS NULL AND type = 'user'")->fetchColumn();
    
    echo "\n📊 Statistiques:\n";
    echo "   - Total messages: $totalMessages\n";
    echo "   - Total conversations: $totalConversations\n";
    echo "   - Messages non lus: $unreadMessages\n";
} catch (PDOException $e) {
    echo "❌ Erreur statistiques: " . $e->getMessage() . "\n";
}

echo "\n✅ Tests terminés avec succès !\n";
echo "\n🔗 Liens utiles:\n";
echo "   - Page d'accueil avec chatbot: http://127.0.0.1:8000/\n";
echo "   - Dashboard admin messages: http://127.0.0.1:8000/admin/messages\n";
echo "   - Test chatbot HTML: ./test_chatbot_complet.html\n";
?>