<?php require_once '../include/login.php'; ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/logReg.css">
</head>
<body>

<div class="card">

    <div class="brand">
        <div class="brand-icon">
            <a href="../Pagine/hPage.php">
                <img src="../Img/Loghi/Logo.png" alt="alt">
            </a>
            
        </div>
        <h1>Bentornato</h1>
        <p class="subtitle">Accedi al tuo account</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;margin-top:1px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <div class="field">
            <label for="mail">Email</label>
            <input
                type="email"
                id="mail"
                name="mail"
                placeholder="nome@esempio.it"
                value="<?= htmlspecialchars($_POST['mail'] ?? '') ?>"
                autocomplete="email"
                required
            >
        </div>  

        <div class="field">
            <label for="passwordUt">Password</label>
            <div class="password-wrap">
                <input
                    type="password"
                    id="passwordUt"
                    name="passwordUt"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="toggle-pw" aria-label="Mostra/Nascondi password" onclick="togglePassword()">
                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn">Accedi</button>

    </form>

    <hr class="divider">

    <p class="footer">
        Non hai un account? <a href="register.php">Registrati</a>
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