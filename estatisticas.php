<?php
include("conexao.php");


$registrosPorPagina = 10;


$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;


$offset = ($paginaAtual - 1) * $registrosPorPagina;

$sql = "SELECT DATE(a.data) as data,
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
        DATE_FORMAT(a.data, '%Y-%m') AS mes_ano,
        COUNT(*) AS quantidade
    FROM 
        atendimento a
    GROUP BY 
        DATE_FORMAT(a.data, '%Y-%m')
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

// Encode the data to JSON format
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historico - Relacionamento Médico</title>
    <link rel="icon" href="img\Logobordab.png" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
</head>
<style>   
    .swal2-confirm.btn-confirm {
        background-color: #0B700B; 
        border-color: #0B700B;
    }

    .swal2-cancel.btn-cancel {
        background-color: #6F0D0A; 
        border-color: #6F0D0A;
    }
    .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination button {
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid #001f3f;
            background-color: white;
            color: #001f3f;
            cursor: pointer;
        }

        .pagination button.active {
            background-color: #001f3f;
            color: white;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    a{
        color: black;
        text-decoration: none;
    }
    .loading svg polyline {
  fill: none;
  stroke-width: 3;
  stroke-linecap: round;
  stroke-linejoin: round;
}

.loading svg polyline#back {
  fill: none;
  stroke: #ff4d5033;
}

.loading svg polyline#front {
  fill: none;
  stroke: #00ffff;
  stroke-dasharray: 48, 144;
  stroke-dashoffset: 192;
  animation: dash_682 2s linear infinite;
  animation-delay: 0s;
}

.loading svg polyline#front2 {
  fill: none;
  stroke: #00ffff;
  stroke-dasharray: 48, 144;
  stroke-dashoffset: 192;
  animation: dash_682 2s linear infinite;
  animation-delay: 1s;
}

@keyframes dash_682 {
  72.5% {
    opacity: 0;
  }

  to {
    stroke-dashoffset: 0;
  }
}

</style>
<?php
    $pageTitle = "Histórico - Registros de Atendimento";
    include 'php/header.php';
?>











