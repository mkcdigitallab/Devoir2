<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable</title>
</head>
<body>
    <h1>404</h1>
    <p><?= htmlspecialchars($message ?? 'Ressource introuvable.', ENT_QUOTES, 'UTF-8') ?></p>
    <p><a href="/copies">Retour aux copies</a></p>
</body>
</html>
