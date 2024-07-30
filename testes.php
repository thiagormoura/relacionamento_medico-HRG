<?php
include("conexao.php");
$registrosPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $registrosPorPagina;
$sql = "SELECT DATE_FORMAT(a.data, '%m/%Y') as data,
        im.nome as nome_profissional,
        assuntos.assunto,
        a.situacao as situacao,
        a.id as id
        FROM relacionamentomedico.atendimento AS a
        JOIN relacionamentomedico.profissionais AS im ON a.profissional = im.id
        JOIN relacionamentomedico.atendimento_has_assunto AS has ON a.id = has.id
        JOIN relacionamentomedico.assunto AS assuntos ON has.id = assuntos.id
        LIMIT $offset, $registrosPorPagina";

$result = $conn->query($sql);
$sqlTotal = "SELECT COUNT(*) AS total
            FROM relacionamentomedico.atendimento AS a
            JOIN relacionamentomedico.profissionais AS im ON a.profissional = im.id
            JOIN relacionamentomedico.atendimento_has_assunto AS has ON a.id = has.id
            JOIN relacionamentomedico.assunto AS assuntos ON has.id = assuntos.id";

$resultCount = $conn->query($sqlTotal);
$totalRegistros = $resultCount->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$sql = "
    SELECT 
        DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        COUNT(*) AS quantidade
    FROM 
        atendimento a
    GROUP BY 
        DATE_FORMAT(a.data, '%m/%Y')
    ORDER BY 
        mes_ano;
";
$result = $conn->query($sql);
$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['mes_ano'];
    $data[] = $row['quantidade'];
}
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);
$sqlStatus = "
    SELECT 
        situacao,
        COUNT(*) AS quantidade
    FROM 
        atendimento
    GROUP BY 
        situacao;
";
$resultStatus = $conn->query($sqlStatus);
$statusLabels = [];
$statusData = [];
while ($row = $resultStatus->fetch_assoc()) {
    $statusLabels[] = $row['situacao'];
    $statusData[] = $row['quantidade'];
}
$statusLabelsJson = json_encode($statusLabels);
$statusDataJson = json_encode($statusData);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Relacionamento Médico</title>
    <link rel="icon" href="img/Logobordab.png" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">

    <style>
        .chart-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }

        .chart-stats p {
            margin: 0 15px;
        }

        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .chart-container-hidden {
            display: none;
        }

        .chart-box {
            width: 80%;
            max-width: 800px;
            height: 350px;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-box canvas {
            width: 100%;
            height: 100%;
        }

        .chart-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .button-group {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .button-group button {
            background-color: #1E3050;
            color: white;
            border: 2px solid #1E3050;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .button-group button:hover {
            background-color: white;
            color: #1E3050;
            border-color: #1E3050;
        }
    </style>
</head>

<body>
<div class="container mt-2">
    <div class="row mt-4 center-content">
        <div class="col-xl-12 col-md-12 col-lg-12 mb-4 mb-0">
            <div class="card border-left-success shadow h-80 py-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="chartSelector">Selecionar Gráfico:</label>
                        <select id="chartSelector" class="form-control" onchange="showChart(this.value)">
                            <option value="barChartContainer">Gráfico de Barras</option>
                            <option value="doughnutChartContainer">Gráfico de Rosca</option>
                            <option value="lineChartContainer">Gráfico de Linhas</option>
                            <option value="radarChartContainer">Gráfico de Radar</option>
                            <option value="polarAreaChartContainer">Gráfico de Área Polar</option>
                            <option value="bubbleChartContainer">Gráfico de Bolhas</option>
                            <option value="waterfallChartContainer">Gráfico de Cascata</option>
                        </select>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-12">
                            <div class="chart-header text-xs font-weight-bold text-success text-uppercase">
                                Quantidade de Atendimentos
                            </div>
                            <div id="barChartContainer" class="chart-container">
                                <div class="chart-box">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                            <div id="doughnutChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="doughnutChart"></canvas>
                                </div>
                                <div id="doughnutChartStats" class="chart-stats"></div>
                            </div>
                            <div id="lineChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>
                            <div id="radarChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="radarChart"></canvas>
                                </div>
                            </div>
                            <div id="polarAreaChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="polarAreaChart"></canvas>
                                </div>
                            </div>
                            <div id="bubbleChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="bubbleChart"></canvas>
                                </div>
                            </div>
                            <div id="waterfallChartContainer" class="chart-container chart-container-hidden">
                                <div class="chart-box">
                                    <canvas id="waterfallChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    function showChart(chartId) {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            if (container.id === chartId) {
                container.classList.remove('chart-container-hidden');
            } else {
                container.classList.add('chart-container-hidden');
            }
        });
    }
    showChart('barChartContainer');
});

</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const labels = <?php echo $labelsJson; ?>;
            const data = <?php echo $dataJson; ?>;
            const statusLabels = <?php echo $statusLabelsJson; ?>;
            const statusData = <?php echo $statusDataJson; ?>;

            const barChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: '#1E3050',
                    borderColor: '#1E3050',
                    borderWidth: 1,
                    data: data,
                }]
            };

            const lineChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: 'rgba(30, 48, 80, 0.2)',
                    borderColor: '#1E3050',
                    borderWidth: 2,
                    data: data,
                }]
            };

            const radarChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: 'rgba(30, 48, 80, 0.2)',
                    borderColor: '#1E3050',
                    borderWidth: 2,
                    data: data,
                }]
            };

            const polarAreaChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: [
                        'rgba(30, 48, 80, 0.2)',
                        'rgba(30, 48, 80, 0.4)',
                        'rgba(30, 48, 80, 0.6)',
                        'rgba(30, 48, 80, 0.8)',
                        'rgba(30, 48, 80, 1)'
                    ],
                    borderColor: '#1E3050',
                    borderWidth: 2,
                    data: data,
                }]
            };

            const bubbleChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: 'rgba(30, 48, 80, 0.5)',
                    borderColor: '#1E3050',
                    borderWidth: 1,
                    data: [
                        { x: 10, y: 20, r: 15 },
                        { x: 15, y: 10, r: 10 },
                        { x: 20, y: 30, r: 20 }
                    ],
                }]
            };

            const waterfallChartData = {
                labels: labels,
                datasets: [{
                    label: 'Quantidade de Atendimentos',
                    backgroundColor: '#1E3050',
                    borderColor: '#1E3050',
                    borderWidth: 1,
                    data: data.map((value, index) => ({
                        x: labels[index],
                        y: value
                    })),
                }]
            };

            const barCtx = document.getElementById('barChart').getContext('2d');
            const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const radarCtx = document.getElementById('radarChart').getContext('2d');
            const polarAreaCtx = document.getElementById('polarAreaChart').getContext('2d');
            const bubbleCtx = document.getElementById('bubbleChart').getContext('2d');
            const waterfallCtx = document.getElementById('waterfallChart').getContext('2d');

            new Chart(barCtx, {
                type: 'bar',
                data: barChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: 'Status dos Atendimentos',
                        backgroundColor: [
                            '#114F1C',
                            '#1E3050'
                        ],
                        borderColor: [
                            '#114F1C',
                            '#1E3050'
                        ],
                        borderWidth: 1,
                        data: statusData,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.raw !== null) {
                                        label += context.raw;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            new Chart(lineCtx, {
                type: 'line',
                data: lineChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(radarCtx, {
                type: 'radar',
                data: radarChartData,
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            new Chart(polarAreaCtx, {
                type: 'polarArea',
                data: polarAreaChartData,
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(bubbleCtx, {
                type: 'bubble',
                data: bubbleChartData,
                options: {
                    responsive: true,
                    scales: {
                        x: { beginAtZero: true },
                        y: { beginAtZero: true }
                    }
                }
            });

            new Chart(waterfallCtx, {
                type: 'bar',
                data: waterfallChartData,
                options: {
                    responsive: true,
                    scales: {
                        x: { beginAtZero: true },
                        y: { beginAtZero: true }
                    }
                }
            });

            const doughnutChartStats = document.getElementById('doughnutChartStats');
            doughnutChartStats.innerHTML = `
                <p style="display: inline-block; margin-right: 20px;">Abertos: ${statusData[0]}</p>
                <p style="display: inline-block;">Finalizados: ${statusData[1]}</p>
            `;
        });

        function showChart(chartId) {
            const chartContainers = document.querySelectorAll('.chart-container');
            chartContainers.forEach(container => {
                if (container.id === chartId) {
                    container.classList.remove('chart-container-hidden');
                } else {
                    container.classList.add('chart-container-hidden');
                }
            });
        }
    </script>
</body>


</html>


