<?php
require_once '../include/Ricambi.php';
require_once '../header_footer/HeaderUser.php';
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../CSS/shop.css">
<title>BzzBzz - Shop Ricambi</title>

<div class="shop-container">
    <div class="shop-header">
        <h1>Shop Ricambi</h1>
        <p>Trova il pezzo perfetto per la tua Vespa</p>
    </div>

    <div class="shop-layout">
        <aside class="shop-sidebar">
            <form method="GET" action="ShopRicambi.php" class="filter-form">
                <h3>Filtra Ricambi</h3>
                
                <div class="filter-group">
                    <label for="tipo">Tipo di Ricambio</label>
                    <select name="tipo" id="tipo" class="custom-select">
                        <option value="">Tutti i tipi</option>
                        <?php foreach ($tipi as $t): ?>
                            <option value="<?= htmlspecialchars($t) ?>" <?= $selectedTipo === $t ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="ordine">Ordina per Prezzo</label>
                    <select name="ordine" id="ordine" class="custom-select">
                        <option value="">Rilevanza</option>
                        <option value="asc" <?= $selectedOrdine === 'asc' ? 'selected' : '' ?>>Crescente</option>
                        <option value="desc" <?= $selectedOrdine === 'desc' ? 'selected' : '' ?>>Decrescente</option>
                    </select>
                </div>

                <div class="filter-group modelli-group">
                    <label>Modelli Compatibili</label>
                    <div class="checkbox-list">
                        <?php foreach ($modelli as $m): ?>
                            <?php $isChecked = in_array($m, $selectedModelli); ?>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="modelli[]" value="<?= htmlspecialchars($m) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                <span class="checkbox-label"><?= htmlspecialchars($m) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn-filter">Applica Filtri</button>
            </form>
        </aside>

        <main class="shop-main">
            <?php if (empty($ricambi)): ?>
                <div class="empty-state">
                    <h2>Nessun ricambio trovato</h2>
                    <p>Prova a modificare i filtri per trovare ciò che cerchi.</p>
                </div>
            <?php else: ?>
                <div class="ricambi-grid">
                    <?php foreach ($ricambi as $ricambio): ?>
                        <div class="ricambio-card">
                            <div class="ricambio-info">
                                <span class="ricambio-badge"><?= htmlspecialchars($ricambio['Tipo']) ?></span>
                                <h2 class="ricambio-nome"><?= htmlspecialchars($ricambio['Nome']) ?></h2>
                                <p class="ricambio-descrizione"><?= htmlspecialchars($ricambio['Descrizione']) ?></p>
                                <ul class="ricambio-details">
                                    <li><span>Modello originale:</span> <strong><?= htmlspecialchars($ricambio['Modello']) ?></strong></li>
                                </ul>
                            </div>
                            <div class="ricambio-actions">
                                <div class="prezzo">€ <?= number_format($ricambio['Prezzo'], 2, ',', '.') ?></div>
                                <button class="btn-buy">Aggiungi</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

</body>
</html>