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
            height: 800px;
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
            width: 100%;
            height: 300px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .table-container {
            display: none; /* Inicialmente oculta a tabela */
            margin-top: 20px;
        }

        .table {
            font-size: 0.9rem; /* Diminuir o tamanho da fonte da tabela */
        }

        .btn-sm {
            font-size: 0.8rem; /* Diminuir o tamanho do botão */
        }

        #toggleIcon {
            margin-right: 5px;
        }

        .chart-small {
            width: 100%;
            max-width: 200px; 
            height: 100px; 
            margin: 0 auto;
        }
        .status {
            width: 100%;
            max-width: 200px; 
            height: 100px; 
            margin: 0 auto;
        }
        .date-filters-container {
            padding: 10px; 
            border-radius: 10px; 
            margin: 10px auto; 
        }

        .filter-label {
            display: block;
            color: black; 
            font-size: 18px; 
            margin-bottom: 10px; 
        }

        .date-filters {
            display: flex; 
            gap: 3px; 
            align-items: center; 
        }
        .date-filters input[type="date"] {
            background-color: #ffffff; 
            border: 2px solid #AFAFAF; 
            border-radius: 5px; 
            padding: 7px; 
            font-size: 16px; 
            color: #1E3050; 
            width: 170px;
            box-sizing: border-box; 
        }
        #applyFilterBtn {
            background-color: #1E3050;
            color: #ffffff; 
            border: none; 
            padding: 8px 15px; 
            font-size: 16px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }
        #applyFilterBtn:hover {
            background-color: #14324e; 
        }
</style>

</head>
<?php
    $pageTitle = "Estatísticas - Registros de Atendimento";
    include 'php/header.php';
?>
<body>
<div class="container mt-2">
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="chart-box">
                <div class="date-filters-container">
                <span class="filter-label">Filtro de Data</span>
                    <div class="date-filters">       
                        <input type="date" id="startDate" placeholder="Data Inicial" title="data inicial">
                        <input type="date" id="endDate" placeholder="Data Final" title="data final">
                        <button id="applyFilterBtn">Aplicar Filtro</button>
                    </div>
                </div>
                <div class="chart-header">
                    Quantidade de Atendimentos no Mês
                </div>
                <canvas id="barChart"></canvas>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mês/Ano</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Dados serão preenchidos via JavaScript -->
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="chart-box">
                <div class="chart-header">
                    Status dos Atendimentos
                </div>
                <canvas id="doughnutChart" class="status"></canvas>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableStatus" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyStatus">
                            <!-- Dados serão preenchidos via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="chart-box">
                <div class="chart-header">
                    Distribuição por Órgão
                </div>
                <canvas id="orgaoChart" class="chart-small"></canvas>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableOrgao" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Órgão</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyOrgao">
                            <!-- Dados serão preenchidos via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-xl-6 col-md-6 mb-4">
            <div class="chart-box">
                <div class="chart-header">
                    Quantidade de Veículos de Atendimento
                </div>
                <canvas id="lineChart"></canvas>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableVeiculo" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Veículo de Atendimento</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyVeiculo">
                            <!-- Dados serão preenchidos via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    function renderChartAndTable(labelsJson, dataJson) {
        const labels = labelsJson;
        const data = dataJson;

        // Combina labels e data em um array de objetos, depois ordena por valor
        const combinedData = labels.map((label, index) => ({
            label: label,
            value: data[index]
        }));
        combinedData.sort((a, b) => b.value - a.value);

        // Separa os dados ordenados de volta em labels e data
        const sortedLabels = combinedData.map(item => item.label);
        const sortedData = combinedData.map(item => item.value);

        // Cria o gráfico de barras
        const ctx = document.getElementById('barChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sortedLabels,
                datasets: [{
                    label: 'Quantidade de Atendimentos no Mês',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    data: sortedData,
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

        // Preenche a tabela com os dados
        const tableBody = document.getElementById('tableBody');
        for (let i = 0; i < sortedLabels.length; i++) {
            const row = document.createElement('tr');
            const cell1 = document.createElement('td');
            const cell2 = document.createElement('td');

            cell1.textContent = sortedLabels[i];
            cell2.textContent = sortedData[i];

            row.appendChild(cell1);
            row.appendChild(cell2);
            tableBody.appendChild(row);
        }

        return chart;
    }

    function filterData(labels, data, startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const filteredData = labels.map((label, index) => {
            const [month, year] = label.split('/');
            const date = new Date(year, month - 1);
            if (date >= start && date <= end) {
                return { label: label, value: data[index] };
            }
            return null;
        }).filter(item => item !== null);

        filteredData.sort((a, b) => b.value - a.value);

        const filteredLabels = filteredData.map(item => item.label);
        const filteredValues = filteredData.map(item => item.value);

        return { filteredLabels, filteredValues };
    }

    const labels = <?php echo $labelsJson; ?>;
    const data = <?php echo $dataJson; ?>;

    let chart = renderChartAndTable(labels, data);

    document.getElementById('applyFilterBtn').addEventListener('click', function () {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate) {
            const { filteredLabels, filteredValues } = filterData(labels, data, startDate, endDate);

            // Atualiza o gráfico com os dados filtrados
            chart.data.labels = filteredLabels;
            chart.data.datasets[0].data = filteredValues;
            chart.update();

            // Atualiza a tabela com os dados filtrados
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
            for (let i = 0; i < filteredLabels.length; i++) {
                const row = document.createElement('tr');
                const cell1 = document.createElement('td');
                const cell2 = document.createElement('td');

                cell1.textContent = filteredLabels[i];
                cell2.textContent = filteredValues[i];

                row.appendChild(cell1);
                row.appendChild(cell2);
                tableBody.appendChild(row);
            }
        }
    });
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusLabels = <?php echo $statusLabelsJson; ?>;
        const statusData = <?php echo $statusDataJson; ?>;
        const combinedStatusData = statusLabels.map((label, index) => ({
            label: label,
            value: statusData[index]
        }));
        combinedStatusData.sort((a, b) => b.value - a.value);
        const sortedStatusLabels = combinedStatusData.map(item => item.label);
        const sortedStatusData = combinedStatusData.map(item => item.value);
        const ctx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sortedStatusLabels,
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
                    data: sortedStatusData,
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
        const tableBodyStatus = document.getElementById('tableBodyStatus');
        for (let i = 0; i < sortedStatusLabels.length; i++) {
            const row = document.createElement('tr');
            const cell1 = document.createElement('td');
            const cell2 = document.createElement('td');
            cell1.textContent = sortedStatusLabels[i];
            cell2.textContent = sortedStatusData[i];
            row.appendChild(cell1);
            row.appendChild(cell2);
            tableBodyStatus.appendChild(row);
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const orgaoLabels = <?php echo $orgaoLabelsJson; ?>;
        const orgaoData = <?php echo $orgaoDataJson; ?>;
        const combinedOrgaoData = orgaoLabels.map((label, index) => ({
            label: label,
            value: orgaoData[index]
        }));
        combinedOrgaoData.sort((a, b) => b.value - a.value);
        const sortedOrgaoLabels = combinedOrgaoData.map(item => item.label);
        const sortedOrgaoData = combinedOrgaoData.map(item => item.value);
        const ctx = document.getElementById('orgaoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sortedOrgaoLabels,
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
                    data: sortedOrgaoData,
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
        const tableBodyOrgao = document.getElementById('tableBodyOrgao');
        for (let i = 0; i < sortedOrgaoLabels.length; i++) {
            const row = document.createElement('tr');
            const cell1 = document.createElement('td');
            const cell2 = document.createElement('td');  
            cell1.textContent = sortedOrgaoLabels[i];
            cell2.textContent = sortedOrgaoData[i];
            row.appendChild(cell1);
            row.appendChild(cell2);
            tableBodyOrgao.appendChild(row);
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const veiculoLabels = <?php echo $veiculoLabelsJson; ?>;
        const veiculoData = <?php echo $veiculoDataJson; ?>;
        const combinedVeiculoData = veiculoLabels.map((label, index) => ({
            label: label,
            value: veiculoData[index]
        }));
        combinedVeiculoData.sort((a, b) => b.value - a.value);
        const sortedVeiculoLabels = combinedVeiculoData.map(item => item.label);
        const sortedVeiculoData = combinedVeiculoData.map(item => item.value);
        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: sortedVeiculoLabels,
                datasets: [{
                    label: 'Quantidade de Veículos de Atendimento',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    data: sortedVeiculoData,
                    fill: true
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
        const tableBodyVeiculo = document.getElementById('tableBodyVeiculo');
        for (let i = 0; i < sortedVeiculoLabels.length; i++) {
            const row = document.createElement('tr');
            const cell1 = document.createElement('td');
            const cell2 = document.createElement('td');  
            cell1.textContent = sortedVeiculoLabels[i];
            cell2.textContent = sortedVeiculoData[i];
            row.appendChild(cell1);
            row.appendChild(cell2);
            tableBodyVeiculo.appendChild(row);
        }
    });
</script>


</body>
</html>
