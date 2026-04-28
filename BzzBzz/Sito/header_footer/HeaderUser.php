<?php
// ← DEVE essere la prima riga assoluta del file
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Strict',
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
                <li class="has-submenu">
                    <span class="nopage">SHOP</span>
                    <ul class="submenu">
                        <li><a href="ShopRicambi.php">Ricambi</a></li>
                        <li><a href="gadgetShop.php">Abbigliamento</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <span class="nopage">RADUNI</span>
                    <ul class="submenu">
                        <li><a href="">Foto e Info</a></li>
                        <li><a href="">Iscrizione</a></li>
                    </ul>
                </li>
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
    <p>Ultimi aggiornamenti: Prossimo raduno nazionale a Maggio 2026! Iscrizioni aperte dal 33 maggio. - Nuovo merchandising disponibile nello shop!</p>
</div>

