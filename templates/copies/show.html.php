<?php

declare(strict_types=1);

/** @var \App\Entity\CopieExamen|null $copie */

if ($copie === null) {
    http_response_code(404);
    exit;
}

$note = $copie->getNoteFinale();
$percentage = ($note / 20) * 100;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Copie #<?= $copie->getId() ?> — Notation universitaire
    </title>

    <link rel="stylesheet" href="/assets/css/app.css">

</head>

<body>

<div class="app-shell">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="brand">

            <div class="brand-logo">
                NU
            </div>

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


    <!-- MAIN -->

    <main class="main-content">

        <header class="topbar">

            <div>

                <a href="/copies" class="back-link">
                    ← Retour aux copies
                </a>

                <p class="eyebrow">
                    DÉTAIL DE LA COPIE
                </p>

                <h1>
                    Copie #<?= $copie->getId() ?>
                </h1>

            </div>

            <a
                href="/copies/create"
                class="btn btn-primary"
            >
                ＋ Nouvelle copie
            </a>

        </header>


        <!-- RESULT HERO -->

        <section class="result-hero">

            <div class="result-left">

                <span class="result-label">
                    NOTE FINALE
                </span>

                <div class="big-score">

                    <strong>
                        <?= number_format(
                            $note,
                            2,
                            ',',
                            ' '
                        ) ?>
                    </strong>

                    <span>/20</span>

                </div>


                <div class="score-progress">

                    <div
                        class="score-progress-value"
                        style="width: <?= $percentage ?>%"
                    ></div>

                </div>


                <?php if ($copie->getPenaliteAppliquee()): ?>

                    <span class="status status-danger large-status">
                        <span></span>
                        Pénalité de retard appliquée
                    </span>

                <?php else: ?>

                    <span class="status status-success large-status">
                        <span></span>
                        Copie déposée à temps
                    </span>

                <?php endif; ?>

            </div>


            <div class="result-visual">

                <div
                    class="score-ring"
                    style="--score: <?= $percentage ?>%;"
                >

                    <div>
                        <strong>
                            <?= round($percentage) ?>%
                        </strong>

                        <span>du barème</span>
                    </div>

                </div>

            </div>

        </section>


        <!-- DETAILS -->

        <section class="details-grid">


            <!-- DATE -->

            <article class="detail-card">

                <div class="detail-icon orange">
                    ◷
                </div>

                <div>

                    <span>
                        Date de dépôt
                    </span>

                    <strong>
                        <?= $copie->getDateDepot()->format('d/m/Y') ?>
                    </strong>

                    <small>
                        à <?= $copie->getDateDepot()->format('H:i:s') ?>
                    </small>

                </div>

            </article>


            <!-- LIMITE -->

            <article class="detail-card">

                <div class="detail-icon blue">
                    ◷
                </div>

                <div>

                    <span>
                        Date limite
                    </span>

                    <strong>
                        <?= $copie->getDateLimite()->format('d/m/Y') ?>
                    </strong>

                    <small>
                        à <?= $copie->getDateLimite()->format('H:i:s') ?>
                    </small>

                </div>

            </article>


            <!-- NOTE BRUTE -->

            <article class="detail-card">

                <div class="detail-icon purple">
                    ★
                </div>

                <div>

                    <span>
                        Note brute
                    </span>

                    <strong>
                        <?= number_format(
                            $copie->getNoteBrute(),
                            2,
                            ',',
                            ' '
                        ) ?>/20
                    </strong>

                    <small>
                        avant pénalité
                    </small>

                </div>

            </article>


            <!-- PENALITE -->

            <article class="detail-card">

                <div class="detail-icon red">
                    !
                </div>

                <div>

                    <span>
                        Pénalité
                    </span>

                    <?php if ($copie->getPenaliteAppliquee()): ?>

                        <strong class="text-danger">
                            -2,00 points
                        </strong>

                        <small>
                            Retard détecté
                        </small>

                    <?php else: ?>

                        <strong class="text-success">
                            Aucune
                        </strong>

                        <small>
                            Aucun retard
                        </small>

                    <?php endif; ?>

                </div>

            </article>

        </section>


        <!-- CALCULATION -->

        <section class="content-card calculation-card">

            <div class="section-header">

                <div>

                    <p class="eyebrow">
                        CALCUL
                    </p>

                    <h2>
                        Détail de la notation
                    </h2>

                </div>

            </div>


            <div class="calculation">

                <div class="calculation-step">

                    <span class="calc-number">
                        01
                    </span>

                    <div>

                        <span>
                            Note brute
                        </span>

                        <strong>
                            <?= number_format(
                                $copie->getNoteBrute(),
                                2,
                                ',',
                                ' '
                            ) ?>/20
                        </strong>

                    </div>

                </div>


                <div class="calculation-operation">
                    −
                </div>


                <div class="calculation-step">

                    <span class="calc-number">
                        02
                    </span>

                    <div>

                        <span>
                            Pénalité
                        </span>

                        <strong>
                            <?= $copie->getPenaliteAppliquee()
                                ? '2,00 points'
                                : '0,00 point'
                            ?>
                        </strong>

                    </div>

                </div>


                <div class="calculation-operation">
                    =
                </div>


                <div class="calculation-result">

                    <span>
                        NOTE FINALE
                    </span>

                    <strong>
                        <?= number_format(
                            $note,
                            2,
                            ',',
                            ' '
                        ) ?>/20
                    </strong>

                </div>

            </div>

        </section>


        <footer class="page-footer">
            <span>Notation Universitaire</span>
            <span>•</span>
            <span>Copie #<?= $copie->getId() ?></span>
        </footer>

    </main>

</div>

</body>

</html>