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


            <div class="accordion" id="accordionPanelsStayOpenExample" class="text-center">
                <div class="accordion-item text-center">
                    <h2 class="accordion-header">
                    <button class="accordion-button shadow-sm text-white text-center" type="button" data-toggle="collapse" data-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #1c8f69 ">


                            <i id="filter" class="fa-solid fa-filter mb-1"></i>
                            <h5>Filtro - Atendimentos</h5>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample">
                        <div class="accordion-body">
                            <div class="col-12">
                                <input type="text" class="form-control" id="inputFilter" placeholder="Filtrar....">
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                    <input type="date" class="form-control" id="dateFilter">
                                </div>
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                <input type="text" class="form-control" id="inputprofissional" placeholder="Profissional">
                                </div>
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                    <select class="form-control" id="subjectFilter">
                                        <option value="">Todos os assuntos</option>
                                        <option value="">Atualização cadastral do Médico</option>
                                        <option value="">Autorização de procedimentos</option>
                                        <option value="">Cadastro Médico</option>
                                        <option value="">Demandas da Contabilidade</option>
                                        <option value="">Demandas do Faturamento</option>
                                        <option value="">Demandas do INCOR</option>
                                        <option value="">Demandas do RH</option>
                                        <option value="">Demandas do setor Financeiro</option>
                                        <option value="">Demandas do setor de TI</option>
                                        <option value="">Estacionamento</option>
                                        <option value="">Repasse Médico</option>
                                    </select>
                                </div>
                               
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">Todos os status</option>
                                        <option value="Aberto">Aberto</option>
                                        <option value="Emandamento">Em andamento</option>
                                        <option value="Fechado">Fechado</option>
                                    </select>
                                </div>

                            </div> <br> <button class="btn btn-primary" id="applyFilters">Aplicar Filtros</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="border p-3">
       
<table class="table">
    <thead class="thead-light">
        <tr>
            <th>Data</th>
            <th>Nome do Profissional</th>
            <th>Assunto Tratado</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Verificando se a consulta retornou resultados
        if ($result && $result->num_rows > 0) {
            // Loop pelos resultados da consulta
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['data'] . "</td>";
                echo "<td>" . $row['nome_profissional'] . "</td>";
                echo "<td>" .  "</td>"; // Adicionei esta linha corrigindo o nome da coluna
                echo "<td>" . $row['situacao'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum resultado encontrado</td></tr>";
        }
        ?>
    </tbody>
</table>
            </div>
        </div>
      
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#applyFilters').click(function() {
                var inputFilter = $('#inputFilter').val().toUpperCase();
                var dateFilter = $('#dateFilter').val();
                var subjectFilter = $('#subjectFilter').val();
                var stateFilter = $('#stateFilter').val();
                var statusFilter = $('#statusFilter').val();

                $('tbody tr').each(function() {
                    var row = $(this).html().toUpperCase();
                    if ((row.indexOf(inputFilter) > -1 || inputFilter === '') &&
                        ($(this).find('td:eq(0)').text() ==dateFilter || dateFilter === '') &&
($(this).find('td:eq(1)').text() == subjectFilter || subjectFilter === '') &&
($(this).find('td:eq(2)').text() == stateFilter || stateFilter === '') &&
($(this).find('td:eq(4)').text() == statusFilter || statusFilter === '')) {
    $(this).show();
} else {
    $(this).hide();
}
});
})});
</script>
</body>
</html>

