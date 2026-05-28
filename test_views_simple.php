<?php

echo "=== Vérification de la structure des vues admin ===\n";

// Vérifier la vue stats
$statsContent = file_get_contents('resources/views/admin/stats.blade.php');
if (strpos($statsContent, '@extends(\'layouts.admin\')') !== false) {
    echo "✅ stats.blade.php étend bien layouts.admin\n";
} else {
    echo "❌ stats.blade.php n'étend pas layouts.admin\n";
}

if (strpos($statsContent, '@section(\'content\')') !== false) {
    echo "✅ stats.blade.php a une section content\n";
} else {
    echo "❌ stats.blade.php n'a pas de section content\n";
}

// Vérifier s'il y a d'autres @section non fermées
$sectionCount = substr_count($statsContent, '@section(');
$endSectionCount = substr_count($statsContent, '@endsection');
echo "Stats - Sections: $sectionCount, EndSections: $endSectionCount\n";

// Vérifier la vue messages
$messagesContent = file_get_contents('resources/views/admin/messages.blade.php');
if (strpos($messagesContent, '@extends(\'layouts.admin\')') !== false) {
    echo "✅ messages.blade.php étend bien layouts.admin\n";
} else {
    echo "❌ messages.blade.php n'étend pas layouts.admin\n";
}

if (strpos($messagesContent, '@section(\'content\')') !== false) {
    echo "✅ messages.blade.php a une section content\n";
} else {
    echo "❌ messages.blade.php n'a pas de section content\n";
}

// Vérifier s'il y a d'autres @section non fermées
$sectionCount = substr_count($messagesContent, '@section(');
$endSectionCount = substr_count($messagesContent, '@endsection');
echo "Messages - Sections: $sectionCount, EndSections: $endSectionCount\n";

// Vérifier le layout admin
$layoutContent = file_get_contents('resources/views/layouts/admin.blade.php');
if (strpos($layoutContent, '@yield(\'content\')') !== false) {
    echo "✅ Layout admin a bien @yield('content')\n";
} else {
    echo "❌ Layout admin n'a pas @yield('content')\n";
}

// Vérifier s'il y a des balises HTML ouvertes non fermées dans le layout
if (strpos($layoutContent, '<main') !== false && strpos($layoutContent, '</main>') !== false) {
    echo "✅ Layout admin a des balises main fermées\n";
} else {
    echo "⚠️ Layout admin pourrait avoir des balises main non fermées\n";
}

echo "\n=== Recherche de balises non fermées ===\n";

// Compter les balises div dans le layout
$divOpen = substr_count($layoutContent, '<div');
$divClose = substr_count($layoutContent, '</div>');
echo "Layout - div ouvertes: $divOpen, div fermées: $divClose\n";

echo "\n=== Fin des tests ===\n";
