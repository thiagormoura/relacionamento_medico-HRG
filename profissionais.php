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
                <button class="accordion-button shadow-sm text-white text-center" type="button" data-toggle="collapse" data-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #001f3f">
                    <i id="filter" class="fa-solid fa-filter mb-1"></i>
                    <h5>Filtro - Atendimentos</h5>
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample">
                <div class="accordion-body mt-4 mb-4">
                    <div class="row">
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="text" class="form-control" id="filterCPF" placeholder="CPF" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="text" class="form-control" id="filterNome" placeholder="Nome" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="text" class="form-control" id="filterEmail" placeholder="Email" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                        <div class="col-xl-3 col-sm-12 col-md-6">
                            <input type="text" class="form-control" id="filterTelefone" placeholder="Telefone" onkeydown="if(event.key==='Enter'){applyFilters();}">
                        </div>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" id="applyFilters" onclick="applyFilters()">Aplicar Filtros</button>
                        <style>
                            #applyFilters {
                                background-color: #001f3f;
                                border: 2px solid #001f3f;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <table class="table table-bordered table-striped">
    <thead class="thead-light">
        <tr>
            <th class="text-left">CPF</th>
            <th class="text-left">NOME</th>
            <th class="text-left">EMAIL</th>
            <th class="text-left">TELEFONE</th>
            <th class="text-center">EDITAR</th>
        </tr>
    </thead>
    <tbody id="tableBody">
    <?php
        $sql_profissionais = "
            SELECT p.id, p.nome, p.cpf, p.telefone, p.telefone2, p.email, a.id AS id_atendimento, a.data, a.assunto, a.situacao
            FROM profissionais p
            LEFT JOIN atendimento a ON p.id = a.profissional
            ORDER BY p.nome ASC"; // Ordena por nome em ordem alfabética

        $result_profissionais = $conn->query($sql_profissionais);

        if ($result_profissionais && $result_profissionais->num_rows > 0) {
            while ($row = $result_profissionais->fetch_assoc()) {
                $data_atendimento = new DateTime($row['data']);
                $data_atendimento_formatada = $data_atendimento->format('d/m/Y');
                $cpf = htmlspecialchars($row['cpf']);
                $telefone = htmlspecialchars($row['telefone']);
                $telefone2 = htmlspecialchars($row['telefone2']);
                $email = htmlspecialchars($row['email']);
                echo "<tr>";
                echo "<td class='text-left'>" . htmlspecialchars($cpf) . "</td>";
                echo "<td class='text-left'>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td class='text-left'>" . $email . "</td>";
                echo "<td class='text-left'>" . $telefone . "</td>";
                echo "<td class='text-center'>";
                echo "<button class='btn btn-primary' onclick='redirectToDetails({$row['id_atendimento']})' style='background-color: transparent; border: none;'>";
                echo "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' width='20' height='20' style='cursor: pointer;' onclick='editAtendimento({$row['id']})'>";
                echo "<path fill='#001f3f' d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/>";
                echo "</svg>";
                echo "</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Nenhum profissional encontrado</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>
<script>
    function redirectToDetails(id) {
        window.location.href = 'editar_profissional.php?id=' + id;
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
        var filterCPF = document.getElementById('filterCPF').value.trim().toLowerCase();
        var filterNome = document.getElementById('filterNome').value.trim().toLowerCase();
        var filterEmail = document.getElementById('filterEmail').value.trim().toLowerCase();
        var filterTelefone = document.getElementById('filterTelefone').value.trim().toLowerCase();
        var table = document.getElementById('tableBody');
        var tr = table.getElementsByTagName('tr');

        for (var i = 0; i < tr.length; i++) {
            var tdCPF = tr[i].getElementsByTagName('td')[0];
            var tdNome = tr[i].getElementsByTagName('td')[1];
            var tdEmail = tr[i].getElementsByTagName('td')[2];
            var tdTelefone = tr[i].getElementsByTagName('td')[3];

            if (tdCPF && tdNome && tdEmail && tdTelefone) {
                var txtValueCPF = tdCPF.textContent || tdCPF.innerText;
                var txtValueNome = tdNome.textContent || tdNome.innerText;
                var txtValueEmail = tdEmail.textContent || tdEmail.innerText;
                var txtValueTelefone = tdTelefone.textContent || tdTelefone.innerText;

                var cpfMatches = txtValueCPF.toLowerCase().indexOf(filterCPF) > -1;
                var nomeMatches = txtValueNome.toLowerCase().indexOf(filterNome) > -1;
                var emailMatches = txtValueEmail.toLowerCase().indexOf(filterEmail) > -1;
                var telefoneMatches = txtValueTelefone.toLowerCase().indexOf(filterTelefone) > -1;

                if (cpfMatches && nomeMatches && emailMatches && telefoneMatches) {
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

