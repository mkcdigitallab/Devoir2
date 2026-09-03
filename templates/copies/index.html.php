<?php

declare(strict_types=1);

/** @var \App\Entity\CopieExamen[] $copies */

$totalCopies = count($copies);
$totalRetards = 0;
$totalNotes = 0.0;

foreach ($copies as $copie) {
    if ($copie->getPenaliteAppliquee()) {
        $totalRetards++;
    }

    $totalNotes += $copie->getNoteFinale();
}

$moyenne = $totalCopies > 0 ? $totalNotes / $totalCopies : 0;
$tauxRetard = $totalCopies > 0 ? ($totalRetards / $totalCopies) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Copies d'examen — Notation universitaire</title>

    <link rel="stylesheet" href="/assets/css/app.css">
</head>

<body>

<div class="app-shell">

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">NU</div>

            <div>
                <strong>Notation</strong>
                <span>Universitaire</span>
            </div>
        </div>

        <nav class="sidebar-nav">

            <a href="/copies" class="nav-item active">
                <span class="nav-icon">▦</span>
                <span>Tableau de bord</span>
            </a>

            <a href="/copies/create" class="nav-item">
                <span class="nav-icon">＋</span>
                <span>Nouvelle copie</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            <div class="status-dot"></div>

            <div>
                <strong>Système opérationnel</strong>
                <span>PostgreSQL connecté</span>
            </div>
        </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <main class="main-content">

        <!-- TOPBAR -->

        <header class="topbar">

            <div>
                <p class="eyebrow">ESPACE ADMINISTRATION</p>

                <h1>
                    Tableau de bord
                </h1>
            </div>

            <a href="/copies/create" class="btn btn-primary">
                <span>＋</span>
                Nouvelle copie
            </a>

        </header>


        <!-- HERO -->

        <section class="hero-card">

            <div class="hero-content">

                <div class="hero-badge">
                    <span></span>
                    Gestion des examens
                </div>

                <h2>
                    Gérez vos copies<br>
                    <strong>simplement.</strong>
                </h2>

                <p>
                    Consultez les résultats, contrôlez les retards
                    et suivez les notes de vos copies d'examen.
                </p>

            </div>

            <div class="hero-decoration">
                <div class="hero-circle circle-one"></div>
                <div class="hero-circle circle-two"></div>
                <div class="hero-number">
                    <?= $totalCopies ?>
                </div>
            </div>

        </section>


        <!-- ================= STATISTICS ================= -->

        <section class="stats-grid">

            <article class="stat-card">

                <div class="stat-icon orange">
                    ▤
                </div>

                <div class="stat-info">
                    <span>Total des copies</span>
                    <strong><?= $totalCopies ?></strong>
                </div>

                <div class="stat-trend">
                    + actif
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon blue">
                    ★
                </div>

                <div class="stat-info">
                    <span>Note moyenne</span>
                    <strong><?= number_format($moyenne, 2, ',', ' ') ?>/20</strong>
                </div>

                <div class="stat-trend">
                    moyenne
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon red">
                    !
                </div>

                <div class="stat-info">
                    <span>Copies en retard</span>
                    <strong><?= $totalRetards ?></strong>
                </div>

                <div class="stat-trend">
                    <?= number_format($tauxRetard, 0) ?> %
                </div>

            </article>


            <article class="stat-card">

                <div class="stat-icon green">
                    ✓
                </div>

                <div class="stat-info">
                    <span>À temps</span>
                    <strong><?= $totalCopies - $totalRetards ?></strong>
                </div>

                <div class="stat-trend success">
                    conforme
                </div>

            </article>

        </section>


        <!-- ================= LIST ================= -->

        <section class="content-card">

            <div class="section-header">

                <div>
                    <p class="eyebrow">ARCHIVES</p>

                    <h2>
                        Copies d'examen
                    </h2>

                    <p class="section-description">
                        Historique des copies enregistrées dans le système.
                    </p>
                </div>

                <div class="copy-count">
                    <?= $totalCopies ?> copie<?= $totalCopies > 1 ? 's' : '' ?>
                </div>

            </div>


            <?php if (empty($copies)): ?>

                <div class="empty-state">

                    <div class="empty-icon">
                        ▤
                    </div>

                    <h3>Aucune copie enregistrée</h3>

                    <p>
                        Commencez par enregistrer une nouvelle copie d'examen.
                    </p>

                    <a href="/copies/create" class="btn btn-primary">
                        Ajouter une copie
                    </a>

                </div>

            <?php else: ?>

                <div class="table-wrapper">

                    <table class="data-table">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date de dépôt</th>
                            <th>Note brute</th>
                            <th>Note finale</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($copies as $copie): ?>

                            <tr>

                                <td>
                                    <span class="copy-id">
                                        #<?= $copie->getId() ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="date-cell">
                                        <strong>
                                            <?= $copie->getDateDepot()->format('d/m/Y') ?>
                                        </strong>

                                        <span>
                                            <?= $copie->getDateDepot()->format('H:i') ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="score">
                                        <?= number_format(
                                            $copie->getNoteBrute(),
                                            2,
                                            ',',
                                            ' '
                                        ) ?>
                                    </span>
                                    <small>/20</small>
                                </td>

                                <td>

                                    <div class="final-score">

                                        <strong>
                                            <?= number_format(
                                                $copie->getNoteFinale(),
                                                2,
                                                ',',
                                                ' '
                                            ) ?>
                                        </strong>

                                        <div class="score-bar">
                                            <span
                                                style="width: <?= ($copie->getNoteFinale() / 20) * 100 ?>%"
                                            ></span>
                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?php if ($copie->getPenaliteAppliquee()): ?>

                                        <span class="status status-danger">
                                            <span></span>
                                            En retard
                                        </span>

                                    <?php else: ?>

                                        <span class="status status-success">
                                            <span></span>
                                            À temps
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="actions">

                                    <a
                                        href="/copies/<?= $copie->getId() ?>"
                                        class="btn-icon"
                                        title="Voir la copie"
                                    >
                                        →
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>


        <footer class="page-footer">
            <span>Notation Universitaire</span>
            <span>•</span>
            <span>Gestion des copies d'examen</span>
        </footer>

    </main>

</div>

</body>
</html>