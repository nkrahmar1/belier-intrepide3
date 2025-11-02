@extends('home.base')

@section('title', 'Home')

@section('content')

<!-- Font Awesome pour les icônes -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    /* === VARIABLES GLOBALES === */
    :root {
    --color-primary: #28a745;
    --color-secondary: #ff6b35;
    --color-dark: #2c3e50;
    --color-light: #f8f9fa;
    --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* === RESET SPÉCIFIQUE AU CONTENU PRINCIPAL === */
    .home-content * {
    box-sizing: border-box;
    }

    .home-content {
    font-family: var(--font-main);
    background-color: var(--color-light);
    color: #333;
    line-height: 1.6;
    padding: 2rem 0;
    min-height: 100vh;
    }

    /* === NAVBAR TOUJOURS VISIBLE === */
    .navbar {
    position: relative !important;
    z-index: 9999 !important;
    display: block !important;
    visibility: visible !important;
    }

    /* S'assurer que le collapse est visible sur DESKTOP */
    @media (min-width: 992px) {
        .navbar-collapse {
            display: flex !important;
            visibility: visible !important;
        }
    }

    /* Sur MOBILE, gérer le collapse normalement */
    @media (max-width: 991px) {
        .navbar-collapse.collapse:not(.show) {
            display: none !important;
        }

        .navbar-collapse.collapse.show {
            display: flex !important;
            flex-direction: column;
        }

        .navbar-collapse {
            background-color: #04672a;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
    }

    /* === FLOATING ELEMENTS === */
    .floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
    }

    /* === HEADER === */
    header {
    position: relative;
    background: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)),
                url("https://th.bing.com/th/id/OIP.EoUzkqtSoMGZFkP9_4DyigHaEz?cb=iwp2&rs=1&pid=ImgDetMain");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    text-align: center;
    padding: 60px 20px;
    overflow: hidden;
    transition: filter 0.3s ease;
    color: var(--color-dark);
    }

    header.scrolled {
    filter: blur(6px);
    }

    header > * {
    position: relative;
    z-index: 2;
    }

    h1 {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 30px;
    color: var(--color-dark);
    }

    .logo-container {
    margin: 30px 0;
    display: inline-block;
    width: 140px;
    height: 70px;
    border-radius: 10%;
    overflow: hidden;
    border: 2px solid rgba(0, 0, 0, 0.1);
    background: transparent;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    }

    .logo-container:hover {
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .logo-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 10%;
    background: transparent;
    display: block;
    transition: transform 0.3s ease;
    }

    .sous-titres {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    color: var(--color-dark);
    }

    .phrase-principale {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 15px;
    text-align: center;
    }

    .abonnement {
    font-size: 1rem;
    margin: 15px 0;
    opacity: 0.95;
    text-align: center;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .phrase-principale {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .abonnement {
            font-size: 1.2rem;
            margin: 20px 0;
        }
    }

    .encadre-jaune {
    background: linear-gradient(45deg, #f1c40f, #f39c12);
    color: var(--color-dark);
    display: inline-block;
    padding: 12px 24px;
    border-radius: 25px;
    margin-top: 20px;
    font-weight: bold;
    font-size: 1.1rem;
    box-shadow: 0 8px 25px rgba(241,196,15,0.4);
    transition: all 0.3s ease;
    text-align: center;
    width: auto;
    }

    /* Desktop */
    @media (min-width: 768px) {
        .encadre-jaune {
            padding: 15px 30px;
            margin-top: 30px;
            font-size: 1.3rem;
        }
    }

    .encadre-jaune:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 35px rgba(241,196,15,0.6);
    }

   /* === CONTENU PRINCIPAL RESPONSIVE === */
    /* Container responsive de base */
.container {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 0 15px;
    box-sizing: border-box;
    }

    /* Mobile First: Styles de base pour mobile */
    @media (min-width: 576px) {
        .container {
            padding: 0 20px;
        }
    }

    @media (min-width: 768px) {
        .container {
            padding: 0 30px;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1200px;
            padding: 0 20px;
        }
    }

    .content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
    }

    /* Tablette et plus */
    @media (min-width: 768px) {
        .content-grid {
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
    }

    /* Styles de base pour les sections responsive */
    .main-content,
    .sidebar-section,
    .popular-section {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .main-content,
        .sidebar-section,
        .popular-section {
            padding: 2rem;
        }
    }
    }

    /* === ANIMATIONS POUR LES LIENS DE CATEGORIES === */

    /* Style de base pour la liste */
    .categories-list {
    list-style: none;
    padding: 0;
    margin: 0;
    }

    .categories-list li {
    margin-bottom: 0.5rem;
    }

  /* === OPTION 1: Animation de glissement avec couleur === */
    .categories-list a {
    display: block;
    padding: 12px 16px;
    text-decoration: none;
    color: #333;
    border-radius: 8px;
    position: relative;
    transition: all 0.3s ease;
    overflow: hidden;
    }

    .categories-list a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #05a105f1, #3cd505dd);
    transition: left 0.3s ease;
    z-index: -1;
    }

    .categories-list a:hover {
    color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    .categories-list a:hover::before {
    left: 0;
    }

    /* === ARTICLES === */
    .featured-article {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
    }

    .featured-article img,
    .article-card img {
    width: 100%;
    object-fit: cover;
    border-radius: 8px;
    }

    .featured-article img {
    height: 300px;
    }

    .article-card img {
    height: 200px;
    }

    .article-category,
    .category-tag {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 10px;
    color: white;
    }

    .article-category.sport,
    .category-tag.sport {
    background: #007bff;
    }

    .article-category.economie,
    .category-tag.economie {
    background: #28a745;
    }

    .article-category.culture,
    .category-tag.culture {
        background: #ffc107;
    color: #333;
    }

    .article-category.politique,
    .category-tag.politique {
    background: #dc3545;
    }

    .article-category.pdci,
    .category-tag.pdci {
    background: #ff6b35;
    }

    .article-title,
    .article-card-title,
    .popular-title {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: var(--color-dark);
    transition: color 0.3s ease;
    line-height: 1.4;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .article-title,
        .article-card-title,
        .popular-title {
            font-size: 1.5rem;
        }
    }

    .article-excerpt,
    .article-card-excerpt {
    color: #666;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    }

    .article-meta,
    .popular-meta {
    font-size: 0.8rem;
    color: #888;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .article-meta,
        .popular-meta {
            font-size: 0.85rem;
            gap: 1rem;
        }
    }

    /* === SIDEBAR RESPONSIVE === */
    .sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .sidebar {
            gap: 2rem;
        }
    }

    .sidebar-title {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--color-dark);
    border-bottom: 2px solid var(--color-secondary);
    padding-bottom: 0.5rem;
    }

    .sidebar-article {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.3s;
    }

    .sidebar-article:hover {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 1rem;
    margin: 0 -1rem 1rem -1rem;
    }

    .sidebar-article img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    }

    .sidebar-article-title {
    font-size: 0.9rem;
    margin-bottom: 0.3rem;
    color: var(--color-dark);
    }

    .sidebar-article-date {
    font-size: 0.8rem;
    }

    /* === ARTICLES EN VEDETTE HOMEPAGE === */
    .homepage-featured-articles {
    animation: fadeInUp 0.8s ease-out;
    }

    .featured-article-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
    }

    .featured-article-card:hover .article-image img {
    transform: scale(1.05);
    }

    .featured-article-card a:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .category-section {
    animation: fadeIn 0.6s ease-out;
    animation-fill-mode: both;
    }

    .category-section:nth-child(1) { animation-delay: 0.1s; }
    .category-section:nth-child(2) { animation-delay: 0.2s; }
    .category-section:nth-child(3) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    @keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
    color: #888;
    }

    /* === LISTE D’ARTICLES (GRID) === */
    .articles-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-top: 1.5rem;
    }

    /* Mobile large */
    @media (min-width: 576px) {
        .articles-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
    }

    /* Tablette */
    @media (min-width: 768px) {
        .articles-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
    }

    .article-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    }

    .article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* === POPULAIRES RESPONSIVE === */
    .popular-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .popular-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
        }
    }

    .popular-item:hover {
    background-color: #f8f9fa;
    padding: 1rem;
    margin: 0 -1rem 1.5rem -1rem;
    border-radius: 8px;
    transform: translateX(5px);
    }

    .popular-number {
    background: var(--color-primary);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
    }

    /* Tablette et desktop */
    @media (min-width: 768px) {
        .popular-number {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
    }

    /* === VIDÉO EMBED RESPONSIVE === */
    .video-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* ratio 16:9 */
    height: 0;
    overflow: hidden;
    margin: 1rem 0;
    border-radius: 10px;
    }

    /* Mobile : pleine largeur mais contenue */
    @media (max-width: 767px) {
        .video-container {
            width: calc(100vw - 30px);
            left: 50%;
            transform: translateX(-50%);
            margin: 1rem 0;
        }
    }


    .video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
    }

    /* === DASHBOARD PANEL === */
    .dashboard-panel {
    position: fixed;
    top: 0;
    right: -100vw; /* caché initialement à droite */
    width: 100vw; /* prend toute la largeur */
    height: 100vh;
    background-color: white;
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    z-index: 1050;
    padding: 20px;
    border-left: 5px solid #28a745;
    transition: right 0.4s ease-in-out;
    }


    .dashboard-panel.show {
    right: 0;
    }

    .backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1049;
    backdrop-filter: blur(2px);
    display: none;
    }

    .backdrop.show {
    display: block;
    }

    /* === CONTENU RESPONSIVE (Tablette) === */
    @media (max-width: 1024px) {
    .container {
        width: 100%;
        margin: 0 auto;
        padding: 0 30px;
    }
    }

    /* === CONTENU RESPONSIVE (Mobile) === */
    @media (max-width: 768px) {
    .container {
        width: 100vw;
        max-width: 100%;
        margin: 0;
        padding: 0 20px;
    }
    }

    /* === CONTENU RESPONSIVE (Très petit mobile) === */
    @media (max-width: 480px) {
    .container {
        width: 100vw;
        max-width: 100%;
        margin: 0;
        padding: 0 15px;
    }
    }

    .popular-number {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }

    .popular-title {
        font-size: 1rem;
    }

    .popular-image {
        width: 100%;
        max-width: 200px;
        height: 120px;
    }

    .encadre-jaune {
        font-size: 1.1rem;
        padding: 12px 25px;
    }

    .abonnement {
        font-size: 1rem;
    }

    .sous-titres {
        padding: 10px;
    }
    }

    /* === AMÉLIORATION PROFESSIONNELLE === */

    /* Container principal unifié */
    .home-content .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
    }

    /* Bordures subtiles pour toutes les sections */
    .main-content,
    .sidebar,
    .popular-section,
    .video-container {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    /* === STYLES DASHBOARD ACCORDÉON PREMIUM === */
    .dashboard-content {
        padding: 0 !important;
    }

    .dashboard-content h3 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 10px rgba(40, 167, 69, 0.1);
    }

    /* Cards accordéon avec effet glassmorphism */
    .dashboard-content .card {
        border-radius: 16px !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent !important;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(32, 201, 151, 0.15)) border-box;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.08) !important;
    }

    .dashboard-content .card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 12px 35px rgba(40, 167, 69, 0.2) !important;
        border-color: rgba(40, 167, 69, 0.4) !important;
    }

    /* Boutons accordéon ultra-stylés */
    .accordion-button {
        border-radius: 16px 16px 0 0 !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        padding: 1.3rem 1.5rem !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    /* Effet shimmer au survol */
    .accordion-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .accordion-button:hover::before {
        left: 100%;
    }

    .accordion-button:hover {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.08), rgba(32, 201, 151, 0.08)) !important;
        transform: translateX(8px);
        padding-left: 1.8rem !important;
    }

    .accordion-button.active {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.12), rgba(32, 201, 151, 0.12)) !important;
        border-bottom: 3px solid #28a745;
        box-shadow: inset 0 -3px 0 rgba(40, 167, 69, 0.2);
    }

    /* Icônes avec effet néon */
    .accordion-button i.fas:not(.transition-icon) {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 12px;
        margin-right: 14px;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.35),
                    inset 0 -2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-size: 1.1rem;
    }

    .accordion-button:hover i.fas:not(.transition-icon) {
        transform: scale(1.15) rotate(8deg);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5),
                    inset 0 -2px 5px rgba(0, 0, 0, 0.2);
        filter: brightness(1.1);
    }

    /* Flèche avec animation bounce élastique */
    .transition-icon {
        font-size: 1.2rem;
        color: #28a745 !important;
        transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        filter: drop-shadow(0 2px 4px rgba(40, 167, 69, 0.3));
    }

    /* Contenu déroulant avec dégradé subtil */
    .collapse-section {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.03), rgba(32, 201, 151, 0.03));
        border-radius: 0 0 16px 16px;
        border-top: 2px solid rgba(40, 167, 69, 0.15);
        backdrop-filter: blur(10px);
    }

    /* Liste du profil avec hover interactif */
    .collapse-section .list-group-item {
        padding: 1.1rem 0 !important;
        border-bottom: 1px dashed rgba(40, 167, 69, 0.1) !important;
        transition: all 0.3s ease;
    }

    .collapse-section .list-group-item:last-child {
        border-bottom: none !important;
    }

    .collapse-section .list-group-item:hover {
        background: linear-gradient(90deg, rgba(40, 167, 69, 0.05), transparent);
        padding-left: 1rem !important;
        border-radius: 10px;
        transform: translateX(5px);
    }

    .collapse-section .list-group-item strong {
        color: #28a745;
        font-weight: 700;
        min-width: 130px;
        display: inline-flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .collapse-section .list-group-item span:not(strong) {
        color: #2c3e50;
        font-weight: 500;
    }

    .collapse-section .list-group-item i.fas {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(32, 201, 151, 0.15));
        border-radius: 8px;
        color: #28a745;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.15);
    }

    /* Bouton "Gérer mes articles" premium */
    .collapse-section .btn-primary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border: none !important;
        padding: 0.7rem 1.8rem !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 12px !important;
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.35) !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .collapse-section .btn-primary:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5) !important;
        background: linear-gradient(135deg, #20c997 0%, #28a745 100%) !important;
    }

    .collapse-section .btn-primary:active {
        transform: translateY(-1px) scale(1.02);
    }

    /* Texte et descriptions stylés */
    .collapse-section .text-muted {
        color: #6c757d !important;
        font-size: 0.95rem;
        line-height: 1.7;
        font-style: italic;
    }

    /* Animations fluides */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateY(-20px) scale(0.98);
        }
    }

    /* Pulse animation pour icônes */
    @keyframes iconPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .accordion-button.active i.fas:not(.transition-icon) {
        animation: iconPulse 2s ease-in-out infinite;
    }

    /* Responsive premium */
    @media (max-width: 768px) {
        .accordion-button {
            font-size: 1rem !important;
            padding: 1.1rem 1.2rem !important;
        }

        .accordion-button i.fas:not(.transition-icon) {
            width: 35px;
            height: 35px;
            margin-right: 12px;
            font-size: 1rem;
        }

        .dashboard-content h3 {
            font-size: 1.4rem;
        }

        .collapse-section .btn-primary {
            font-size: 0.85rem !important;
            padding: 0.6rem 1.4rem !important;
        }
    }

    /* Cards articles avec bordures */
    .article-card,
    .featured-article,
    .sidebar-article {
        border: 1px solid #f0f0f0;
        padding: 1rem;
        border-radius: 10px;
    }

    /* Système "Voir plus" pour textes longs */
    .article-excerpt,
    .article-card-excerpt,
    .popular-content p {
        position: relative;
        overflow: hidden;
        max-height: 4.5em; /* 3 lignes environ */
        line-height: 1.5em;
        transition: max-height 0.3s ease;
    }

    .article-excerpt.expanded,
    .article-card-excerpt.expanded {
        max-height: none;
    }

    .read-more-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        cursor: pointer;
        margin-top: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .read-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    .read-more-btn i {
        transition: transform 0.3s ease;
    }

    .read-more-btn.expanded i {
        transform: rotate(180deg);
    }

    /* Vidéo avec bordure */
    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Section populaires améliorée */
    .popular-item {
        border: 1px solid #f5f5f5;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .popular-item:hover {
        border-color: #28a745;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }

    .popular-image {
        border: 2px solid #f0f0f0;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Textes tronqués avec ellipsis */
    .popular-title,
    .sidebar-article-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Amélioration responsive */
    @media (max-width: 768px) {
        .main-content,
        .sidebar,
        .popular-section {
            padding: 1.5rem;
        }

        .article-card,
        .popular-item {
            padding: 0.75rem;
        }
    }
</style>

<div class="floating-elements">
    <div class="floating-element" style="left: 10%; animation-delay: 0s;"></div>
    <div class="floating-element" style="left: 20%; animation-delay: 1s;"></div>
    <div class="floating-element" style="left: 30%; animation-delay: 2s;"></div>
    <div class="floating-element" style="left: 40%; animation-delay: 3s;"></div>
    <div class="floating-element" style="left: 50%; animation-delay: 4s;"></div>
    <div class="floating-element" style="left: 60%; animation-delay: 5s;"></div>
    <div class="floating-element" style="left: 70%; animation-delay: 6s;"></div>
    <div class="floating-element" style="left: 80%; animation-delay: 7s;"></div>
    <div class="floating-element" style="left: 90%; animation-delay: 8s;"></div>
</div>

<div class="home-content">
   <header>
    <div class="logo-container">
        <img src="https://images.squarespace-cdn.com/content/v1/67a35ff4b707014510c5bb88/da1128d8-83e5-4d1c-81cd-bfaea379e590/belier+2.jpg?format=1500w" alt="Logo Le Bélier-Intrépide">
    </div>
    <div class="sous-titres">
        <p class="phrase-principale">VOIR PLUS LOIN, COMPRENDRE MIEUX L'INFO, EN TEMPS RÉEL ET SANS FILTRE</p>
        <p class="abonnement">Abonnez-vous massivement pour 30 000 F CFA par an, recevez le journal du jour en PDF, ne manquez rien de l'actualité</p>
        <div class="encadre-jaune">
            <i class="fas fa-newspaper"></i> Echos du PDCI-RDA
        </div>
    </div>
</header>


<div class="container">

    <!-- Message de bienvenue spécial pour nouveaux utilisateurs -->
    @if (session('welcome_message'))
    <div class="alert alert-success alert-dismissible fade show" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; border-radius: 10px; margin-bottom: 20px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h5 class="mb-1">🎉 {{ session('welcome_message') }}</h5>
                <p class="mb-0" style="opacity: 0.9;">Explorez nos articles et personnalisez votre expérience !</p>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Section de bienvenue responsive pour utilisateur connecté -->
    @auth
    <div class="user-welcome-section" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 text-center text-md-start">
                <h2 class="mb-2" style="font-size: 1.5rem;">👋 Bienvenue, {{ Auth::user()->firstname }} !</h2>
                <p class="mb-0" style="opacity: 0.9; font-size: 0.9rem;">Restez informé des dernières actualités</p>
            </div>
            <div class="col-12 col-md-4 text-center text-md-end mt-3 mt-md-0">
                <!--<button class="btn btn-light btn-sm" onclick="toggleDashboard()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">
                    <i class="fas fa-user-circle me-2"></i>Mon Dashboard
                </button> -->
            </div>
        </div>
    </div>
    @endauth



    <div class="content-grid">
        <main class="main-content">
            @if($featuredArticles->count() > 0)
                <!-- S'il y a des articles en vedette, on affiche le premier comme article principal -->
                @php 
                    $mainArticle = $featuredArticles->first();
                    
                    // Gestion intelligente de l'image pour l'article principal
                    $mainImageUrl = null;
                    if($mainArticle->image) {
                        $storagePath = storage_path('app/public/' . $mainArticle->image);
                        if(file_exists($storagePath)) {
                            $mainImageUrl = asset('storage/' . $mainArticle->image);
                        } else {
                            $publicPath = public_path('image/' . basename($mainArticle->image));
                            if(file_exists($publicPath)) {
                                $mainImageUrl = asset('image/' . basename($mainArticle->image));
                            }
                        }
                    }
                    if(!$mainImageUrl) {
                        $mainImageUrl = asset('image/pdci1.jpg');
                    }
                @endphp
                <article class="featured-article" onclick="window.location.href='{{ route('article.show', $mainArticle->id) }}'" style="cursor: pointer;">
                    <img src="{{ $mainImageUrl }}" alt="{{ $mainArticle->titre }}" onerror="this.src='{{ asset('image/pdci1.jpg') }}'">
                    <div class="article-category {{ strtolower($mainArticle->category->nom ?? 'general') }}">
                        {{ strtoupper($mainArticle->category->nom ?? 'GÉNÉRAL') }}
                    </div>
                    <h2 class="article-title">{{ $mainArticle->titre }}</h2>
                    <p class="article-excerpt">{{ $mainArticle->extrait ?: Str::limit(strip_tags($mainArticle->contenu), 200) }}</p>
                    <div class="article-meta">
                        <span>📅 {{ $mainArticle->created_at->format('d M Y') }}</span>
                        @if($mainArticle->user)
                            <span>👤 {{ $mainArticle->user->firstname }} {{ $mainArticle->user->lastname }}</span>
                        @endif
                        <span>📖 {{ ceil(str_word_count(strip_tags($mainArticle->contenu)) / 200) }} min de lecture</span>
                        @if($mainArticle->category && strtolower($mainArticle->category->nom) === 'politique')
                            <span style="color: #dc3545; font-weight: bold;">
                                <i class="fas fa-lock"></i> Réservé abonnés
                            </span>
                        @endif
                    </div>
                </article>
                <!-- Grille d'articles dynamiques - SIMPLIFIÉE -->
                <div class="articles-grid">
                @php
                    // Images de fallback par catégorie depuis votre dossier local
                    $categoryImages = [
                        'economie' => 'economie2.webp',
                        'sport' => 'sport-monde.jpg',
                        'politique' => 'politique.jpg',
                        'culture et média' => 'culture.webp',
                        'pdci-rda' => 'pdci1.jpg',
                        'société' => 'justice.webp',
                        'afrique' => 'ivoire.jpg',
                        'dossiers' => 'im3.png'
                    ];
                @endphp

                {{-- Afficher les 6 prochains articles après le principal --}}
                @foreach($featuredArticles->skip(1)->take(6) as $article)
                    <article class="article-card" onclick="window.location.href='{{ route('article.show', $article->id) }}'" style="cursor: pointer;">
                        {{-- Image de l'article avec gestion intelligente --}}
                        @php
                            $imageUrl = null;
                            
                            if($article->image) {
                                // Vérifier si le fichier existe dans storage
                                $storagePath = storage_path('app/public/' . $article->image);
                                if(file_exists($storagePath)) {
                                    $imageUrl = asset('storage/' . $article->image);
                                } else {
                                    // Si le fichier n'existe pas dans storage, chercher dans public/image
                                    $publicPath = public_path('image/' . basename($article->image));
                                    if(file_exists($publicPath)) {
                                        $imageUrl = asset('image/' . basename($article->image));
                                    }
                                }
                            }
                            
                            // Si toujours pas d'image, utiliser le fallback par catégorie
                            if(!$imageUrl) {
                                $catKey = strtolower($article->category->nom ?? 'general');
                                $fallbackImage = $categoryImages[$catKey] ?? 'pdci1.jpg';
                                $imageUrl = asset('image/' . $fallbackImage);
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $article->titre }}" onerror="this.src='{{ asset('image/pdci1.jpg') }}'">

                        <div class="article-card-content">
                            @php
                                $categoryClass = 'general';
                                $categoryName = $article->category->nom ?? 'Général';
                                switch(strtolower($categoryName)) {
                                    case 'economie': $categoryClass = 'economie'; break;
                                    case 'sport': $categoryClass = 'sport'; break;
                                    case 'culture et média': $categoryClass = 'culture'; break;
                                    case 'politique': $categoryClass = 'politique'; break;
                                    case 'pdci-rda': $categoryClass = 'pdci'; break;
                                    case 'afrique': $categoryClass = 'afrique'; break;
                                    case 'société': $categoryClass = 'societe'; break;
                                    case 'dossiers': $categoryClass = 'dossiers'; break;
                                }
                            @endphp

                            <div class="article-category {{ $categoryClass }}">{{ strtoupper($categoryName) }}</div>
                            <h3 class="article-card-title">{{ $article->titre }}</h3>
                            <p class="article-card-excerpt">{{ Str::limit($article->extrait ?: strip_tags($article->contenu), 120) }}</p>

                            <div class="article-meta">
                                <span>📅 {{ $article->created_at->format('d M Y') }}</span>

                                @if($categoryClass === 'politique')
                                    <span style="margin-left: 10px;">
                                        <span style="color: #dc3545; font-weight: bold;">
                                            <i class="fas fa-lock"></i> Réservé abonnés
                                        </span>
                                    </span>
                                @endif

                                @if($article->document_path)
                                    <span style="margin-left: 10px;">
                                        <span style="color: #28a745; font-weight: bold;" title="Document disponible">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
                {{-- Message si aucun article n'est disponible --}}
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-newspaper" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="color: #666;">Aucun article publié pour le moment</h3>
                    <p style="color: #999;">Revenez bientôt pour découvrir nos dernières actualités !</p>
                </div>
            @endif
        </main>

        <aside class="sidebar">
            <!-- Section utilisateur connecté dans la sidebar -->
            @auth
            <div class="sidebar-section" style="background: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
                <h3 class="sidebar-title" style="color: #28a745; display: flex; align-items: center;">
                    <i class="fas fa-user-circle me-2"></i>Mon Profil
                </h3>
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="width: 40px; height: 40px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px;">
                        {{ strtoupper(substr(Auth::user()->firstname, 0, 1) . substr(Auth::user()->lastname, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 0.9rem;">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                        <div style="font-size: 0.8rem; color: #666;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <a href="{{ route('articles.index') }}" class="btn btn-sm" style="background: #28a745; color: white; font-size: 0.8rem; padding: 5px 10px;">Mes Articles</a>
                    <button onclick="toggleDashboard()" class="btn btn-sm btn-outline-secondary" style="font-size: 0.8rem; padding: 5px 10px;">Mon compte</button>
                </div>
            </div>
            @endauth

            <div class="sidebar-section">
                <h3 class="sidebar-title">À la Une</h3>
                @forelse($sidebarArticles as $sidebarArticle)
                <article class="sidebar-article" onclick="window.location.href='{{ route('article.show', $sidebarArticle->id) }}'" style="cursor: pointer;">
                    @php
                        $sidebarImageUrl = null;
                        if($sidebarArticle->image) {
                            $storagePath = storage_path('app/public/' . $sidebarArticle->image);
                            if(file_exists($storagePath)) {
                                $sidebarImageUrl = asset('storage/' . $sidebarArticle->image);
                            } else {
                                $publicPath = public_path('image/' . basename($sidebarArticle->image));
                                if(file_exists($publicPath)) {
                                    $sidebarImageUrl = asset('image/' . basename($sidebarArticle->image));
                                }
                            }
                        }
                        if(!$sidebarImageUrl) {
                            $sidebarImageUrl = asset('image/politique.jpg');
                        }
                    @endphp
                    <img src="{{ $sidebarImageUrl }}" alt="{{ $sidebarArticle->titre }}" onerror="this.src='{{ asset('image/politique.jpg') }}'">
                    <div class="sidebar-article-content">
                        <h4 class="sidebar-article-title">{{ Str::limit($sidebarArticle->titre, 60) }}</h4>
                        <div class="sidebar-article-date">{{ $sidebarArticle->created_at->format('d M Y') }}</div>
                    </div>
                </article>
                @empty
                <article class="sidebar-article">
                    <img src="{{asset('image/politique.jpg')}}" alt="Actualité">
                    <div class="sidebar-article-content">
                        <h4 class="sidebar-article-title">AAM2025 à Abuja : Afreximbank trace la route de l'Afrique résiliente</h4>
                        <div class="sidebar-article-date">26 Juin 2025</div>
                    </div>
                </article>
                @endforelse
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Catégories</h3>
                <ul class="categories-list">
                    <li><a href="{{ route('app_category', ['categorie' => 'PDCI-RDA']) }}">🏛️ PDCI-RDA</a></li>
                    <li><a href="{{ route('app_category', ['categorie' => 'Politique']) }}">⚖️ Politique</a></li>
                    <li><a href="{{ route('app_category', ['categorie' => 'Economie']) }}">💰 Économie</a></li>
                    <li><a href="{{ route('app_category', ['categorie' => 'Sport']) }}">⚽ Sport</a></li>
                    <li><a href="{{ route('app_category', ['categorie' => 'Culture et média']) }}">🎭 Culture & Média</a></li>
                    <li><a href="{{ route('app_category', ['categorie' => 'Afrique']) }}">🌍 Afrique</a></li>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Suivez-nous</h3>
                <div class="social-links">
                    <a href="#" title="Facebook">📘</a>
                    <a href="#" title="Twitter">🐦</a>
                    <a href="#" title="Instagram">📷</a>
                    <a href="#" title="YouTube">📺</a>
                    <a href="#" title="LinkedIn">💼</a>
                </div>
            </div>

            <div class="sidebar-section">
                <h3 class="sidebar-title">Newsletter</h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">Restez informé de nos dernières actualités</p>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="email" placeholder="Votre email" style="flex: 1; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    <button style="background: #ff6b35; color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">S'inscrire</button>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Dashboard Panel (caché par défaut) -->
@auth
<div id="dashboardPanel" class="dashboard-panel">
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Mon compte</h5>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <a class="dropdown-toggle text-decoration-none d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="user-initials me-2">
                        {{ strtoupper(substr(Auth::user()->lastname, 0, 1) . substr(Auth::user()->firstname, 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" onclick="closeDashboard()">Fermé</button></li>
                    <li>
                        <form id="logout-form" action="{{ route('app_logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Se déconnecter
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <h3>Bienvenue, {{ Auth::user()->lastname }} 👋</h3>

        <!-- Section Mes Articles (Accordéon) -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-0">
                <button class="accordion-button w-100 text-start px-3 py-3 border-0 bg-white d-flex justify-content-between align-items-center"
                        onclick="toggleDashboardSection('articlesSection', this)"
                        style="font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <span>
                        <i class="fas fa-newspaper me-2" style="color: #28a745;"></i>
                        Mes Articles
                    </span>
                    <i class="fas fa-chevron-down transition-icon" style="color: #28a745; transition: transform 0.3s;"></i>
                </button>
                <div id="articlesSection" class="collapse-section px-3 pb-3" style="display: none; padding-top: 1rem;">
                    <p class="text-muted mb-3">Gérez tous vos articles publiés et brouillons</p>
                    <a href="{{ route('articles.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Gérer mes articles
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Profil (Accordéon) -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-0">
                <button class="accordion-button w-100 text-start px-3 py-3 border-0 bg-white d-flex justify-content-between align-items-center"
                        onclick="toggleDashboardSection('profileSection', this)"
                        style="font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <span>
                        <i class="fas fa-user me-2" style="color: #28a745;"></i>
                        Mon Profil
                    </span>
                    <i class="fas fa-chevron-down transition-icon" style="color: #28a745; transition: transform 0.3s;"></i>
                </button>
                <div id="profileSection" class="collapse-section px-3 pb-3" style="display: none; padding-top: 1rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <strong><i class="fas fa-id-card me-2 text-muted"></i>Nom :</strong>
                            <span>{{ Auth::user()->firstname }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <strong><i class="fas fa-signature me-2 text-muted"></i>Prénom :</strong>
                            <span>{{ Auth::user()->lastname }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <strong><i class="fas fa-envelope me-2 text-muted"></i>Email :</strong>
                            <span class="text-break">{{ Auth::user()->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section Adresse (Accordéon) -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-0">
                <button class="accordion-button w-100 text-start px-3 py-3 border-0 bg-white d-flex justify-content-between align-items-center"
                        onclick="toggleDashboardSection('addressSection', this)"
                        style="font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <span>
                        <i class="fas fa-map-marker-alt me-2" style="color: #28a745;"></i>
                        Mon Adresse
                    </span>
                    <i class="fas fa-chevron-down transition-icon" style="color: #28a745; transition: transform 0.3s;"></i>
                </button>
                <div id="addressSection" class="collapse-section px-3 pb-3" style="display: none; padding-top: 1rem;">
                    @if(Auth::user()->address)
                        <p class="mb-0">
                            <i class="fas fa-home me-2 text-muted"></i>
                            {{ Auth::user()->address }}
                        </p>
                    @else
                        <p class="text-muted fst-italic mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucune adresse enregistrée
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div id="backdrop" class="backdrop" onclick="closeDashboard()"></div>
@endauth

<br><br>

<!-- Section vidéo de présentation -->
<div class="container">
    <div class="video-container">
        <iframe
            src="https://www.youtube.com/embed/dQw4w9WgXcQ"
            title="Vidéo de présentation - Le Bélier Intrépide"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    </div>
</div>

<br><br>

    <!--pour la deuxieme sections --->

    <div class="container">
        <div class="popular-section">
            <h2 class="section-title">Les + Populaires</h2>

            <ul class="popular-list">
                @forelse($popularArticles as $index => $popularArticle)
                <li class="popular-item">
                    <a href="{{ route('article.show', $popularArticle->id) }}" class="popular-link">
                        <div class="popular-number">{{ $index + 1 }}</div>
                        @php
                            $popularImageUrl = null;
                            if($popularArticle->image) {
                                $storagePath = storage_path('app/public/' . $popularArticle->image);
                                if(file_exists($storagePath)) {
                                    $popularImageUrl = asset('storage/' . $popularArticle->image);
                                } else {
                                    $publicPath = public_path('image/' . basename($popularArticle->image));
                                    if(file_exists($publicPath)) {
                                        $popularImageUrl = asset('image/' . basename($popularArticle->image));
                                    }
                                }
                            }
                            if(!$popularImageUrl) {
                                $popularImageUrl = asset('image/economie2.webp');
                            }
                        @endphp
                        <img src="{{ $popularImageUrl }}" alt="{{ $popularArticle->titre }}" class="popular-image" onerror="this.src='{{ asset('image/economie2.webp') }}'">
                        <div class="popular-content">
                            @if($popularArticle->category)
                                @php
                                    $categoryClass = 'economie';
                                    switch(strtolower($popularArticle->category->nom)) {
                                        case 'sport': $categoryClass = 'sport'; break;
                                        case 'politique': $categoryClass = 'politique'; break;
                                        case 'culture et média': $categoryClass = 'culture'; break;
                                        case 'pdci-rda': $categoryClass = 'pdci'; break;
                                    }
                                @endphp
                                <div class="category-tag {{ $categoryClass }}">{{ strtoupper($popularArticle->category->nom) }}</div>
                            @endif
                            <h3 class="popular-title">{{ Str::limit($popularArticle->titre, 80) }}</h3>
                            <div class="popular-meta">
                                <span>📅 {{ $popularArticle->created_at->format('d M Y') }}</span>
                                @if($popularArticle->category && strtolower($popularArticle->category->nom) === 'politique')
                                    <span style="color: #dc3545; font-weight: bold; font-size: 0.85rem;">
                                        <i class="fas fa-lock"></i> Réservé abonnés
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </li>
                @empty
                <li class="popular-item">
                    <a href="#" class="popular-link">
                        <div class="popular-number">1</div>
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=300&h=200&fit=crop" alt="Résultats BEPC" class="popular-image">
                        <div class="popular-content">
                            <div class="category-tag economie">ÉCONOMIE</div>
                            <h3 class="popular-title">BEPC 2023 : Les résultats fin prêt, voici où les trouver</h3>
                            <div class="popular-meta">
                                <span>📅 26 Juin 2025</span>
                                <span>👁️ 12,543 vues</span>
                            </div>
                        </div>
                    </a>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
<br><br><br>

<script>
function toggleDashboard() {
    const panel = document.getElementById('dashboardPanel');
    const backdrop = document.getElementById('backdrop');
    panel.classList.toggle('show');
    backdrop.classList.toggle('show');
}

function closeDashboard() {
    document.getElementById('dashboardPanel').classList.remove('show');
    document.getElementById('backdrop').classList.remove('show');
}

function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    section.style.display = section.style.display === 'block' ? 'none' : 'block';
}

// Fonction premium pour les accordéons du dashboard avec animations avancées
function toggleDashboardSection(sectionId, button) {
    const section = document.getElementById(sectionId);
    const icon = button.querySelector('.transition-icon');

    // Toggle de l'affichage avec animation douce et élastique
    if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block';
        section.style.animation = 'slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        icon.style.transform = 'rotate(180deg)';
        button.classList.add('active');

        // Effet sonore visuel (vibration subtile)
        button.style.animation = 'none';
        setTimeout(() => {
            button.style.animation = 'iconPulse 0.3s ease-out';
        }, 10);
    } else {
        section.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            section.style.display = 'none';
        }, 280);
        icon.style.transform = 'rotate(0deg)';
        button.classList.remove('active');
    }
}

// Animations CSS avancées (déjà dans le style, mais renforcées ici)
if (!document.getElementById('dashboard-animations')) {
    const style = document.createElement('style');
    style.id = 'dashboard-animations';
    style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateY(-20px) scale(0.98);
            }
        }

        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    `;
    document.head.appendChild(style);
}

</script>

    <!--<form method="POST" action="{{ route('app_logout') }}">
    @csrf
    <button type="submit">TEST LOGOUT</button>
</form>



    <script>

         // Ajoute le flou sur le header au scroll
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if(window.scrollY > 10){
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

        class DynamicCarousel {
            constructor() {
                this.currentSlide = 0;
                this.totalSlides = 3;
                this.autoSlideInterval = 5000;
                this.progressInterval = 50;
                this.isAutoPlay = true;

                this.track = document.getElementById('carouselTrack');
                this.prevBtn = document.getElementById('prevBtn');
                this.nextBtn = document.getElementById('nextBtn');
                this.progressFill = document.getElementById('progressFill');
                this.indicatorsContainer = document.getElementById('indicators');

                this.init();
            }

            init() {
                this.createIndicators();
                this.updateCarousel();
                this.setupEventListeners();
                this.startAutoSlide();
                this.startProgressBar();
                this.createFloatingElements();
            }

            createIndicators() {
                for (let i = 0; i < this.totalSlides; i++) {
                    const indicator = document.createElement('div');
                    indicator.className = 'indicator';
                    indicator.addEventListener('click', () => this.goToSlide(i));
                    this.indicatorsContainer.appendChild(indicator);
                }
            }

            setupEventListeners() {
                this.prevBtn.addEventListener('click', () => this.prevSlide());
                this.nextBtn.addEventListener('click', () => this.nextSlide());

                const container = document.querySelector('.carousel-container');
                container.addEventListener('mouseenter', () => this.pauseAutoSlide());
                container.addEventListener('mouseleave', () => this.resumeAutoSlide());

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') this.prevSlide();
                    if (e.key === 'ArrowRight') this.nextSlide();
                });

                let startX = 0;
                let startY = 0;

                container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                });

                container.addEventListener('touchend', (e) => {
                    if (!startX || !startY) return;

                    const endX = e.changedTouches[0].clientX;
                    const endY = e.changedTouches[0].clientY;

                    const diffX = startX - endX;
                    const diffY = startY - endY;

                    if (Math.abs(diffX) > Math.abs(diffY)) {
                        if (Math.abs(diffX) > 50) {
                            if (diffX > 0) {
                                this.nextSlide();
                            } else {
                                this.prevSlide();
                            }
                        }
                    }

                    startX = 0;
                    startY = 0;
                });
            }
updateCarousel() {
    const translateX = -this.currentSlide * 100;
    this.track.style.transform = `translateX(${translateX}%)`;

    const indicators = this.indicatorsContainer.querySelectorAll('.indicator');
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === this.currentSlide);
    });
}

//pour la deuxieme section de javascripte

 <script>
        // Ajouter des événements de clic pour les liens
        document.querySelectorAll('.popular-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Article cliqué:', this.querySelector('.popular-title').textContent);
                // Ici vous pouvez ajouter votre logique de navigation
                // Par exemple: window.location.href = 'article-details.html?id=' + articleId;
            });
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.popular-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // === SYSTÈME "VOIR PLUS" POUR LES TEXTES LONGS ===
            addReadMoreButtons();
        });

        // Fonction pour ajouter les boutons "Voir plus"
        function addReadMoreButtons() {
            // Sélectionner tous les extraits d'articles
            const excerpts = document.querySelectorAll('.article-excerpt, .article-card-excerpt');

            excerpts.forEach(excerpt => {
                // Vérifier si le texte est tronqué (plus de 3 lignes)
                if (excerpt.scrollHeight > excerpt.clientHeight + 5) {
                    // Créer le bouton "Voir plus"
                    const readMoreBtn = document.createElement('button');
                    readMoreBtn.className = 'read-more-btn';
                    readMoreBtn.innerHTML = '<span>Voir plus</span> <i class="fas fa-chevron-down"></i>';

                    // Insérer le bouton après l'extrait
                    excerpt.parentNode.insertBefore(readMoreBtn, excerpt.nextSibling);

                    // Ajouter l'événement click
                    readMoreBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Toggle la classe expanded
                        excerpt.classList.toggle('expanded');
                        readMoreBtn.classList.toggle('expanded');

                        // Changer le texte du bouton
                        if (excerpt.classList.contains('expanded')) {
                            readMoreBtn.innerHTML = '<span>Voir moins</span> <i class="fas fa-chevron-down"></i>';
                        } else {
                            readMoreBtn.innerHTML = '<span>Voir plus</span> <i class="fas fa-chevron-down"></i>';
                        }
                    });
                }
            });
        }
    </script>

    <!-- Widget Chatbot (seulement pour les utilisateurs connectés) -->
    @auth
        @include('components.chatbot-widget')
    @endauth

</div> <!-- Fin home-content -->

@endsection
