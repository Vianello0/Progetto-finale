<?php
require_once '../header_footer/HeaderUser.php';
?>

<link rel="stylesheet" href="../CSS/Hpage.css">
<script src="../JS/imgScroll.js" defer></script>
<title>BzzBzz - Home</title>

<div class="home-container">
    <div class="hero-section">
        <h1>Benvenuto su BzzBzz</h1>
        <p>La più grande community di vespisti d'Italia</p>
    </div>

    <div class="home-grid">
        <a href="catalogoVespa.php" class="nav-card">
            <div class="card-content">
                <h2>GUARDA I MODELLI</h2>
            </div>
            <div class="slideshow-container">
                <img id="fading-image" src="../Img/Vespe/50.jpg" alt="Modelli Vespa">
            </div>
        </a>

        <a href="iscrizioneRad.php" class="nav-card">
            <div class="card-content">
                <h2>ISCRIVITI AI RADUNI</h2>
            </div>
            <div class="slideshow-container">
                <img src="../Img/Loghi/Raduni.jpg" alt="Raduni">
            </div>
        </a>

        <a href="ShopRicambi.php" class="nav-card">
            <div class="card-content">
                <h2>SHOP RICAMBI</h2>
            </div>
            <div class="slideshow-container">
                <img src="../Img/loghi/Shop.jpg" alt="Shop Ricambi">
            </div>
        </a>
    </div>
</div>

</body>
</html>