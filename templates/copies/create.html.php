<?php

declare(strict_types=1);

$old ??= [];
$error ??= null;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nouvelle copie — Notation universitaire</title>

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

            <a href="/copies" class="nav-item">
                <span class="nav-icon">▦</span>
                <span>Tableau de bord</span>
            </a>

            <a href="/copies/create" class="nav-item active">
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
                    NOUVEL ENREGISTREMENT
                </p>

                <h1>
                    Soumettre une copie
                </h1>

            </div>

        </header>


        <?php if ($error !== null): ?>

            <div class="alert alert-danger">

                <div class="alert-icon">
                    !
                </div>

                <div>
                    <strong>Impossible d'enregistrer la copie</strong>

                    <p>
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>

            </div>

        <?php endif; ?>


        <div class="form-layout">

            <!-- FORMULAIRE -->

            <section class="form-card">

                <div class="form-card-header">

                    <div class="form-card-icon">
                        ✎
                    </div>

                    <div>

                        <h2>
                            Informations de la copie
                        </h2>

                        <p>
                            Renseignez les informations ci-dessous.
                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    action="/copies"
                    class="modern-form"
                >

                    <!-- DATE DEPOT -->

                    <div class="form-group">

                        <label for="date_depot">
                            Date de dépôt
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                ◷
                            </span>

                            <input
                                type="datetime-local"
                                id="date_depot"
                                name="date_depot"
                                value="<?= htmlspecialchars(
                                    $old['date_depot'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                required
                            >

                        </div>

                        <small>
                            Date et heure auxquelles la copie a été déposée.
                        </small>

                    </div>


                    <!-- DATE LIMITE -->

                    <div class="form-group">

                        <label for="date_limite">
                            Date limite
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                ◷
                            </span>

                            <input
                                type="datetime-local"
                                id="date_limite"
                                name="date_limite"
                                value="<?= htmlspecialchars(
                                    $old['date_limite'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                required
                            >

                        </div>

                        <small>
                            Date et heure maximale autorisées.
                        </small>

                    </div>


                    <!-- NOTE -->

                    <div class="form-group">

                        <label for="note_brute">
                            Note brute
                        </label>

                        <div class="input-wrapper score-input">

                            <span class="input-icon">
                                ★
                            </span>

                            <input
                                type="number"
                                id="note_brute"
                                name="note_brute"
                                min="0"
                                max="20"
                                step="0.01"
                                placeholder="Ex. 15.50"
                                value="<?= htmlspecialchars(
                                    $old['note_brute'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                required
                            >

                            <span class="input-suffix">
                                /20
                            </span>

                        </div>

                        <small>
                            La note obtenue avant l'application d'une éventuelle pénalité.
                        </small>

                    </div>


                    <!-- INFO PENALITE -->

                    <div class="info-box">

                        <div class="info-box-icon">
                            ⓘ
                        </div>

                        <div>

                            <strong>
                                Calcul automatique
                            </strong>

                            <p>
                                Si la copie est déposée après la date limite,
                                une pénalité de <strong>2 points</strong>
                                sera automatiquement appliquée.
                            </p>

                        </div>

                    </div>


                    <!-- BUTTONS -->

                    <div class="form-actions">

                        <a
                            href="/copies"
                            class="btn btn-secondary"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary btn-submit"
                        >
                            <span>✓</span>
                            Enregistrer la copie
                        </button>

                    </div>

                </form>

            </section>


            <!-- SIDE INFO -->

            <aside class="form-aside">

                <div class="preview-card">

                    <div class="preview-top">

                        <span class="preview-label">
                            APERÇU
                        </span>

                        <span class="preview-status">
                            Nouveau
                        </span>

                    </div>

                    <div class="preview-score">
                        <span>NOTE</span>
                        <strong>--</strong>
                        <small>/20</small>
                    </div>

                    <div class="preview-divider"></div>

                    <div class="preview-row">

                        <span>Date de dépôt</span>
                        <strong>--</strong>

                    </div>

                    <div class="preview-row">

                        <span>Date limite</span>
                        <strong>--</strong>

                    </div>

                </div>


                <div class="help-card">

                    <div class="help-icon">
                        ?
                    </div>

                    <h3>
                        Comment ça marche ?
                    </h3>

                    <p>
                        Le système calcule automatiquement la note finale
                        en fonction de la date de dépôt et de la date limite.
                    </p>

                    <div class="help-step">
                        <span>1</span>
                        <p>Vous renseignez la note brute.</p>
                    </div>

                    <div class="help-step">
                        <span>2</span>
                        <p>Le système vérifie le retard.</p>
                    </div>

                    <div class="help-step">
                        <span>3</span>
                        <p>La note finale est enregistrée.</p>
                    </div>

                </div>

            </aside>

        </div>


        <footer class="page-footer">
            <span>Notation Universitaire</span>
            <span>•</span>
            <span>Soumission sécurisée</span>
        </footer>

    </main>

</div>

</body>

</html>