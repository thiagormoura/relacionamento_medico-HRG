<?php
include("conexao.php");

// // quant. atendimentos no mês
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
$sql = "
    SELECT 
        DATE_FORMAT(a.data, '%m/%Y') AS mes_ano,
        COUNT(*) AS quantidade
    FROM 
        atendimento a
    WHERE MONTH(a.data) = $mesClicado
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
}else{
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
}
// // 


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
    max-height: 300px; /* Define a altura máxima para a tabela */
    overflow-y: auto;  /* Adiciona barra de rolagem vertical se necessário */
}

.table {
    table-layout: fixed; /* Garante que a tabela ocupe o espaço disponível */
    word-wrap: break-word; /* Quebra as palavras longas para evitar overflow */
}

.table th, .table td {
    white-space: nowrap; /* Evita que o texto quebre em várias linhas */
    overflow: hidden;
    text-overflow: ellipsis; /* Adiciona "..." ao texto que não cabe na célula */
}

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

    <div class="row">
   

    
    </div>


        <div class="col-xl-6 col-md-6 mb-4 mt-5">
            <div class="chart-box">
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

        <div class="col-xl-6 col-md-6 mb-4 mt-5">
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
                    Quantidade de Assuntos
                </div>
                <canvas id="orgaoChart" class="chart-small"></canvas>
                <div class="table-responsive">
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
        const labels = <?php echo $labelsJson; ?>;
        const data = <?php echo $dataJson; ?>;
        const combinedData = labels.map((label, index) => ({
            label: label,
            value: data[index]
        }));
        combinedData.sort((a, b) => b.value - a.value);
        const sortedLabels = combinedData.map(item => item.label);
        const sortedData = combinedData.map(item => item.value);
        const ctx = document.getElementById('barChart').getContext('2d');
        new Chart(ctx, {
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
    });
    document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('toggleTableBtn');
    const tableContainer = document.querySelector('.table-container');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleButton.addEventListener('click', function () {
        if (tableContainer.style.display === 'none' || tableContainer.style.display === '') {
            tableContainer.style.display = 'block';
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
            toggleButton.textContent = ' Ocultar Tabela';
        } else {
            tableContainer.style.display = 'none';
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
            toggleButton.textContent = ' Mostrar Tabela';
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

    const backgroundColor = sortedStatusLabels.map(label => label === "Aberto" ? '#4BC0C0' : '#F7464A');
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
        const ctx = document.getElementById('orgaoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: sortedassuntosLabels,
                datasets: [{
                    label: 'Quantidade de assuntos',
                    backgroundColor: [
                    '#FF6384', // Rosa
                    '#36A2EB', // Azul
                    '#FFCE56', // Amarelo
                    '#4BC0C0', // Verde água
                    '#9966FF', // Roxo
                    '#FF9F40', // Laranja
                    '#FFCD56', // Ouro
                    '#4D5360', // Cinza escuro
                    '#C9CBCF', // Cinza claro
                    '#46BFBD', // Verde esmeralda
                    '#F7464A', // Vermelho forte
                    '#97BBCD', // Azul pastel
                    ],
                    borderColor: [
                        '#FF6384', // Rosa
                        '#36A2EB', // Azul
                        '#FFCE56', // Amarelo
                        '#4BC0C0', // Verde água
                        '#9966FF', // Roxo
                        '#FF9F40', // Laranja
                        '#FFCD56', // Ouro
                        '#4D5360', // Cinza escuro
                        '#C9CBCF', // Cinza claro
                        '#46BFBD', // Verde esmeralda
                        '#F7464A', // Vermelho forte
                        '#97BBCD', // Azul pastel
                    ],
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
            cell1.textContent = sortedassuntosLabels[i];
            cell2.textContent = sortedassuntosData[i];
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
<script>
    // Função para obter o nome do mês
function getMonthName(monthIndex) {
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    return months[monthIndex];
}

const monthButtonsContainer = document.getElementById("monthButtons");

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
    });

    monthButtonsContainer.appendChild(button);
}

</script>

</body>
</html>
