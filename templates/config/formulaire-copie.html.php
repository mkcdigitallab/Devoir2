<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une Copie d'Examen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input[type="number"] {
            font-size: 16px;
        }

        input[type="datetime-local"] {
            font-size: 16px;
        }

        .input-hint {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        button {
            flex: 1;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-reset {
            background: #f0f0f0;
            color: #333;
        }

        .btn-reset:hover {
            background: #e0e0e0;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.error {
            background: #ffebee;
            border-left: 4px solid #f44336;
            color: #c62828;
        }

        .alert.success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }

        .info-box {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>📝 Soumettre une Copie d'Examen</h1>
        <p class="subtitle">Remplissez le formulaire pour enregistrer votre copie</p>

        <div class="info-box">
            <strong>ℹ️ Note:</strong> Les données du formulaire seront converties et validées via le DTO SoumettreCopieDTO
        </div>

        <div id="alertContainer"></div>

        <form id="copyForm" method="POST" action="">
            <div class="form-group">
                <label for="noteBrute">Note Brute (0-20)</label>
                <input
                    type="number"
                    id="noteBrute"
                    name="note_brute"
                    min="0"
                    max="20"
                    step="0.5"
                    placeholder="ex: 15.5"
                    required>
                <div class="input-hint">Entrez une note entre 0 et 20</div>
            </div>

            <div class="form-group">
                <label for="dateDepot">Date de Dépôt</label>
                <input
                    type="datetime-local"
                    id="dateDepot"
                    name="date_depot"
                    required>
                <div class="input-hint">Format: YYYY-MM-DD HH:mm</div>
            </div>

            <div class="form-group">
                <label for="dateLimite">Date Limite</label>
                <input
                    type="datetime-local"
                    id="dateLimite"
                    name="date_limite"
                    required>
                <div class="input-hint">Format: YYYY-MM-DD HH:mm</div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">Soumettre</button>
                <button type="reset" class="btn-reset">Réinitialiser</button>
            </div>
        </form>
    </div>

    <script>
        // Pré-remplir les dates avec les valeurs actuelles
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const limit = new Date(now);
            limit.setDate(limit.getDate() + 7); // +7 jours

            // Formater les dates
            const formatDateTime = (date) => {
                return date.toISOString().slice(0, 16);
            };

            document.getElementById('dateDepot').value = formatDateTime(now);
            document.getElementById('dateLimite').value = formatDateTime(limit);
        });

        // Gestion du formulaire
        document.getElementById('copyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Afficher les données dans la console (pour debug)
            console.log('Données du formulaire:', data);

            // Ici, vous pouvez envoyer les données via AJAX
            // fetch('...', { method: 'POST', body: formData })
        });

        // Fonction pour afficher les alertes
        function showAlert(message, type = 'error') {
            const container = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            alert.textContent = message;
            alert.style.display = 'block';
            container.innerHTML = '';
            container.appendChild(alert);
        }
    </script>
</body>

</html>