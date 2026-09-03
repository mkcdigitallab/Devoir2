<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une copie</title>
</head>
<body>
    <h1>Soumettre une copie d'examen</h1>

    <?php if (!empty($error)): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="/copies">
        <div>
            <label for="noteBrute">Note brute / 20</label>
            <input id="noteBrute" name="noteBrute" type="number" min="0" max="20" step="0.01"
                   value="<?= htmlspecialchars($old['noteBrute'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div>
            <label for="dateDepot">Date de dépôt</label>
            <input id="dateDepot" name="dateDepot" type="datetime-local"
                   value="<?= htmlspecialchars($old['dateDepot'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div>
            <label for="dateLimite">Date limite</label>
            <input id="dateLimite" name="dateLimite" type="datetime-local"
                   value="<?= htmlspecialchars($old['dateLimite'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <button type="submit">Soumettre</button>
    </form>

    <p><a href="/copies">Voir les copies</a></p>
</body>
</html>
