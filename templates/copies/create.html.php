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
            <label for="note_brute">Note brute / 20</label>
            <input id="note_brute" name="note_brute" type="number" min="0" max="20" step="0.01"
                   value="<?= htmlspecialchars($old['note_brute'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div>
            <label for="date_depot">Date de dépôt</label>
            <input id="date_depot" name="date_depot" type="datetime-local"
                   value="<?= htmlspecialchars($old['date_depot'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div>
            <label for="date_limite">Date limite</label>
            <input id="date_limite" name="date_limite" type="datetime-local"
                   value="<?= htmlspecialchars($old['date_limite'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <button type="submit">Soumettre</button>
    </form>

    <p><a href="/copies">Voir les copies</a></p>
</body>
</html>
