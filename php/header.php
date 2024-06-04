<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ata de Encontro - HRG</title>
    <link rel="icon" href="view/img/Logobordab.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-border-hrg">
            <div class="container-fluid">
                <a class="navbar-brand" href="http://10.1.1.31:80/centralservicos/">
                    <img src="http://10.1.1.31:80/centralservicos/resources/img/central-servicos.png" alt="Central de Serviço" style="width: 160px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarCentral" aria-controls="navBarCentral" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navBarCentral">
                </div>
            </div>
        </nav>
        <div class="content-header shadow" style="border-bottom: solid 1px gray;">
            <div class="container-fluid">
                <div class="row py-1">
                    <div class="col ">
                        <p class="h1  text-light shadow" style="font-size: 25px;" ><b><?php echo isset($pageTitle) ? $pageTitle : "Relacionamento Médico  "; ?></b></p>
                        <p class="  text-light shadow  opacity-75" style="font-size: 20px;"><?php echo isset($pageTitle) ? $pageTitle : " Registro de atendimento "; ?></p>

                    </div>
                </div>
            </div>
        </div>
    </header>
   


