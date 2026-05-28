<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Mon Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Tailwind CSS compilé avec Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles personnalisés -->
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="/">MonApp</a>
        <div>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link ajax-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link ajax-link" href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
            <li class="nav-item"><a class="nav-link ajax-link" href="{{ route('admin.orders.index') }}">Commandes</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="py-4 container">
        <div id="admin-content">
            @yield('content') {{-- contenu initial --}}
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".ajax-link").forEach(function(link) {
          link.addEventListener("click", function(e) {
            e.preventDefault();
            fetch(link.getAttribute("href"), {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(response => response.text())
            .then(html => {
              document.getElementById("admin-content").innerHTML = html;
            })
            .catch(err => console.error("Erreur AJAX :", err));
          });
        });
      });
    </script>
</body>
</html>
