@extends('home.base')

@section('title', $categorie , 'Articles de la catégorie ')

@section('content')

<div class="container my-5">
    <h1 class="mb-4 border-bottom pb-2 text-primary fw-bold">{{ $categorie }}</h1>

    {{-- Image d'accueil dynamique selon la catégorie --}}
@switch($categorie)
    @case('Sport')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/ivoir.webp') }}" alt="Sport Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
           <!-- <p>
                bonjour le monde entier comment allez vous
            </p>-->
        </div>
        @break
    @case('Culture and media')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/culture.webp') }}" alt="Culture Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
    @case('Economy')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/economie.jpg') }}" alt="Economy Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
    @case('Political')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/politique1.jpg') }}" alt="Political Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
    @case('PDCI-RDA')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/pdci1.jpg') }}" alt="PDCI-RDA Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br>
<style>
* {
            /* Police et fond global */
}
body {
    margin: 0;
    padding: 0;
    font-family: 'Georgia', serif;
    background-color: #fff;
    color: #111;
}

/* Section du bureau politique */
.politique-section {
    padding: 60px 30px;
    background-color: #fff;
}

.politique-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    max-width: 1200px;
    margin: auto;
    gap: 40px;
}

/* Texte */
.texte {
    flex: 1 1 400px;
    max-width: 600px;
}

.texte h2 {
    font-size: 3em;
    margin-bottom: 20px;
    line-height: 1.2;
}

.texte p {
    font-size: 1.1em;
    color: #444;
    line-height: 1.6;
}

/* Vidéo */
.video {
    flex: 1 1 400px;
    max-width: 600px;
}

.video video {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
 body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background-color: #f9f9f9;
            color: #111;
        }

        .politique-section {
            padding: 60px 30px;
            background-color: #ffffff;
        }

        .politique-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            max-width: 1200px;
            margin: auto;
            gap: 40px;
        }

        .texte {
            flex: 1 1 400px;
            max-width: 600px;
        }

        .texte h2 {
            font-size: 3em;
            margin-bottom: 20px;
            line-height: 1.2;
            text-transform: uppercase;
            color: #2b2b2b;
            letter-spacing: 1px;
        }

        .texte p {
            font-size: 1.1em;
            color: #444;
            line-height: 1.6;
            text-align: justify;
        }

        .video {
            flex: 1 1 400px;
            max-width: 600px;
        }

        .video video {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .video video:hover {
            transform: scale(1.01);
        }

        @media (max-width: 768px) {
            .texte h2 {
                font-size: 2.2em;
            }

            .texte p {
                font-size: 1em;
            }
        }

     </style>
     <br><br><br>

            <div class="container">
        <header class="header">
            <h1>LA VIE DU PARTI</h1>
            <p>Suivre l'actualité du PDCI RDA en temps réel</p>
        </header>

        <main>
        <section class="politique-section">
        <div class="politique-content">
            <div class="texte">
                <h2>BUREAU POLITIQUE DU<br>PDCI-RDA</h2>
                <p>
                    PDCI-RDA : LE 18ÈME BUREAU POLITIQUE DU PDCI-RDA MAINTENU AU SAMEDI 05 AVRIL 2025 À LA FONDATION F.H.B DE YAMOUSSOUKRO
                </p>
            </div>

            <div class="video">
                <video controls>
                    <source src="{{asset('video/18Eme bureau.mp4')}}" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture vidéo.
                </video>
            </div>
        </div>
    </section>

    </div>

    <br><br><br><br><br><br><br>

        <h1>Tribunal d'Abidjan : Des députés PDCI-RDA refoulés à l'entrée </h1>
        <p>Le président du groupe parlementaire PDCI-RDA, Simon Doho, accompagné de plusieurs députés, dont Aby Raoul et Jacques Ehouo, a été empêché d’accéder au Tribunal de Première Instance du Plateau ce mercredi 2 avril 2025. Ils souhaitaient assister à l’audience en référé, mais ont été refoulés par la police nationale.
        Un dispositif sécuritaire renforcé a été mis en place pour assurer le bon déroulement de cette audience. Pendant ce temps, une forte agitation règne à la permanence du PDCI-RDA à Abidjan-Plateau, où de nombreux militants, visiblement en colère , se sont rassemblés. </p>
        <div class="video">
    <video controls>
        <source src="{{ asset('video/18Eme bureau.mp4') }}" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
    </video>
</div>

        <!-- saut de pages --->
            <br><br><br><br><br><br><br>
        </div>
        @break
    @case('Africa')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/culture3.jpg') }}" alt="Africa Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
    @case('Society')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/culture2.jpg') }}" alt="Society Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
    @case('Files')
        <div class="mb-4 text-center">
            <img src="{{ asset('image/justice1.webp') }}" alt="Files Banner" class="w-100 banner-image" style="max-height: 300px; object-fit: cover;"><br><br><br><br><br><br><br>
        </div>
        @break
@endswitch



    @if($articles->isEmpty())
        <div class="alert alert-warning">Aucun article trouvé dans cette catégorie.</div>
    @else
        @foreach($articles as $article)
            <div class="card mb-4 shadow-sm border-0">
                <div class="row g-0 align-items-stretch" style="min-height: 250px;">

                    {{-- Image à gauche --}}
                    @if($article->image)
                        <div class="col-md-4 d-flex">
                            <img src="{{ asset('image/' . $article->image) }}"
                                 alt="{{ $article->titre }}"
                                 class="img-fluid w-100 rounded-start"
                                 style="object-fit: cover; height: 100%;">
                        </div>
                    @endif

                    {{-- Contenu à droite --}}
                    <div class="col-md-8 d-flex">
                        <div class="card-body d-flex flex-column justify-content-between">

                            <div>
                                <h5 class="card-title fw-semibold text-dark">{{ $article->titre }}</h5>
                                <p class="card-subtitle text-muted mb-2">Catégorie : {{ $article->category->name }}</p>

                                {{-- Texte avec bouton Lire plus --}}
                                <p class="card-text text-secondary" style="line-height: 1.6;">
                                    <span class="short-text">{{ \Illuminate\Support\Str::limit($article->contenu, 100, '...') }}</span>
                                    <span class="full-text d-none">{{ $article->contenu }}</span>
                                </p>

                                <button class="btn btn-link p-0" onclick="toggleReadMore(this)">Lire plus</button>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                {{-- Date --}}
                               <small class="text-muted">
                                    @if($article->created_at)
                                        Publié le {{ $article->created_at->format('d/m/Y') }}
                                    @else
                                        Publié à une date inconnue
                                    @endif
                                </small>

                                {{-- Bouton Télécharger avec logique d'abonnement --}}
                                @if($article->document_path)
                                    @auth
                                        @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
                                            <a href="{{ route('articles.download', $article) }}" class="btn btn-success">
                                                <i class="fas fa-download"></i> Télécharger
                                                @if(auth()->user()->isAdmin())
                                                    <small class="text-muted">(Admin)</small>
                                                @endif
                                            </a>
                                        @else
                                            <a href="{{ route('home.abonnement') }}" class="btn btn-warning">
                                                <i class="fas fa-crown"></i> Abonnement requis
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-sign-in-alt"></i> Se connecter
                                        </a>
                                    @endauth
                                @else
                                    <span class="text-muted">Aucun document</span>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Script JS pour toggle Lire plus / Lire moins --}}
<script>
function toggleReadMore(button) {
    const container = button.previousElementSibling;
    const shortText = container.querySelector('.short-text');
    const fullText = container.querySelector('.full-text');

    if (fullText.classList.contains('d-none')) {
        fullText.classList.remove('d-none');
        shortText.classList.add('d-none');
        button.textContent = 'Lire moins';
    } else {
        fullText.classList.add('d-none');
        shortText.classList.remove('d-none');
        button.textContent = 'Lire plus';
    }
}
</script>

@endsection

