@extends('home.base')

@section('title', 'Test Simple Bootstrap')

@section('content')
<div class="container mt-5">
    <h1>Test Simple des Dropdowns</h1>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Dropdown Account</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Mon Compte
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Connexion</a></li>
                    <li><a class="dropdown-item" href="#">Inscription</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-6">
            <h3>Dropdown Panier</h3>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Panier (3)
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Article 1</a></li>
                    <li><a class="dropdown-item" href="#">Article 2</a></li>
                    <li><a class="dropdown-item" href="#">Article 3</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <strong>Instructions :</strong>
        <ol>
            <li>Testez les dropdowns ci-dessus</li>
            <li>Ouvrez l'inspecteur (F12) et testez à nouveau</li>
            <li>Fermez l'inspecteur et vérifiez que ça marche toujours</li>
        </ol>
    </div>
</div>
@endsection