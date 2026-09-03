<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copie #<?= (int) $copie->getId() ?></title>
</head>
<body>
    <h1>Copie #<?= (int) $copie->getId() ?></h1>

    <dl>
        <dt>Date de dépôt</dt>
        <dd><?= htmlspecialchars($copie->getDateDepot()->format('d/m/Y H:i:s'), ENT_QUOTES, 'UTF-8') ?></dd>

        <dt>Date limite</dt>
        <dd><?= htmlspecialchars($copie->getDateLimite()->format('d/m/Y H:i:s'), ENT_QUOTES, 'UTF-8') ?></dd>

        <dt>Note brute</dt>
        <dd><?= htmlspecialchars((string) $copie->getNoteBrute(), ENT_QUOTES, 'UTF-8') ?>/20</dd>

        <dt>Note finale</dt>
        <dd><?= htmlspecialchars((string) $copie->getNoteFinale(), ENT_QUOTES, 'UTF-8') ?>/20</dd>

        <dt>Pénalité appliquée</dt>
        <dd><?= $copie->getPenaliteAppliquee() ? 'Oui (-2 points)' : 'Non' ?></dd>
    </dl>

    <p><a href="/copies">Retour à la liste</a></p>
</body>
</html>
