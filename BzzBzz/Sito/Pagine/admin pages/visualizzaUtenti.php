<?php
require_once '../../include/DBHandler.php';
require_once '../../include/menuChoice.php';

$pdo = DBHandler::getConnection();

// Fetch all users
$stmt = $pdo->query("SELECT IDWasper, Nome, Cognome, Mail, DataNascita, admin FROM Wasper ORDER BY IDWasper ASC");
$users = $stmt->fetchAll();
?>
<title>Admin - Gestione Utenti</title>

<div class="admin-container">
    <h1 class="admin-title">Gestione Utenti</h1>
    <p class="admin-subtitle">Visualizza e modifica i privilegi degli utenti registrati.</p>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Data di Nascita</th>
                    <th>Privilegi Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['IDWasper']) ?></td>
                        <td><?= htmlspecialchars($u['Nome']) ?></td>
                        <td><?= htmlspecialchars($u['Cognome']) ?></td>
                        <td><?= htmlspecialchars($u['Mail']) ?></td>
                        <td><?= htmlspecialchars($u['DataNascita']) ?></td>
                        <td>
                            <label class="admin-toggle">
                                <input type="checkbox" class="admin-checkbox" data-id="<?= $u['IDWasper'] ?>" <?= $u['admin'] ? 'checked' : '' ?> <?= $u['IDWasper'] == $_SESSION['IDWasper'] ? 'disabled title="Non puoi modificare i tuoi stessi privilegi"' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Nessun utente trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Toast notification -->
<div id="toast" class="toast">Notifica</div>

<script src="../../JS/admin.js"></script>

</body>
</html>
