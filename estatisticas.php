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

$sqlVeiculoAtendimento = "
    SELECT 
        veiculo_atendimento,
        COUNT(*) AS quantidade
    FROM 
        atendimento
    GROUP BY 
        veiculo_atendimento;
";
$resultVeiculoAtendimento = $conn->query($sqlVeiculoAtendimento);
$veiculoLabels = [];
$veiculoData = [];
while ($row = $resultVeiculoAtendimento->fetch_assoc()) {
    $veiculoLabels[] = $row['veiculo_atendimento'];
    $veiculoData[] = $row['quantidade'];
}
$veiculoLabelsJson = json_encode($veiculoLabels);
$veiculoDataJson = json_encode($veiculoData);

$sqlOrg = "
    SELECT 
        orgao,
        COUNT(*) AS quantidade
    FROM 
        profissionais
    GROUP BY 
        orgao;
";
$resultOrg = $conn->query($sqlOrg);
$orgLabels = [];
$orgData = [];
while ($row = $resultOrg->fetch_assoc()) {
    $orgLabels[] = $row['orgao'];
    $orgData[] = $row['quantidade'];
}
$orgLabelsJson = json_encode($orgLabels);
$orgDataJson = json_encode($orgData);

$sqlOrg = "
    SELECT orgao, COUNT(*) AS quantidade
    FROM profissionais
    GROUP BY orgao;
";

$resultOrg = $conn->query($sqlOrg);
$orgaoLabels = [];
$orgaoData = [];

while ($row = $resultOrg->fetch_assoc()) {
    $orgaoLabels[] = $row['orgao'];
    $orgaoData[] = $row['quantidade'];
}

$orgaoLabelsJson = json_encode($orgaoLabels);
$orgaoDataJson = json_encode($orgaoData);

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
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 0 15px; 
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        width: 100%;
    }

    .col-xl-6, .col-md-6 {
        display: flex;
        justify-content: center; 
        margin-bottom: 30px; 
    }

    .chart-box {
        position: relative;
        width: 100%;
        max-width: 550px; 
        margin: 0 auto; 
        height: 400px; 
        border: 1px solid #ccc; 
        border-radius: 8px; 
        padding: 15px; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    .chart-header {
        font-size: 1.25rem;
        color: #1E3050;
        text-align: center;
        margin-bottom: 10px; 
    }

    .chart-box canvas {
        width: 80%;
        height: 80%; 
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
<?php
    $pageTitle = "Estatísticas - Registros de Atendimento";
    include 'php/header.php';
?>
<body>
<div class="container mt-2">
        <div class="row mt-4">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="chart-box">
                    <div class="chart-header">
                        Quantidade de Atendimentos no Mês
                    </div>
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="chart-box">
                    <div class="chart-header">
                        Status dos Atendimentos
                    </div>
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="chart-box">
                    <div class="chart-header">
                        Distribuição por Órgão
                    </div>
                    <canvas id="orgaoChart"></canvas>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="chart-box">
                    <div class="chart-header">
                        Quantidade de Veículos de Atendimento
                    </div>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const labels = <?php echo $labelsJson; ?>;
    const data = <?php echo $dataJson; ?>;

    const ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantidade de Atendimentos no Mês',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                data: data,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const statusLabels = <?php echo $statusLabelsJson; ?>;
    const statusData = <?php echo $statusDataJson; ?>;

    const ctx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Status dos Atendimentos',
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
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
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const orgaoLabels = <?php echo $orgaoLabelsJson; ?>;
    const orgaoData = <?php echo $orgaoDataJson; ?>;

    const ctx = document.getElementById('orgaoChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: orgaoLabels,
            datasets: [{
                label: 'Distribuição por Órgão',
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
                data: orgaoData,
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
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const veiculoLabels = <?php echo $veiculoLabelsJson; ?>;
    const veiculoData = <?php echo $veiculoDataJson; ?>;

    const ctx = document.getElementById('lineChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: veiculoLabels,
            datasets: [{
                label: 'Quantidade de Veículos de Atendimento',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                data: veiculoData,
            }]
        },
        options: {
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


