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
</style>
<body>
    <!-- Parte do header e nav -->
    <?php

    $pageTitle = "Histórico - Registros de Atendimento";
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




    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-light align-middle">
        <tr>
            <th class="text-left s-3">
                Data
                <a href="javascript:void(0);" id="toggleOrder" class="btn btn-link btn-sm" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="35" height="35">
                        <path fill="#001f3f" d="M7 14l5-5 5 5H7z"/>
                    </svg>
                </a>
            </th>
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
                    return 'badge badge-custom bg-success';
                case 'Concluido':
                    return 'badge badge-custom bg-primary';
                case 'Análise':
                    return 'badge badge-custom bg-warning';
                default:
                    return 'badge badge-custom bg-secondary';
            }
        }

        $orderBy = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $_SESSION['order'] = $orderBy;

        $sql_profissionais = "
            SELECT p.id, p.nome, p.cpf, p.telefone, p.telefone2, p.email, a.id AS id_atendimento, a.data, a.assunto, a.situacao
            FROM profissionais p
            LEFT JOIN atendimento a ON p.id = a.profissional
            ORDER BY a.data $orderBy, p.id DESC"; 
        
        $result_profissionais = $conn->query($sql_profissionais);
        $rows = [];
        if ($result_profissionais && $result_profissionais->num_rows > 0) {
            while ($row = $result_profissionais->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $conn->close();
        ?>
        </tbody>
    </table>

    <div class="pagination" id="pagination"></div>

    <script>
        const rows = <?php echo json_encode($rows); ?>;
        const rowsPerPage = 10;
        const tableBody = document.getElementById('tableBody');
        const pagination = document.getElementById('pagination');
        let currentPage = 1;

        function displayRows(startIndex, endIndex) {
            tableBody.innerHTML = '';
            for (let i = startIndex; i < endIndex; i++) {
                if (i >= rows.length) break;
                const row = rows[i];
                const dataAtendimento = new Date(row.data);
                const dataAtendimentoFormatada = dataAtendimento.toLocaleDateString('pt-BR');
                const cpf = row.cpf;
                const telefone = row.telefone;
                const telefone2 = row.telefone2;
                const email = row.email;
                const assunto = row.assunto ? row.assunto : "Nenhum assunto encontrado";
                const situacao = row.situacao;
                tableBody.innerHTML += `
                    <tr>
                        <td class='text-left'>${dataAtendimentoFormatada}</td>
                        <td class='text-left'>${row.nome}</td>
                        <td class='text-left'>${assunto}</td>
                        <td class='text-left'><span class='${getBadgeClass(situacao)}'>${situacao}</span></td>
                        <td class='text-center'>
                            <button class='btn btn-primary' onclick='redirectToDetails(${row.id_atendimento})' style='background-color: transparent; border: none;'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' width='20' height='20'>
                                    <path fill='#001f3f' d='M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z'/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            }
        }

        function setupPagination() {
            pagination.innerHTML = '';

            const prevButton = document.createElement('button');
            prevButton.innerHTML = '&larr;';
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener('click', () => {
                currentPage--;
                updatePagination();
            });
            pagination.appendChild(prevButton);

            const pageCount = Math.ceil(rows.length / rowsPerPage);
            for (let i = 1; i <= pageCount; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.classList.add('page');
                if (i === currentPage) {
                    button.classList.add('active');
                }
                button.addEventListener('click', () => {
                    currentPage = i;
                    updatePagination();
                });
                pagination.appendChild(button);
            }

            const nextButton = document.createElement('button');
            nextButton.innerHTML = '&rarr;';
            nextButton.disabled = currentPage === pageCount;
            nextButton.addEventListener('click', () => {
                currentPage++;
                updatePagination();
            });
            pagination.appendChild(nextButton);
        }

        function updatePagination() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            displayRows(start, end);
            setupPagination();
        }

        function getBadgeClass(situacao) {
            switch (situacao) {
                case 'Aberto':
                    return 'badge badge-custom bg-success';
                case 'Concluido':
                    return 'badge badge-custom bg-primary';
                case 'Análise':
                    return 'badge badge-custom bg-warning';
                default:
                    return 'badge badge-custom bg-secondary';
            }
        }
        
        function redirectToDetails(idAtendimento) {
            window.location.href = `detalhes.php?id=${idAtendimento}`;
        }

        updatePagination();
    </script>
    <br>
<script>
    function toggleOrder() {
        var currentOrder = '<?php echo $orderBy; ?>';
        var newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';
        var currentUrl = window.location.href;
        var newUrl;

        // Verifica se já há um parâmetro 'order' na URL atual
        if (currentUrl.indexOf('order=') !== -1) {
            // Atualiza o valor do parâmetro 'order' na URL
            newUrl = currentUrl.replace(/order=(ASC|DESC)/, 'order=' + newOrder);
        } else {
            // Adiciona o parâmetro 'order' à URL
            newUrl = currentUrl + (currentUrl.indexOf('?') !== -1 ? '&' : '?') + 'order=' + newOrder;
        }

        window.location.href = newUrl;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('toggleOrder').addEventListener('click', toggleOrder);
    });
</script>


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
