<?php

include("conexao.php");

// Mapear abreviações dos meses para números
$monthMap = array(
    "Jan" => 1,
    "Feb" => 2,
    "Mar" => 3,
    "Apr" => 4,
    "May" => 5,
    "Jun" => 6,
    "Jul" => 7,
    "Aug" => 8,
    "Sep" => 9,
    "Oct" => 10,
    "Nov" => 11,
    "Dec" => 12
);

$mesClicado = isset($_GET['mes']) ? $monthMap[$_GET['mes']] : null;
$anoAtual = date('Y');

// Definir o início e o fim do mês
$startDate = $mesClicado ? date('Y-m-01', strtotime("$anoAtual-$mesClicado-01")) : null;
$endDate = $mesClicado ? date('Y-m-t', strtotime("$anoAtual-$mesClicado-01")) : null;

?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startDate = '<?php echo $startDate; ?>';
        const endDate = '<?php echo $endDate; ?>';

        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Definir valores mínimos e máximos para os campos de data
        if (startDate && endDate) {
            startDateInput.setAttribute('min', startDate);
            startDateInput.setAttribute('max', endDate);
            endDateInput.setAttribute('min', startDate);
            endDateInput.setAttribute('max', endDate);
        }
    });
</script>

<?php

// Consultas SQL baseadas no mês selecionado
if ($mesClicado) {
    // Filtrar por intervalo de datas
    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        
        $sql = "
            SELECT 
                a.data AS mes_ano,
                COUNT(*) AS quantidade
            FROM 
                atendimento a
            WHERE 
                MONTH(a.data) = $mesClicado
                AND a.data BETWEEN '$startDate' AND '$endDate'
            GROUP BY 
                mes_ano
            ORDER BY 
                mes_ano;
        ";
    } else {
        $sql = "
            SELECT 
                a.data AS mes_ano,
                COUNT(*) AS quantidade
            FROM 
                atendimento a
            WHERE 
                MONTH(a.data) = $mesClicado
            GROUP BY 
                mes_ano
            ORDER BY 
                mes_ano;
        ";
    }
} else {
    $sql = "
        SELECT 
            a.data AS mes_ano,
            COUNT(*) AS quantidade
        FROM 
            atendimento a
        GROUP BY 
            mes_ano
        ORDER BY 
            mes_ano;
    ";
}

$result = $conn->query($sql);
$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['mes_ano'];
    $data[] = $row['quantidade'];
}

$labelsJson = json_encode($labels);
$dataJson = json_encode($data);

// quant. status dos atendimentos
if(isset($_GET['mes'])) {
    $monthMap = array(
        "Jan" => 1,
        "Feb" => 2,
        "Mar" => 3,
        "Apr" => 4,
        "May" => 5,
        "Jun" => 6,
        "Jul" => 7,
        "Aug" => 8,
        "Sep" => 9,
        "Oct" => 10,
        "Nov" => 11,
        "Dec" => 12
    );
$mesClicadoAbbr = $_GET['mes'];
$mesClicado = $monthMap[$mesClicadoAbbr];
$sqlStatus = "
    SELECT  
		DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        situacao,
        COUNT(*) AS quantidade
    FROM 
        atendimento as a
    WHERE MONTH(a.data) = $mesClicado
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
}else{
    $sqlStatus = "
    SELECT  
		DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        situacao,
        COUNT(*) AS quantidade
    FROM 
        atendimento as a
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
}
// 


// quant. por veiculo de atendimento
if(isset($_GET['mes'])) {
    $monthMap = array(
        "Jan" => 1,
        "Feb" => 2,
        "Mar" => 3,
        "Apr" => 4,
        "May" => 5,
        "Jun" => 6,
        "Jul" => 7,
        "Aug" => 8,
        "Sep" => 9,
        "Oct" => 10,
        "Nov" => 11,
        "Dec" => 12
    );
$mesClicadoAbbr = $_GET['mes'];
$mesClicado = $monthMap[$mesClicadoAbbr];
$sqlVeiculoAtendimento = "
    SELECT  
	DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
	veiculo_atendimento,
	COUNT(*) AS quantidade
    FROM 
        atendimento as a
    WHERE MONTH(a.data) = $mesClicado
    GROUP BY 
        veiculo_atendimento
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
}else{
    $sqlVeiculoAtendimento = "
    SELECT  
	DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
	veiculo_atendimento,
	COUNT(*) AS quantidade
    FROM 
        atendimento as a
    GROUP BY 
        veiculo_atendimento
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
}
// 

// quant. assuntos no mês

$assuntos = [];
$quantidade = [];

if(isset($_GET['mes'])) {
    $monthMap = array(
        "Jan" => 1,
        "Feb" => 2,
        "Mar" => 3,
        "Apr" => 4,
        "May" => 5,
        "Jun" => 6,
        "Jul" => 7,
        "Aug" => 8,
        "Sep" => 9,
        "Oct" => 10,
        "Nov" => 11,
        "Dec" => 12
    );
$mesClicadoAbbr = $_GET['mes'];
$mesClicado = $monthMap[$mesClicadoAbbr];
$sqlassuntos = "
        SELECT 
        DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        assunto.assunto as ass,
        COUNT(has.assunto) AS quantidade
    FROM 
        relacionamentomedico.atendimento_has_assunto AS has
    INNER JOIN 
        relacionamentomedico.assunto AS assunto 
        ON has.assunto = assunto.id
    INNER JOIN 
        relacionamentomedico.atendimento AS a 
        ON has.atendimento = a.id
    WHERE MONTH(a.data) = $mesClicado
    GROUP BY 
        mes_ano, ass
    ORDER BY 
        mes_ano ASC, quantidade DESC;

";
$result = $conn->query($sqlassuntos);
$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $assuntos[] = $row['ass'];
    $quantidade[] = $row['quantidade'];
}
$assuntosjson = json_encode($assuntos);
$quantidadejson = json_encode($quantidade);
}else{
    $sqlassuntos = "
        SELECT 
        DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        assunto.assunto as ass,
        COUNT(has.assunto) AS quantidade
    FROM 
        relacionamentomedico.atendimento_has_assunto AS has
    INNER JOIN 
        relacionamentomedico.assunto AS assunto 
        ON has.assunto = assunto.id
    INNER JOIN 
        relacionamentomedico.atendimento AS a 
        ON has.atendimento = a.id
    GROUP BY 
        mes_ano, ass
    ORDER BY 
        mes_ano ASC, quantidade DESC;

";
$result = $conn->query($sqlassuntos);
$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $assuntos[] = $row['ass'];
    $quantidade[] = $row['quantidade'];
}
$assuntosjson = json_encode($assuntos);
$quantidadejson = json_encode($quantidade);
}



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
    .table-responsive {
        max-height: 200px; /* Define a altura máxima para a tabela */
        overflow-y: auto;  /* Adiciona barra de rolagem vertical se necessário */
        border: 1px solid #dee2e6; /* Borda ao redor da div */
        border-radius: 8px; /* Bordas arredondadas */
        padding: 10px; /* Espaçamento interno */
    }

    .table {
        width: 100%;
        table-layout: fixed; /* Garante que a tabela ocupe o espaço disponível */
        border-collapse: collapse; /* Remove espaços entre bordas da tabela */
    }

    .table th, .table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6; /* Borda para as células da tabela */
        text-align: left; /* Alinha o texto à esquerda */
        white-space: nowrap; /* Evita quebra de linha */
        overflow: hidden;
        text-overflow: ellipsis; /* Adiciona "..." ao texto que não cabe na célula */
    }

    .table th {
        background-color: #f8f9fa; /* Cor de fundo para cabeçalhos */
        font-weight: bold;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 0 15px;
<<<<<<< HEAD
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
            padding: 15px;
        }
        .chart-container {
            max-height: 400px; 
            overflow-y: auto; 
            margin-top: 20px; 
            border-radius: 5px; 
            padding: 10px;
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
        .table-responsive {
            max-height: 300px; 
            overflow-y: auto; 
            border: 1px solid #ddd; 
            border-radius: 5px;
            padding: 10px; 
        }
        table {
            width: 100%;
            border-collapse: collapse; 
        }
        th, td {
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        .table thead th {
            background-color: #1E3050; 
            color: white;
        }
        .btn-sm {
            font-size: 0.8rem; 
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
            border: 1px solid #AFAFAF; 
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
=======
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
        height: 600px;
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

    .table-container {
        display: block; /* Exibe a tabela quando necessário */
        margin-top: 20px;
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

    /* Classe para o botão selecionado */
    .selected-month {
        background-color: #004d00; /* Cor de fundo do botão selecionado */
        color: white; /* Cor do texto do botão selecionado */
    }

>>>>>>> origin/vitnovabranch
</style>

</head>
<?php
    $pageTitle = "Estatísticas - Registros de Atendimento";
    include 'php/header.php';
?>
<body>
    
<div class="container mt-5">

    <div class="row">
    <div class="btn-group d-flex justify-content-center flex-wrap" role="group" aria-label="Basic example" id="monthButtons">

    </div>

 



        <div class="col-xl-6 col-md-7 mb-4 mt-5">
            <div class="chart-box">
<<<<<<< HEAD
                <div class="date-filters-container">
                    <span class="filter-label">Filtro de Data</span>
                    <div class="date-filters">       
=======
            <b><span class="filter-label">Filtro de Data</span></b>
                    <div class="date-filters mt-2">       
>>>>>>> origin/vitnovabranch
                        <input type="date" id="startDate" placeholder="Data Inicial" title="data inicial">
                        <input type="date" id="endDate" placeholder="Data Final" title="data final">
                        <button id="applyFilterBtn">Aplicar Filtro</button>
                    </div>
<<<<<<< HEAD
                </div>
                <div class="chart-header">
=======
                <div class="chart-header mt-3">
>>>>>>> origin/vitnovabranch
                    Quantidade de Atendimentos no Mês
                </div>
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mês/Ano</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>
<<<<<<< HEAD
        <div class="col-xl-6 col-md-6 mb-4">
=======

        <div class="col-xl-6 col-md-7 mb-4 mt-5">
>>>>>>> origin/vitnovabranch
            <div class="chart-box">
                <div class="chart-header mt-2">
                    Status dos Atendimentos
                </div>
                <canvas id="doughnutChart" class="status"></canvas>
                <div class="table-responsive mt-5">
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
        <div class="col-xl-6 col-md-7 mb-4">
            <div class="chart-box">
                <div class="chart-header mt-2">
                    Quantidade de Assuntos
                </div>
                <canvas id="orgaoChart" class="chart-small"></canvas>
                <div class="table-responsive mt-5">
                    <table class="table table-bordered" id="dataTableOrgao" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Assunto</th>
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


        <div class="col-xl-6 col-md-7 mb-4">
            <div class="chart-box">
                <div class="chart-header mt-2">
                    Quantidade de Veículos de Atendimento
                </div>
                <canvas id="lineChart"></canvas>
                <div class="table-responsive mt-4">
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
<<<<<<< HEAD
    function renderChartAndTable(labelsJson, dataJson) {
        const labels = labelsJson;
        const data = dataJson;
        const combinedData = labels.map((label, index) => ({
            label: label,
            value: data[index]
        }));
        combinedData.sort((a, b) => b.value - a.value);
        const sortedLabels = combinedData.map(item => item.label);
        const sortedData = combinedData.map(item => item.value);
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
                chart.data.labels = filteredLabels;
                chart.data.datasets[0].data = filteredValues;
                chart.update();
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
=======
        const labels = <?php echo $labelsJson; ?>;
        const data = <?php echo $dataJson; ?>;
        let combinedData = labels.map((label, index) => ({
            label: label,
            value: data[index]
        }));

        let myChart; // Variable to store the chart instance

        // Function to update the chart
        function updateChart() {
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
            const endDate = endDateInput.value ? new Date(endDateInput.value) : null;

            // Filter data based on date range
            let filteredData = combinedData;

            if (startDate && endDate) {
                filteredData = combinedData.filter(item => {
                    const itemDate = new Date(item.label); // Assuming label is in a date-compatible format
                    return (!isNaN(itemDate.getTime()) && itemDate >= startDate && itemDate <= endDate);
                });
            }

            filteredData.sort((a, b) => b.value - a.value);

            const sortedLabels = filteredData.map(item => item.label);
            const sortedData = filteredData.map(item => item.value);

            const ctx = document.getElementById('barChart').getContext('2d');

            // Destroy existing chart if it exists
            if (myChart) {
                myChart.destroy();
            }

            // Create new chart
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sortedLabels,
                    datasets: [{
                        label: 'Quantidade de Atendimentos no Mês',
                        backgroundColor: '#A1C4FD', // Azul Claro
                        borderColor: '#A1C4FD', // Azul Claro
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

            // Update table
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Clear existing rows
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
        }

        // Event listeners for date inputs and apply filter button
        document.getElementById('applyFilterBtn').addEventListener('click', updateChart);

        // Initialize chart with general data on page load
        updateChart();
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

    const backgroundColor = sortedStatusLabels.map(label => label === "Aberto" ? '#9DF2C8' : '#F4A6B0');
    const borderColor = backgroundColor; // Same color for border

    const ctx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: sortedStatusLabels,
            datasets: [{
                label: 'Status dos Atendimentos',
                backgroundColor: backgroundColor,
                borderColor: borderColor,
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
>>>>>>> origin/vitnovabranch

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const assuntosLabels = <?php echo $assuntosjson; ?>;
    const assuntosData = <?php echo $quantidadejson; ?>;
    const combinedassuntosData = assuntosLabels.map((label, index) => ({
        label: label,
        value: assuntosData[index]
    }));
    combinedassuntosData.sort((a, b) => b.value - a.value);
    const sortedassuntosLabels = combinedassuntosData.map(item => item.label);
    const sortedassuntosData = combinedassuntosData.map(item => item.value);

    // Defina um array de cores correspondente
    const colors = [
        '#A1C4FD', // Azul Claro
        '#9DF2C8', // Verde Menta
        '#E0E0E0', // Cinza Claro
        '#F5F5F5', // Bege Claro
        '#F9AFAE', // Pêssego Claro
        '#E6BEE6', // Lavanda
        '#8FB1C2', // Cinza Azul
        '#F7E7A6', // Amarelo Claro
        '#F4A6B0', // Rosa Claro
        '#F4A79A', // Salmon Claro
        '#A8D5B8', // Verde Claro
        '#D8BFD8'  // Lilás
    ];

    // Mapeie os rótulos para cores
    const labelColors = {};
    sortedassuntosLabels.forEach((label, index) => {
        labelColors[label] = colors[index % colors.length];
    });

    const ctx = document.getElementById('orgaoChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: sortedassuntosLabels,
            datasets: [{
                label: 'Quantidade de assuntos',
                backgroundColor: sortedassuntosLabels.map(label => labelColors[label]),
                borderColor: sortedassuntosLabels.map(label => labelColors[label]),
                borderWidth: 1,
                data: sortedassuntosData,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
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
    for (let i = 0; i < sortedassuntosLabels.length; i++) {
        const row = document.createElement('tr');
        const cell1 = document.createElement('td');
        const cell2 = document.createElement('td');
        
        // Define o conteúdo das células
        cell1.textContent = sortedassuntosLabels[i];
        cell2.textContent = sortedassuntosData[i];

        // Aplica a cor de fundo às células com base no labelColors
        const color = labelColors[sortedassuntosLabels[i]] || '#FFFFFF'; // Fallback para branco se a cor não for encontrada
        cell1.style.backgroundColor = color;
        cell2.style.backgroundColor = color;

        // Adiciona as células à linha e a linha à tabela
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
                    backgroundColor: '#A1C4FD', // Azul Claro
                    borderColor: '#A1C4FD', // Azul Claro
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
<script>
// Função para obter o nome do mês
function getMonthName(monthIndex) {
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[monthIndex];
}

const monthButtonsContainer = document.getElementById("monthButtons");

// Função para marcar o botão selecionado
function highlightSelectedMonth(button) {
    // Remove a classe 'selected-month' de todos os botões
    const buttons = document.querySelectorAll("#monthButtons button");
    buttons.forEach(btn => btn.classList.remove("selected-month"));

    // Adiciona a classe 'selected-month' ao botão clicado
    button.classList.add("selected-month");
}

// Loop para obter os nomes dos meses de janeiro até dezembro
for (let i = 0; i < 12; i++) {
    const monthName = getMonthName(i);

    const button = document.createElement("button");
    button.type = "button";
    button.classList.add("btn", "btn-success");
    button.textContent = monthName;
    button.id = monthName;

    // Adicione um evento de clique ao botão
    button.addEventListener("click", function () {
        const monthClicked = this.id;
        const currentURL = window.location.href;

        // Verifica se já existe um parâmetro 'mes' na URL
        if (currentURL.indexOf("?mes=") !== -1) {
            // Se já existe, substitui o valor do parâmetro 'mes'
            const updatedURL = currentURL.replace(/(mes=)[^\&]+/, '$1' + monthClicked);
            // Redireciona para a nova URL
            window.location.href = updatedURL;
        } else {
            // Se não existe, adiciona o parâmetro 'mes' à URL
            const updatedURL = `${currentURL}?mes=${monthClicked}`;
            // Redireciona para a nova URL
            window.location.href = updatedURL;
        }

        // Destaque o botão clicado
        highlightSelectedMonth(this);
    });

    monthButtonsContainer.appendChild(button);
}

// Se o URL já contiver um mês selecionado, destaque o botão correspondente
const urlParams = new URLSearchParams(window.location.search);
const selectedMonth = urlParams.get("mes");
if (selectedMonth) {
    const selectedButton = document.getElementById(selectedMonth);
    if (selectedButton) {
        highlightSelectedMonth(selectedButton);
    }
}


</script>

</body>
</html>
