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

    
    a{
        color: black;
        text-decoration: none;
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
                                <option value="Aberto">Aberto</option>
                                <option value="Andamento">Andamento</option>
                                <option value="Desconhecido">Desconhecido</option>
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
            <th class="text-center">Dados</th>
        </tr>
    </thead>
    <tbody id="tableBody">
    <?php
    function getBadgeClass($situacao) {
        switch ($situacao) {
            case 'Aberto':
                return 'badge bg-success';  // Correção: "Aberto" deve ser verde
            case 'Andamento':
                return 'badge bg-warning';
            case 'Desconhecido':
                return 'badge bg-warning';
            default:
                return 'badge bg-success';
        }
    }

    // Modificar a query para incluir o JOIN entre as tabelas "profissionais" e "atendimento"
    $sql_profissionais = "
    SELECT p.id, p.nome, p.cpf, p.telefone, p.telefone2, p.email, p.endereco, p.registro, p.especialidades, p.orgao, a.id AS id_atendimento, a.assunto, a.situacao, a.data, a.descricao, a.acoes, a.veiculo_atendimento
    FROM profissionais p
    LEFT JOIN atendimento a ON p.id = a.profissional
    ORDER BY p.id ASC";

    $result_profissionais = $conn->query($sql_profissionais);

    if ($result_profissionais && $result_profissionais->num_rows > 0) {
        while ($row = $result_profissionais->fetch_assoc()) {
            $data_atendimento = new DateTime($row['data']);
            $data_atendimento_formatada = $data_atendimento->format('d/m/Y');
            $cpf = htmlspecialchars($row['cpf']);
            $telefone = htmlspecialchars($row['telefone']);
            $telefone2 = htmlspecialchars($row['telefone2']);
            $email = htmlspecialchars($row['email']);
            $assunto = htmlspecialchars($row['assunto']) ? htmlspecialchars($row['assunto']) : "Nenhum assunto encontrado";
            $situacao = htmlspecialchars($row['situacao']) ? htmlspecialchars($row['situacao']) : "Desconhecido";
            $badge_class = getBadgeClass($situacao);
            echo "<tr>";
            echo "<td class='text-left'>" . htmlspecialchars($data_atendimento_formatada) . "</td>";
            echo "<td class='text-left'>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td class='text-left'>" . $assunto . "</td>";
            echo "<td class='text-left'><span class='$badge_class'>" . $situacao . "</span></td>";
            echo "<td class='text-center'>
                <button class='btn btn-primary' onclick='redirectToDetails({$row['id_atendimento']})'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' width='20' height='20'>
                        <path fill='#ffffff' d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/>
                    </svg>
                </button>
            </td>";
            echo "</tr>";
            
            echo "<div class='modal fade' id='detalhesModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='detalhesModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header' style='border: none;'>"; // Remova a borda do modal-header para não cobrir o botão de fechar
            echo "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512' width='20' height='20' style='cursor: pointer; position: absolute; right: 10px; top: 10px;' data-dismiss='modal' aria-label='Fechar'>";
            echo "<path d='M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z'/>";
            echo "</svg>";
            echo "</div>";
            
            echo "<div class='modal-body'>";
            
            // Dados do Profissional
            echo "<div class='mb-3'>";
            echo "<h5>Dados do Profissional</h5>";
            echo "<hr>";
            
            echo "<div class='row'>";
            
            // Nome
            echo "<div class='col-6'>";
            echo "<label><b>Nome:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['nome']) . "</p>";
            echo "</div>";
            
            // CPF
            echo "<div class='col-6'>";
            echo "<label><b>CPF:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $cpf . "</p>";
            echo "</div>";
            
            // Telefone
            echo "<div class='col-4'>";
            echo "<label><b>Número de Celular:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $telefone . "</p>";
            echo "</div>";
            
            // Telefone 2
            echo "<div class='col-4'>";
            echo "<label><b>Número de Celular 2:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $telefone2 . "</p>";
            echo "</div>";
            
            // Registro
            echo "<div class='col-4'>";
            echo "<label><b>Registro:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['registro']) . "</p>";
            echo "</div>";


            // Email
            echo "<div class='col-6'>";
            echo "<label><b>E-mail:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $email . "</p>";
            echo "</div>";
            
            // Endereço
            echo "<div class='col-6'>";
            echo "<label><b>Endereço:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['endereco']) . "</p>";
            echo "</div>";
            
            // Estados
            // echo "<div class='col-4'>";
            // echo "<label><b>Estado:</b></label>";
            // echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['estados']) . "</p>";
            // echo "</div>";
            
            
            // Especialidades
            echo "<div class='col-4'>";
            echo "<label><b>Especialidades:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['especialidades']) . "</p>";
            echo "</div>";
            
            // Orgão
            echo "<div class='col-4'>";
            echo "<label><b>Órgão:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['orgao']) . "</p>";
            echo "</div>";
            
            echo "</div>"; // row
            
            echo "</div>"; // mb-3
            
            // Dados do Atendimento
            
            echo "<div class='mb-3'>";
            echo "<h5>Dados do Atendimento</h5>";
            echo "<hr>";

            echo "<div class='row'>";

            // Data de Atendimento
            echo "<div class='col-4'>";
            echo "<label><b>Data de Atendimento:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $data_atendimento_formatada . "</p>";
            echo "</div>";

            echo "<div class='col-4'>";
            echo "<label><b>Assunto Tratado:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . $assunto . "</p>";
            echo "</div>";

            echo "<div class='col-4'>";
            echo "<label><b>Status:</b></label>";
            echo "<p><span class='$badge_class'>" . $situacao . "</span></p>";
            echo "</div>";

            // Descrição
            echo "<div class='col-12'>";
            echo "<label><b>Descrição:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['descricao']) . "</p>";
            echo "</div>";

            // Ações
            echo "<div class='col-6'>";
            echo "<label><b>Ações:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['acoes']) . "</p>";
            echo "</div>";

            // Veículo de Atendimento
            echo "<div class='col-6'>";
            echo "<label><b>Veículo de Atendimento:</b></label>";
            echo "<p class='form-control bg-body-secondary border rounded'>" . htmlspecialchars($row['veiculo_atendimento']) . "</p>";
            echo "</div>";

            echo "</div>"; // row

            echo "</div>"; // mb-3


            echo "</div>"; // modal-body

            echo "</div>"; // modal-content
            echo "</div>"; // modal-dialog
            echo "</div>"; // modal fade

        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>Nenhum profissional encontrado</td></tr>";
    }
    $conn->close();
    ?>
    </tbody>
</table>



<script>
    function redirectToDetails(id) {
        window.location.href = 'dados.php?id=' + id;
    }
</script>





<script>
$(document).ready(function() {
    $('#tableBody').on('click', 'tr', function() {
        var dataNascimento = $(this).find('td:eq(0)').text();
        var nome = $(this).find('td:eq(1)').text();
        var assunto = $(this).find('td:eq(2)').text();
        var status = $(this).find('td:eq(3)').find('span').text();

        console.log("Data de Nascimento:", dataNascimento);
        console.log("Nome:", nome);
        console.log("Assunto Tratado:", assunto);
        console.log("Status:", status);

        $('#modalDataNascimento').text(dataNascimento);
        $('#modalNome').text(nome);
        $('#modalAssunto').text(assunto);
        $('#modalStatus').text(status);

        $('#detalhesModal').modal('show');
    });
});
</script>
<script>
    function applyFilters() {
        var filterData = document.getElementById('filterData').value;
        var filterNome = document.getElementById('filterNome').value.trim().toLowerCase();
        var filterAssunto = document.getElementById('filterAssunto').value.toLowerCase();
        var filterStatus = document.getElementById('filterStatus').value;
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
                var statusMatches = filterStatus === "" || txtValueStatus.toLowerCase() === filterStatus.toLowerCase();
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

