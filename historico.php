<?php
include("conexao.php");
$registrosPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $registrosPorPagina;

$sql = "SELECT 
DATE(a.data) as data,
a.id as id,
im.nome as nome_profissional,
a.situacao,
assunto.assunto


        FROM relacionamentomedico.atendimento AS a
    JOIN relacionamentomedico.profissionais AS im ON a.profissional = im.id     
  JOIN 
    relacionamentomedico.atendimento_has_assunto AS aha ON a.id = aha.atendimento
JOIN 
    relacionamentomedico.assunto AS assunto ON aha.assunto = assunto.id
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
    <div class="form-group col-10 mt-5 ">
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button shadow-sm text-white text-center" type="button" data-toggle="collapse" data-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #1c8f69 ">
                        <i id="filter" class="fa-solid fa-filter mb-1"></i>
                        <h5>Filtro - Atendimentos</h5>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample">
                    <div class="accordion-body mt-4 mb-4">
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
                                <select class="form-control" id="statusFilter">
                                    <option value="">Todos os status</option>
                                    <option value="Concluído">Concluído</option>
                                    <option value="Em andamento">Em andamento</option>
                                    <option value="Pendente">Pendente</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary" id="applyFilters">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
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
                <tbody id="dataTable">
                    <?php
                    // Verificando se a consulta retornou resultados
                    if ($result && $result->num_rows > 0) {
                        // Loop pelos resultados da consulta
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><a href='informacoes.php?id=". $row["id"]. "'>" . $row["data"] . "</a></td>";
                            echo "<td>" . $row['nome_profissional'] . "</td>";
                            echo "<td>" . $row['assunto'] . "</td>"; // Nome da coluna do assunto
                            echo "<td>" . $row['situacao'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhum resultado encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php if ($paginaAtual > 1) : ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo ($paginaAtual - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else : ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
         
            $inicio = max(1, $paginaAtual - 1);
            $fim = min($totalPaginas, $paginaAtual + 1);
            if ($inicio > 1) {
                echo '<li class="page-item"><a class="page-link" href="?pagina=1">1</a></li>';
                if ($inicio > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            for ($i = $inicio; $i <= $fim; $i++) {
                echo '<li class="page-item ' . ($paginaAtual == $i ? 'active' : '') . '">';
                echo '<a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>';
                echo '</li>';
            }
            if ($fim < $totalPaginas) {
                if ($fim < $totalPaginas - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $totalPaginas . '">' . $totalPaginas . '</a></li>';
            }
            ?>
            <?php if ($paginaAtual < $totalPaginas) : ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo ($paginaAtual + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else : ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
        </div>
    </div>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function applyFilters() {
            var dateFilter = $('#dateFilter').val();
            var inputprofissional = $('#inputprofissional').val().toLowerCase();
            var subjectFilter = $('#subjectFilter').val();
            var statusFilter = $('#statusFilter').val();
            $('#dataTable tr').each(function() {
                var date = $(this).find('td:eq(0)').text();
                var profissional = $(this).find('td:eq(1)').text().toLowerCase();
                var assunto = $(this).find('td:eq(2)').text();
                var status = $(this).find('td:eq(3)').text();
                var dateMatch = dateFilter === '' || date === dateFilter;
                var profissionalMatch = inputprofissional === '' || profissional.includes(inputprofissional);
                var subjectMatch = subjectFilter === '' || assunto === subjectFilter;
                var statusMatch = statusFilter === '' || status === statusFilter;
                if (dateMatch && profissionalMatch && subjectMatch && statusMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
 
        $('#applyFilters').on('click', function() {
            applyFilters();
        });
    });
    
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
</script>
</script>
</body>
</html>