<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/logReg.css">
</head>
<body>

<div class="card card--wide">

    <div class="brand">
        <div class="brand-icon">
            <a href="../Pagine/hPage.php">
                <img src="../Img/Loghi/Logo.png" alt="alt">
            </a>
        </div>
        <h1>Crea un account</h1>
        <p class="subtitle">Compila i campi per registrarti</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;margin-top:1px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../include/registrazione.php" novalidate>

        <p class="section-label">Dati personali</p>

        <div class="field-row">
            <div class="field">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Mario"
                    value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="cognome">Cognome</label>
                <input type="text" id="cognome" name="cognome" placeholder="Rossi"
                    value="<?= htmlspecialchars($_POST['cognome'] ?? '') ?>" required>
            </div>
        </div>

        <div class="field">
            <label for="mail">Email</label>
            <input type="email" id="mail" name="mail" placeholder="nome@esempio.it"
                value="<?= htmlspecialchars($_POST['mail'] ?? '') ?>" autocomplete="email" required>
        </div>

        <div class="field">
            <label for="passwordUt">Password</label>
            <div class="password-wrap">
                <input type="password" id="passwordUt" name="passwordUt"
                    placeholder="Min. 4 caratteri" autocomplete="new-password" required>
                <button type="button" class="toggle-pw" aria-label="Mostra/Nascondi password" onclick="togglePassword()">
                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="field">
            <label for="dataNascita">Data di nascita</label>
            <input type="date" id="dataNascita" name="dataNascita"
                value="<?= htmlspecialchars($_POST['dataNascita'] ?? '') ?>" required>
        </div>

        <hr class="divider">
        <p class="section-label">Indirizzo</p>

        <div class="field">
            <label for="provincia">Provincia</label>
            <select id="provincia" name="provincia" required>
                <option value="" disabled <?= empty($_POST['provincia']) ? 'selected' : '' ?>>Seleziona provincia</option>
                <?php
                $province = [
                    'AG' => 'Agrigento',    'AL' => 'Alessandria',  'AN' => 'Ancona',
                    'AO' => 'Aosta',        'AR' => 'Arezzo',       'AP' => 'Ascoli Piceno',
                    'AT' => 'Asti',         'AV' => 'Avellino',     'BA' => 'Bari',
                    'BT' => 'Barletta-Andria-Trani', 'BL' => 'Belluno', 'BN' => 'Benevento',
                    'BG' => 'Bergamo',      'BI' => 'Biella',       'BO' => 'Bologna',
                    'BZ' => 'Bolzano',      'BS' => 'Brescia',      'BR' => 'Brindisi',
                    'CA' => 'Cagliari',     'CL' => 'Caltanissetta','CB' => 'Campobasso',
                    'CI' => 'Carbonia-Iglesias', 'CE' => 'Caserta', 'CT' => 'Catania',
                    'CZ' => 'Catanzaro',    'CH' => 'Chieti',       'CO' => 'Como',
                    'CS' => 'Cosenza',      'CR' => 'Cremona',      'KR' => 'Crotone',
                    'CN' => 'Cuneo',        'EN' => 'Enna',         'FM' => 'Fermo',
                    'FE' => 'Ferrara',      'FI' => 'Firenze',      'FG' => 'Foggia',
                    'FC' => 'Forlì-Cesena', 'FR' => 'Frosinone',    'GE' => 'Genova',
                    'GO' => 'Gorizia',      'GR' => 'Grosseto',     'IM' => 'Imperia',
                    'IS' => 'Isernia',      'SP' => 'La Spezia',    'AQ' => 'L\'Aquila',
                    'LT' => 'Latina',       'LE' => 'Lecce',        'LC' => 'Lecco',
                    'LI' => 'Livorno',      'LO' => 'Lodi',         'LU' => 'Lucca',
                    'MC' => 'Macerata',     'MN' => 'Mantova',      'MS' => 'Massa-Carrara',
                    'MT' => 'Matera',       'VS' => 'Medio Campidano', 'ME' => 'Messina',
                    'MI' => 'Milano',       'MO' => 'Modena',       'MB' => 'Monza e Brianza',
                    'NA' => 'Napoli',       'NO' => 'Novara',       'NU' => 'Nuoro',
                    'OG' => 'Ogliastra',    'OT' => 'Olbia-Tempio', 'OR' => 'Oristano',
                    'PD' => 'Padova',       'PA' => 'Palermo',      'PR' => 'Parma',
                    'PV' => 'Pavia',        'PG' => 'Perugia',      'PU' => 'Pesaro e Urbino',
                    'PE' => 'Pescara',      'PC' => 'Piacenza',     'PI' => 'Pisa',
                    'PT' => 'Pistoia',      'PN' => 'Pordenone',    'PZ' => 'Potenza',
                    'PO' => 'Prato',        'RG' => 'Ragusa',       'RA' => 'Ravenna',
                    'RC' => 'Reggio Calabria', 'RE' => 'Reggio Emilia', 'RI' => 'Rieti',
                    'RN' => 'Rimini',       'RM' => 'Roma',         'RO' => 'Rovigo',
                    'SA' => 'Salerno',      'SS' => 'Sassari',      'SV' => 'Savona',
                    'SI' => 'Siena',        'SR' => 'Siracusa',     'SO' => 'Sondrio',
                    'TA' => 'Taranto',      'TE' => 'Teramo',       'TR' => 'Terni',
                    'TO' => 'Torino',       'OG' => 'Tortolì',      'TP' => 'Trapani',
                    'TN' => 'Trento',       'TV' => 'Treviso',      'TS' => 'Trieste',
                    'UD' => 'Udine',        'VA' => 'Varese',       'VE' => 'Venezia',
                    'VB' => 'Verbano-Cusio-Ossola', 'VC' => 'Vercelli', 'VR' => 'Verona',
                    'VV' => 'Vibo Valentia','VI' => 'Vicenza',      'VT' => 'Viterbo',
                ];
                foreach ($province as $sigla => $nome):
                    $sel = (($_POST['provincia'] ?? '') === $sigla) ? 'selected' : '';
                ?>
                    <option value="<?= $sigla ?>" <?= $sel ?>><?= $nome ?> (<?= $sigla ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="citta">Città</label>
            <input type="text" id="citta" name="citta" placeholder="Es. Milano"
                value="<?= htmlspecialchars($_POST['citta'] ?? '') ?>" required>
        </div>

        <div class="field-row">
            <div class="field field--small">
                <label for="cap">CAP</label>
                <input type="text" id="cap" name="cap" placeholder="20100" maxlength="5"
                    value="<?= htmlspecialchars($_POST['cap'] ?? '') ?>" required>
            </div>
            <div class="field field--grow">
                <label for="via">Via / Indirizzo</label>
                <input type="text" id="via" name="via" placeholder="Es. Via Roma 1"
                    value="<?= htmlspecialchars($_POST['via'] ?? '') ?>" required>
            </div>
        </div>

        <button type="submit" class="btn">Registrati</button>

    </form>

    <hr class="divider">

    <p class="footer">
        Hai già un account? <a href="loginForm.php">Accedi</a>
    </p>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordUt');
    const icon  = document.getElementById('eye-icon');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a10.05 10.05 0 0 1 2.38-3.96M6.53 6.53A9.96 9.96 0 0 1 12 4.5c5 0 9.27 3.11 11 7.5a10.05 10.05 0 0 1-4.15 5.12M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>'
        : '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
}
</script>

</body>
</html>