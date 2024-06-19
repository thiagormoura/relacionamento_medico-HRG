<?php 
include("conexao.php");

$sql = "SELECT 
DATE(a.data) as data,
im.nome as nome_profissional,
a.situacao as situacao


FROM 
    relacionamentomedico.atendimento AS a
JOIN 
    relacionamentomedico.inform_medicos AS im ON a.profissional = im.id
JOIN 
    relacionamentomedico.assunto AS assuntos ON assuntos.id = a.id
JOIN 
    relacionamentomedico.atendimento_has_assunto AS has ON has.id = assuntos.id";

$result = $conn->query($sql);


// $data_json = json_encode($data);
// $nome_json = json_encode($nome);
// $situacao_json = json_encode($situacao);
// print_r($data_json);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historico-Registro de Atendimento</title>
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
    main{
       padding: 2em;
    }
</style>
<body>
    <!-- Parte do header e nav -->
    <?php

    $pageTitle = "Histórico - Registro de Atendimento";
    include 'php/header.php';
    ?>



<main class="container-fluid d-flex justify-content-center align-items-center">
<div class="form-group col-10 mt-5">
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button shadow-sm text-white text-center" type="button" data-toggle="collapse" data-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #1c8f69">
                    <i id="filter" class="fa-solid fa-filter mb-1"></i>
                    <h5>Filtro - Atendimentos</h5>
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample">
                <div class="accordion-body mt-4 mb-4">
                    <div class="row">
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="date" class="form-control" id="filterData" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="text" class="form-control" id="filterNome" placeholder="Profissional" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <select class="form-control" id="filterAssunto">
                                <option value="">Todos os assuntos</option>
                                <option value="Atualização cadastral do Médico">Atualização cadastral do Médico</option>
                                <option value="Autorização de procedimentos">Autorização de procedimentos</option>
                                <option value="Cadastro Médico">Cadastro Médico</option>
                                <option value="Demandas da Contabilidade">Demandas da Contabilidade</option>
                                <option value="Demandas do Faturamento">Demandas do Faturamento</option>
                                <option value="Demandas do INCOR">Demandas do INCOR</option>
                                <option value="Demandas do RH">Demandas do RH</option>
                                <option value="Demandas do setor Financeiro">Demandas do setor Financeiro</option>
                                <option value="Demandas do setor de TI">Demandas do setor de TI</option>
                                <option value="Estacionamento">Estacionamento</option>
                                <option value="Repasse Médico">Repasse Médico</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <select class="form-control" id="filterStatus">
                                <option value="">Todos os status</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Andamento">Andamento</option>
                                <option value="Fechado">Fechado</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" id="applyFilters" onclick="applyFilters()">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th class="text-left">Data</th>
                <th class="text-left">Nome do Profissional</th>
                <th class="text-left">Assunto Tratado</th>
                <th class="text-left">Status</th>
            </tr>
        </thead>
        <tbody id="tableBody">
        <?php
        function getBadgeClass($situacao) {
            switch ($situacao) {
                case 'Ativo':
                    return 'badge bg-success';
                case 'Fechado':
                    return 'badge bg-danger';
                case 'Andamento':
                    return 'badge bg-warning';
                default:
                    return 'badge bg-secondary';
            }
        }
        $sql_profissionais = "SELECT id, data_nascimento, nome, situacao_atendimento FROM profissionais";
        $result_profissionais = $conn->query($sql_profissionais);
        $sql_assunto = "SELECT assunto FROM assunto ORDER BY id DESC";
        $result_assunto = $conn->query($sql_assunto);
        $assuntos = [];
        if ($result_assunto && $result_assunto->num_rows > 0) {
            while ($row_assunto = $result_assunto->fetch_assoc()) {
                $assuntos[] = $row_assunto['assunto'];
            }
        }
        if ($result_profissionais && $result_profissionais->num_rows > 0) {
            $index = 0;
            while ($row = $result_profissionais->fetch_assoc()) {
                $data_nascimento = new DateTime($row['data_nascimento']);
                $data_nascimento_formatada = $data_nascimento->format('d/m/Y');
                echo "<tr data-toggle='modal' data-target='#detalhesModal'>";
                echo "<td class='text-left'>" . htmlspecialchars($data_nascimento_formatada) . "</td>";
                echo "<td class='text-left'>" . htmlspecialchars($row['nome']) . "</td>";
                if (isset($assuntos[$index])) {
                    echo "<td class='text-left'>" . htmlspecialchars($assuntos[$index]) . "</td>";
                } else {
                    echo "<td class='text-left'>Nenhum assunto encontrado</td>";
                }
                $situacao = htmlspecialchars($row['situacao_atendimento']);
                echo "<td class='text-left'><span class='" . getBadgeClass($situacao) . "'>" . $situacao . "</span></td>";
                echo "</tr>";
                $index++;
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>Nenhum profissional encontrado</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
    <script>
        function applyFilters() {
            var filterData = document.getElementById('filterData').value;
            var filterNome = document.getElementById('filterNome').value.trim().toLowerCase(); // Remove espaços extras e converte para minúsculas
            var filterAssunto = document.getElementById('filterAssunto').value.toLowerCase();
            var filterStatus = document.getElementById('filterStatus').value.toLowerCase();
            var table = document.getElementById('tableBody');
            var tr = table.getElementsByTagName('tr');
            for (var i = 0; i < tr.length; i++) {
                var tdData = tr[i].getElementsByTagName('td')[0];
                var tdNome = tr[i].getElementsByTagName('td')[1];
                var tdAssunto = tr[i].getElementsByTagName('td')[2];
                var tdStatus = tr[i].getElementsByTagName('td')[3];
                if (tdData && tdNome && tdAssunto && tdStatus) {
                    var txtValueData = tdData.textContent || tdData.innerText;
                    var txtValueNome = tdNome.textContent || tdNome.innerText;
                    var txtValueAssunto = tdAssunto.textContent || tdAssunto.innerText;
                    var txtValueStatus = tdStatus.textContent || tdStatus.innerText;
                    var tableDateFormatted = txtValueData.split('/').reverse().join('-');
                    var cleanTxtValueNome = txtValueNome.trim().toLowerCase();
                    var dataMatches = filterData === "" || tableDateFormatted === filterData;
                    var nomeMatches = cleanTxtValueNome.indexOf(filterNome) > -1;
                    var assuntoMatches = txtValueAssunto.toLowerCase().indexOf(filterAssunto) > -1;
                    var statusMatches = filterStatus === "" || txtValueStatus.toLowerCase().indexOf(filterStatus) > -1;
                    if (dataMatches && nomeMatches && assuntoMatches && statusMatches) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        document.getElementById('applyFilters').addEventListener('click', applyFilters);
    </script>
</div>   
</div>  
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


</body>
</html>

