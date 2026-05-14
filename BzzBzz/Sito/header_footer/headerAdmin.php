<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/headerAdmin.css">
    <link rel="stylesheet" href="../../CSS/admin.css"> <!--così non devo metterlo in ogni pagina admin-->
</head>
<body>
    <header class="admin-header">
        <div class="admin-logo">
            <a href="adminHPage.php">BzzBzz <span>Admin Panel</span></a>
        </div>
        <nav class="admin-nav">
            <ul>
                <li><a href="adminHPage.php">Dashboard</a></li>
                <li><a href="creaRaduni.php">Crea Raduni</a></li>
                <li><a href="iscrizioniRad.php">Iscrizioni Raduni</a></li>
                <li><a href="visualizzaUtenti.php">Gestisci Utenti</a></li>
            </ul>
        </nav>
        <div class="admin-actions">
            <span class="admin-greeting">Ciao, <?= htmlspecialchars($_SESSION['nome']) ?></span>
            <a href="../../include/logout.php" class="btn-logout">Logout</a>
        </div>
    </header>
