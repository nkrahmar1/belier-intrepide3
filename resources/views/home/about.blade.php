@extends('home.base')

@section('title', 'about')

@section('content')


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos de nous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 dark:bg-gray-900">

<div class="site-container">
    <img src="{{ asset('image/pdci2.jpg') }}" alt="images_pdci">
</div>

<style>
    html, body {
    height: 100%;      /* Pour que le body prenne toute la hauteur du viewport */
    margin: 0;         /* Supprime marges par défaut */
}

.site-container {
    width: 100%;       /* largeur pleine */
    height: 100vh;     /* hauteur pleine de la fenêtre */
    overflow: hidden;  /* évite les scroll si image dépasse */
}

.site-container img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* adapte l'image sans couper, garde proportions */
    display: block;
}

</style>



    <header class="py-6 text-center">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            À propos de nous
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300">
            Le Bélier Intrépide est un quotidien proche du PDCI-RDA. Nous défendons les idéaux du parti et de la Côte d’Ivoire.
        </p>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-12">

            <!-- Introduction -->
            <section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-center">
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Notre histoire</h3>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Nous sommes le Parti Démocratique de Côte d’Ivoire - PDCI. Le PDCI, notre parti, a été créé en 1946 pour, comme l’indique son hymne,
                    « servir le vaillant peuple ivoirien ». Près de huit décennies plus tard, c’est cette ambition, écourtée par le coup d’état de 1999,
                    qui est au cœur de notre projet : unifier, dynamiser et moderniser un parti conquérant !
                </p>
            </section>

            <!-- Image avec logo -->
            <section class="relative">
                <img src="https://th.bing.com/th/id/OIP.PFm8jW5FgOJQZyhpQDP_EAHaFW?cb=iwp2&rs=1&pid=ImgDetMain" alt="PDCI-RDA" class="w-full h-auto rounded-lg shadow-md">
                <img src="https://th.bing.com/th/id/OIP.HvwFJrVlOtaYa3TsafG5ZAHaIC?cb=iwp2&rs=1&pid=ImgDetMain" alt="Logo PDCI" class="absolute right-4 top-4 w-1/6 h-auto">
            </section>

            <!-- Mission et valeurs -->
            <section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-4xl font-semibold text-gray-800 dark:text-white mb-4">Notre mission</h3>
                <p class="text-center text-gray-600 dark:text-gray-300 font-bold text-2xl mb-2">
                    Servir le vaillant Peuple ivoirien
                </p>
                <p class="text-gray-600 dark:text-gray-300">
                    Notre ambition forte est de servir le pays en le hissant au niveau de son potentiel. Il en est de notre devoir. C’est pourquoi notre parti se donne les moyens de couvrir toute l’étendue du territoire national pour être à l’écoute du peuple ivoirien, faire un état des lieux des principaux secteurs de la vie nationale.
                </p>
            </section>

            <!-- Citation de Félix Houphouët-Boigny -->
            <section class="flex items-center bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <img src="https://cdn.modernghana.com/content/600/360/felix_houphouet_boigny.jpg" alt="Félix Houphouët-Boigny" class="w-1/3 h-auto rounded-lg mr-4">
                <blockquote class="text-gray-600 dark:text-gray-300">
                    « En politique et comme les crocodiles, je dors en gardant un oeil ouvert, car j'ai plus peur de mes amis que de mes ennemis...<br>
                    <span class="font-bold">Félix Houphouët-Boigny</span>
                    »
                </blockquote>
            </section>

            <!-- Témoignages -->
            <section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Ce que disent nos clients</h3>
                <blockquote class="text-gray-600 dark:text-gray-300 italic">
                    "Une équipe professionnelle et à l'écoute. Leurs solutions ont transformé notre manière de travailler."
                </blockquote>
                <p class="mt-2 text-gray-800 dark:text-white font-semibold">— Marie L., Directrice Marketing</p>
            </section>

            <!-- Appel à l'action -->
            <section class="text-center">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Envie d'en savoir plus ?</h3>

            </section>

        </div>
    </div>

</body>
</html>
@endsection
