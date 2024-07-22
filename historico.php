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
            <th class="text-center">Finalizar</th>
        </tr>
    </thead>
        <tbody id="tableBody">
        <?php
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
    <br>
    <div class="pagination" id="pagination"></div>
    <script>
        const rows = <?php echo json_encode($rows); ?>;
        const rowsPerPage = 10;
        const tableBody = document.getElementById('tableBody');
        const pagination = document.getElementById('pagination');
        const toggleOrderButton = document.getElementById('toggleOrder');
        let currentPage = 1;
        let ascending = true;

        function displayRows(data, startIndex, endIndex) {
    tableBody.innerHTML = '';
    for (let i = startIndex; i < endIndex; i++) {
        if (i >= data.length) break;
        const row = data[i];
        const dataAtendimento = new Date(row.data);
        const dataAtendimentoFormatada = dataAtendimento.toLocaleDateString('pt-BR');
        const assunto = row.assunto ? row.assunto : "Nenhum assunto encontrado";
        const situacao = row.situacao;
        const situacaoClass = situacao === 'Aberto' ? 'text-success' : 'text-danger';
        let buttonHTML = '';
        if (situacao === 'Aberto') {
            buttonHTML = `
                <button id='finalizar-${row.id_atendimento}' class='btn btn-success' onclick='finalizeTask(${row.id_atendimento})' style='background-color: transparent; border: none;' title='Finalizar'>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width='20' height='20'>
                        <path fill='#1E3050' d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                    </svg>
                </button>
            `;
        } else {
            buttonHTML = `
                <button id='finalizar-${row.id_atendimento}' class='btn btn-success' style='background-color: transparent; border: none;' title='Finalizado'>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width='20' height='20'>
                        <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path fill='#03870C' d="M342.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 178.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l80 80c12.5 12.5 32.8 12.5 45.3 0l160-160zm96 128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 402.7 54.6 297.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l256-256z"/>
                    </svg>
                </button>
            `;
        }

        tableBody.innerHTML += `
            <tr>
                <td class='text-left'>${dataAtendimentoFormatada}</td>
                <td class='text-left'>${row.nome}</td>
                <td class='text-left'>${assunto}</td>
                <td class='text-left'><span class='badge ${situacao === 'Aberto' ? 'bg-success' : 'bg-danger'}'>${situacao}</span></td>
                <td class='text-center'>
                    <button class='btn btn-primary' onclick='redirectToDetails(${row.id_atendimento})' style='background-color: transparent; border: none;' title='Visualizar'>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' width='20' height='20'>
                            <path fill='#001f3f' d='M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z'/>
                        </svg>
                    </button>
                </td>
                <td class='text-center'>
                    ${buttonHTML}
                </td>
            </tr>
        `;
    }
}




function finalizeTask(id) {
    const button = document.getElementById(`finalizar-${id}`);

    Swal.fire({
        title: 'Você realmente deseja finalizar este atendimento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            button.disabled = true;
            fetch('finalizar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id_atendimento': id
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
            });
        }
    });
}
        function setupPagination(data) {
            pagination.innerHTML = '';

            const prevButton = document.createElement('button');
            prevButton.innerHTML = '&larr;';
            prevButton.disabled = currentPage === 1;
            prevButton.title = 'Anterior';
            prevButton.addEventListener('click', () => {
                currentPage--;
                updatePagination(data);
            });
            pagination.appendChild(prevButton);

            const pageCount = Math.ceil(data.length / rowsPerPage);

            for (let i = 1; i <= pageCount; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.classList.add('page');
                if (i === currentPage) {
                    button.classList.add('active');
                }
                button.addEventListener('click', () => {
                    currentPage = i;
                    updatePagination(data);
                });
                pagination.appendChild(button);
            }

            const nextButton = document.createElement('button');
            nextButton.innerHTML = '&rarr;';
            nextButton.disabled = currentPage === pageCount;
            nextButton.title = 'Próximo';
            nextButton.addEventListener('click', () => {
                currentPage++;
                updatePagination(data);
            });
            pagination.appendChild(nextButton);
        }

        function updatePagination(data) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            displayRows(data, start, end);
            setupPagination(data);
        }

        function redirectToDetails(idAtendimento) {
            window.location.href = `dados.php?id=${idAtendimento}`;
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

        function applyFilters() {
            filterData = document.getElementById('filterData').value.trim();
            filterNome = document.getElementById('filterNome').value.trim().toLowerCase();
            filterAssunto = document.getElementById('filterAssunto').value.trim().toLowerCase();
            filterStatus = document.getElementById('filterStatus').value.trim().toLowerCase();

            const filteredData = rows.filter(row => {
                const dataMatches = filterData === '' || new Date(row.data).toLocaleDateString('pt-BR') === filterData;
                const nomeMatches = row.nome.toLowerCase().includes(filterNome);
                const assuntoMatches = row.assunto.toLowerCase().includes(filterAssunto);
                const statusMatches = filterStatus === '' || row.situacao.toLowerCase() === filterStatus;

                return dataMatches && nomeMatches && assuntoMatches && statusMatches;
            });

            currentPage = 1;
            updatePagination(filteredData);
        }

        document.getElementById('applyFilters').addEventListener('click', applyFilters);

        toggleOrderButton.addEventListener('click', function() {
            ascending = !ascending;
            const sortedData = rows.sort((rowA, rowB) => {
                const dateA = new Date(rowA.data);
                const dateB = new Date(rowB.data);
                return ascending ? dateA - dateB : dateB - dateA;
            });
            currentPage = 1;
            updatePagination(sortedData);
            toggleSortIcon(ascending);
        });

        function toggleSortIcon(ascending) {
            const icon = toggleOrderButton.querySelector('svg path');
            if (ascending) {
                icon.setAttribute('d', 'M7 14l5-5 5 5H7z');
            } else {
                icon.setAttribute('d', 'M7 10l5 5 5-5H7z');
            }
        }

        updatePagination(rows);
    </script>
</div>   
</div>  
    </main>
<br>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


</body>
</html>
