<?php
require_once 'include/class.pdogsb.inc.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$pdo = PdoGsb::getPdoGsb();
$logs = $pdo->getLogsOperations();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Admin - Logs</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            padding: 20px;
        }
        h1 {
            color: #343a40; /* Changed from blue to black-gray */
            margin-bottom: 20px;
            text-align: center;
        }
        .table {
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
            margin-bottom: 40px;
        }
        .table th {
            background-color: #343a40; /* Changed from blue to black-gray */
            color: white;
            text-align: center;
            padding: 10px;
        }
        .table td {
            text-align: center;
            padding: 10px;
        }
        .btn {
            background-color: #343a40; /* Black color */
            color: white;
            border-radius: 0; /* Square corners */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
        }
        .btn:hover {
            background-color: #23272b; /* Darker black on hover */
        }
        .chart-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <h1>Logs des opérations</h1>
    <table id="tableLogs" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Utilisateur</th>
                <th>Adresse IP</th>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['id']) ?></td>
                    <td><?= htmlspecialchars($log['idutilisateur']) ?></td>
                    <td><?= htmlspecialchars($log['adresse_ip']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="chart-container">
        <canvas id="logsChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="ipChart"></canvas>
    </div>

    <script>
    $(document).ready(function() {
        $('#tableLogs').DataTable({
            "language": {
                "sSearch": "Recherche:",
                "lengthMenu": "Afficher _MENU_ éléments",
                "zeroRecords": "Aucun résultat trouvé",
                "info": "Affichage de _START_ à _END_ de _TOTAL_ éléments",
                "infoEmpty": "Aucun élément à afficher",
                "infoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                }
            }
        });

        // Prepare data for the action chart
        var logsData = <?php echo json_encode($logs); ?>;
        var actions = logsData.map(log => log.action);
        var actionCounts = actions.reduce((acc, action) => {
            acc[action] = (acc[action] || 0) + 1;
            return acc;
        }, {});

        var ctx = document.getElementById('logsChart').getContext('2d');
        var logsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(actionCounts),
                datasets: [{
                    label: 'Nombre d\'actions',
                    data: Object.values(actionCounts),
                    backgroundColor: 'rgba(52, 58, 64, 0.2)',
                    borderColor: 'rgba(52, 58, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Prepare data for the IP chart
        var ips = logsData.map(log => log.adresse_ip);
        var ipCounts = ips.reduce((acc, ip) => {
            acc[ip] = (acc[ip] || 0) + 1;
            return acc;
        }, {});

        var ctxIp = document.getElementById('ipChart').getContext('2d');
        var ipChart = new Chart(ctxIp, {
            type: 'bar',
            data: {
                labels: Object.keys(ipCounts),
                datasets: [{
                    label: 'Nombre d\'actions par IP',
                    data: Object.values(ipCounts),
                    backgroundColor: 'rgba(52, 58, 64, 0.2)',
                    borderColor: 'rgba(52, 58, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
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
