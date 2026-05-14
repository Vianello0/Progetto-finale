<?php
// controlla  se è già avviata una session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,//chiude e distrugge il coockie appena viene chiuso il browser
        'path'     => '/', //coockie valido in tutte le pagine interne al sito e non solo questa
        'httponly' => true,
        'samesite' => 'Strict', //mantiene il coockie solo nel sito corrente
    ]);
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/header.css">
</head>
<body>
    <header id="main-header">
    <div class="header-container">
        <a href="hPage.php" class="logo-link">
            <img src="../Img/Loghi/LogoRettangolo.png" alt="Logo">
        </a>

        <nav class="menu">
            <ul>
                <li><a href="hPage.php">HOME</a></li>
                <li><a href="storia.php">STORIA</a></li>
                <li><a href="ShopRicambi.php">SHOP</a></li>
                <li><a href="iscrizioneRad.php">RADUNI</a></li>
            </ul>
        </nav>
        <nav>
  <?php if (isset($_SESSION['IDWasper'])): ?>
    <!-- Utente loggato -->
    <a href="areaPersonale.php">Ciao, <?= htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['cognome']) ?></a>
    <a href="../include/logout.php" class="btn-register">Logout</a>
  <?php else: ?>
    <!-- Utente non loggato -->
    <div class="header-actions">
      <a href="../Pagine/loginForm.php" class="btn-login">Accedi</a>
      <a href="../Pagine/register.php" class="btn-register">Registrati</a>
    </div>
  <?php endif; ?>
</nav>
    </div>
</header>

<div class="news-ticker">
    <p>Per gli utenti registrati, riceveranno a casa lo splendido gadget BzzBzz.</p>
</div>

