<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copies d'examen</title>
</head>
<body>
    <h1>Copies d'examen</h1>

    <p><a href="/copies/create">Soumettre une nouvelle copie</a></p>

    <?php if (empty($copies)): ?>
        <p>Aucune copie enregistrée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dépôt</th>
                    <th>Note brute</th>
                    <th>Note finale</th>
                    <th>Pénalité</th>
                    <th>Détail</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($copies as $copie): ?>
                <tr>
                    <td><?= (int) $copie->getId() ?></td>
                    <td><?= htmlspecialchars($copie->getDateDepot()->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $copie->getNoteBrute(), ENT_QUOTES, 'UTF-8') ?>/20</td>
                    <td><?= htmlspecialchars((string) $copie->getNoteFinale(), ENT_QUOTES, 'UTF-8') ?>/20</td>
                    <td><?= $copie->getPenaliteAppliquee() ? 'Oui' : 'Non' ?></td>
                    <td><a href="/copies/<?= (int) $copie->getId() ?>">Voir</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
