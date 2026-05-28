<!-- Font Awesome (icônes) à mettre dans le <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  footer {
    background-color: rgba(34, 34, 34, 0.9);
    color: white;
    padding: 40px 10px;
    text-align: center;
  }

  .texte {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 30px;
  }

  .footer-content {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 60px; /* Séparation plus nette */
    margin-bottom: 40px;
  }

  .footer-column {
    flex: 1 1 250px;
    max-width: 300px;
    text-align: left;
  }

  .footer-column p {
    margin: 10px 0;
  }

  .footer-column i {
    margin-right: 10px;
    color: #00d1b2;
  }

  .social-icons {
    margin-top: 40px;
  }

  .social-icons a {
    color: white;
    margin: 0 10px;
    font-size: 1.5rem;
    transition: color 0.3s ease;
  }

  .social-icons a:hover {
    color: #00d1b2;
  }

  .footer-bottom {
    margin-top: 40px;
    font-size: 0.9rem;
    color: #ccc;
  }

  @media (max-width: 600px) {
    .footer-column {
      text-align: center;
    }
  }
</style>

<footer>
  <div class="texte">Informations</div>

  <div class="footer-content">
    <div class="footer-column">
      <p><i class="fas fa-phone"></i> Contact : +225 07 49 30 86 05</p>
      <p><i class="fab fa-facebook"></i> Facebook : Le Bélier Intrépide</p>
      <p><i class="fab fa-instagram"></i> Instagram : @lebelierci</p>
    </div>

    <div class="footer-column">
      <p><i class="fab fa-whatsapp"></i> WhatsApp : 07 49 38 10 656</p>
      <p><i class="fas fa-envelope"></i> Email : lebelierintrepide@gmail.com</p>
      <p><i class="fab fa-twitter"></i> Twitter : @lebelier_ci</p>
    </div>
  </div>

  <div class="social-icons">
    <a href="#"><i class="fab fa-facebook-f"></i></a>
    <a href="#"><i class="fab fa-instagram"></i></a>
    <a href="#"><i class="fab fa-whatsapp"></i></a>
    <a href="#"><i class="fab fa-twitter"></i></a>
    <a href="#"><i class="fab fa-linkedin-in"></i></a>
    <a href="#"><i class="fab fa-youtube"></i></a>
    <a href="#"><i class="fab fa-tiktok"></i></a>
  </div>

  <div class="footer-bottom">
    &copy; 2025 Le Bélier-Intrépide. Tous droits réservés.
  </div>
</footer>
