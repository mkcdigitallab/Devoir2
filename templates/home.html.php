<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
        }
        h1 {
            color: #333;
            margin: 0 0 20px 0;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .status {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .status li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>L\'application est correctement configurée et le point d\'entrée fonctionne!</p>
        
        <div class="status">
            <strong>État de l\'application:</strong>
            <ul>
                <li>✓ Autoloader PSR-4 chargé</li>
                <li>✓ Configuration chargée</li>
                <li>✓ Architecture en place</li>
            </ul>
        </div>
        
        <p style="color: #999; font-size: 14px;">Debug mode</p>
    </div>
</body>
</html>