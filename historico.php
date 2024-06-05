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
                                    <select class="form-control" id="subjectFilter">
                                        <option value="">Todos os assuntos</option>
                                        <option value="Consulta">Consulta</option>
                                        <option value="Treinamento">Treinamento</option>
                                        <option value="Reunião">Reunião</option>
                                    </select>
                                </div>
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                    <select class="form-control" id="stateFilter">
                                        <option value="">Todos os estados</option>
                                        <option value="São Paulo">São Paulo</option>
                                        <option value="Rio de Janeiro">Rio de Janeiro</option>
                                        <option value="Minas Gerais">Minas Gerais</option>
                                    </select>
                                </div>
                                <div class="col-xl-3 col-sm-12 col-md-6">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">Todos os status</option>
                                        <option value="Aberto">Aberto</option>
                                        <option value="Fechado">Fechado</option>
                                    </select>
                                </div>

                            </div> <br> <button class="btn btn-primary" id="applyFilters">Aplicar Filtros</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Data</th>
                        <th>Assunto Tratado</th>
                        <th>Estado</th>
                        <th>Nome do Profissional</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2023-05-01</td>
                        <td>Consulta</td>
                        <td>São Paulo</td>

                        <td>João Silva</td>
                        <td>Fechado</td>
                    </tr>
                    <tr>
                        <td>2023-05-02</td>
                        <td>Treinamento</td>
                        <td>Rio de Janeiro</td>

                        <td>Maria Oliveira</td>
                        <td>Aberto</td>
                    </tr>
                    <tr>
                        <td>2023-05-03</td>
                        <td>Reunião</td>
                        <td>Minas Gerais</td>

                        <td>Carlos Souza</td>
                        <td>Fechado</td>
                    </tr>
                    <tr>
                        <td>2023-05-03</td>
                        <td>Reunião</td>
                        <td>Minas Gerais</td>

                        <td>Carlos Souza</td>
                        <td>Fechado</td>
                    </tr>
                    <tr>
                        <td>2023-05-03</td>
                        <td>Reunião</td>
                        <td>Minas Gerais</td>

                        <td>Carlos Souza</td>
                        <td>Fechado</td>
                    </tr>

                </tbody>
            </table>
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
