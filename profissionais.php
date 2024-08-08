<?php
include("conexao.php");
$registrosPorPagina = 20;
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
    <title>Registro de profissionais - Relacionamento Médico</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
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
    <?php
    $pageTitle = "Listagem de Profissionais";
    include 'php/header.php';
    ?>
<main class="container-fluid d-flex justify-content-center align-items-center">
<div class="form-group col-10 mt-5">
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button shadow-sm text-white text-center" type="button" data-toggle="collapse" data-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #001f3f">
                    <i id="filter" class="fa-solid fa-filter mb-1"></i>
                    <h5>Filtro de listagem</h5>
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





    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-light">
            <tr>
                <th class="text-left">CPF</th>
                <th class="text-left">Nome do profissional</th>
                <th class="text-left">Email</th>
                <th class="text-left">Telefone</th>
                <th class="text-center">Editar</th>
            </tr>
        </thead>
        <tbody id="tableBody">
        <?php
            $sql_profissionais = "
                SELECT p.id, p.nome, p.cpf, p.telefone, p.telefone2, p.email, a.id AS id_atendimento, a.data, a.assunto, a.situacao
                FROM profissionais p
                LEFT JOIN atendimento a ON p.id = a.profissional
                ORDER BY p.nome ASC";
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
    const rowsPerPage = 20;
    const tableBody = document.getElementById('tableBody');
    const pagination = document.getElementById('pagination');
    let currentPage = 1;

    function displayRows(data, startIndex, endIndex) {
        tableBody.innerHTML = '';
        for (let i = startIndex; i < endIndex; i++) {
            if (i >= data.length) break;
            const row = data[i];
            const dataAtendimento = new Date(row.data);
            const dataAtendimentoFormatada = dataAtendimento.toLocaleDateString('pt-BR');
            const cpf = row.cpf;
            const telefone = row.telefone;
            const telefone2 = row.telefone2;
            const email = row.email;
            tableBody.innerHTML += `
                <tr>
                    <td class='text-left'>${cpf}</td>
                    <td class='text-left'>${row.nome}</td>
                    <td class='text-left'>${email}</td>
                    <td class='text-left'>${telefone}</td>
                    <td class='text-center'>
                        <button class='btn btn-primary' onclick='redirectToDetails(${row.id_atendimento})' style='background-color: transparent; border: none;'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' width='20' height='20' style='cursor: pointer;' onclick='editAtendimento(${row.id})'>
                                <path fill='#001f3f' d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        }
    }

    function setupPagination(data) {
    pagination.innerHTML = '';

    // Botão Anterior
    const prevButton = document.createElement('button');
    prevButton.innerHTML = '&larr;';
    prevButton.disabled = currentPage === 1;
    prevButton.title = 'Anterior'; // Tooltip para o botão Anterior
    prevButton.addEventListener('click', () => {
        currentPage--;
        updatePagination(data);
    });
    pagination.appendChild(prevButton);

    // Botões de Página
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

    // Botão Próximo
    const nextButton = document.createElement('button');
    nextButton.innerHTML = '&rarr;';
    nextButton.disabled = currentPage === pageCount;
    nextButton.title = 'Próximo'; // Tooltip para o botão Próximo
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
        window.location.href = `editar_profissional.php?id=${idAtendimento}`;
    }

    let filterCPF = '';
    let filterNome = '';
    let filterEmail = '';
    let filterTelefone = '';

    function applyFilters() {
        filterCPF = document.getElementById('filterCPF').value.trim().toLowerCase();
        filterNome = document.getElementById('filterNome').value.trim().toLowerCase();
        filterEmail = document.getElementById('filterEmail').value.trim().toLowerCase();
        filterTelefone = document.getElementById('filterTelefone').value.trim().toLowerCase();

        const filteredData = rows.filter(row => {
            const cpfMatches = row.cpf.toLowerCase().includes(filterCPF);
            const nomeMatches = row.nome.toLowerCase().includes(filterNome);
            const emailMatches = row.email.toLowerCase().includes(filterEmail);
            const telefoneMatches = row.telefone.toLowerCase().includes(filterTelefone);

            return cpfMatches && nomeMatches && emailMatches && telefoneMatches;
        });

        currentPage = 1; // Resetar para a primeira página ao filtrar
        displayRows(filteredData, 0, filteredData.length); // Mostrar todos os dados filtrados
        setupPagination(filteredData); // Atualizar a paginação para refletir o novo conjunto de dados
    }

    document.getElementById('applyFilters').addEventListener('click', applyFilters);

    // Inicializar a exibição e paginação com todos os dados
    updatePagination(rows);
</script>




</script>


</div>   
</div>  
    </main>
    <br>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

