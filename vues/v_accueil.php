<?php
if (!isset($_SESSION['id'])) {
    header('Location: index.php?uc=connexion');
    exit();
}

require_once 'include/m_produits.php';
require_once 'include/m_statistiques.php';
require_once 'include/m_visioconferences.php';

$role = $_SESSION['role'] ?? null;

$pdoProduit = PdoGsbProduit::getPdoGsbProduit();
$produits = new Produits($pdoProduit);
$allProduits = $produits->consulterProduits();

$pdoStat = PdoGsbStat::getPdoGsbStat();
$statistiques = new Statistiques($pdoStat);
$allStats = $statistiques->getStatistiquesOperations();

$pdoVisio = PdoGsbVisio::getPdoGsbVisio();
$visioconferences = new Visioconferences($pdoVisio);
$allVisios = $visioconferences->consulterVisioconferences();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil GSB</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --bg-light: #ecf0f1;
            --text-dark: #2c3e50;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 10px;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow);
        }

        header h1 {
            color: white;
            font-size: 2.5rem;
        }

        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .container {
            background: white;
            padding: 2rem;
            margin: 1.5rem 0;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .stats-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            margin: 2rem auto;
            max-width: 900px;
        }

        .stat-item {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: var(--radius);
            text-align: center;
            transition: transform 0.3s ease;
            flex: 0 1 250px;
            min-width: 250px;
            box-shadow: var(--shadow);
        }

        .stat-item strong {
            font-size: 1.5rem;
            color: var(--secondary-color);
            display: block;
            margin: 0.5rem 0;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            margin: 2rem 0;
            box-shadow: var(--shadow);
        }

        h1, h2, h3 {
            color: var(--primary-color);
            margin: 0 0 1rem 0;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
            border-bottom: 3px solid var(--secondary-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .welcome-message {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur l'extranet GSB</h1>
    </header>
    
    <main>
        <div class="welcome-message">
            <h2>GSB Extranet</h2>
            <p>Votre portail de gestion des services pharmaceutiques</p>
        </div>

        <section class="container">
            <h2>À propos de GSB</h2>
            <p>Leader dans l'innovation pharmaceutique, GSB s'engage à fournir des solutions de santé de haute qualité.</p>
        </section>

        <section class="container">
            <h2>Statistiques</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>Produits</h3>
                    <p><strong><?= count($allProduits) ?></strong> produits disponibles</p>
                </div>
                <div class="stat-item">
                    <h3>Visites</h3>
                    <p><strong><?= count($allStats) ?></strong> visites mensuelles</p>
                </div>
                <div class="stat-item">
                    <h3>Visioconférences</h3>
                    <p><strong><?= count($allVisios) ?></strong> sessions organisées</p>
                </div>
            </div>
        </section>

        <section class="container">
            <h2>Analyse des données</h2>
            <div class="chart-container">
                <canvas id="combinedChart"></canvas>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> GSB - Tous droits réservés</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxCombined = document.getElementById('combinedChart').getContext('2d');
            var combinedChart = new Chart(ctxCombined, {
                type: 'bar',
                data: {
                    labels: ['Produits', 'Visioconférences'],
                    datasets: [{
                        label: 'Statistiques GSB',
                        data: [<?= count($allProduits) ?>, <?= count($allVisios) ?>],
                        backgroundColor: ['#3498db', '#2ecc71'],
                        borderColor: ['#2980b9', '#27ae60'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Vue d\'ensemble des activités'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>