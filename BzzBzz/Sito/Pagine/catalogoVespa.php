<?php
require_once '../header_footer/HeaderUser.php';
require_once '../include/catalogoVespe.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../CSS/catalogoVespe.css">

<div class="catalog-container">
    <div class="catalog-header">
        <h1>Catalogo Vespe</h1>
        <p>Scopri i modelli e aggiungili al tuo garage virtuale.</p>
    </div>

    <div class="vespa-grid">
        <?php foreach ($vespas as $vespa): ?>
            <?php 
                $modello = htmlspecialchars($vespa['Modello']);
                $isOwned = isset($userVespas[$vespa['Modello']]);
            ?>
            <div class="vespa-card">
                <div class="vespa-info">
                    <h2 class="vespa-model"><?= $modello ?></h2>
                    <ul class="vespa-details">
                        <li><span>Cilindrata:</span> <strong><?= htmlspecialchars($vespa['Cilindrata']) ?> cc</strong></li>
                        <li><span>Marce:</span> <strong><?= htmlspecialchars($vespa['nMarce']) ?></strong></li>
                        <li><span>Velocità Max:</span> <strong><?= htmlspecialchars($vespa['vMax']) ?> km/h</strong></li>
                        <li><span>Posti:</span> <strong><?= htmlspecialchars($vespa['Posti']) ?></strong></li>
                    </ul>
                </div>
                <div class="vespa-actions">
                    <?php if ($isLoggedIn): ?>
                        <span class="action-text"><?= $isOwned ? 'Nel tuo garage' : 'Aggiungi al garage' ?></span>
                        <label class="checkbox-wrapper">
                            <input type="checkbox" class="vespa-toggle" data-modello="<?= $modello ?>" <?= $isOwned ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                        </label>
                    <?php else: ?>
                        <p class="login-prompt"><a href="loginForm.php" style="color: inherit; text-decoration: underline;">Accedi</a> per aggiungere al garage</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($isLoggedIn): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.vespa-toggle');
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const modello = this.getAttribute('data-modello');
            const isChecked = this.checked;
            const textSpan = this.closest('.vespa-actions').querySelector('.action-text');
            
            fetch('../include/toggleVespa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    modello: modello,
                    checked: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    textSpan.textContent = isChecked ? 'Nel tuo garage' : 'Aggiungi al garage';
                } else {
                    alert('Errore: ' + (data.message || 'Si è verificato un errore'));
                    this.checked = !isChecked; // Revert
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore di rete. Riprova.');
                this.checked = !isChecked; // Revert
            });
        });
    });
});
</script>
<?php endif; ?>

</body>
</html>