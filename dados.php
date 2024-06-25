<?php
include("conexao.php"); // Inclui o arquivo de conexão com o banco de dados

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para obter os dados do atendimento e do profissional
function dadosProf($id, $conn) {
    $dados = array();

    $sql = "SELECT a.data AS data_atendimento, a.situacao AS situacao_atendimento, a.assunto, a.descricao, a.acoes, a.veiculo_atendimento,
                   p.nome AS nome_profissional, p.data_nascimento AS data_nascimento_profissional, p.cpf, p.telefone AS telefone_profissional, p.telefone2, p.registro, p.email AS email_profissional, 
                   p.endereco, p.estados, p.especialidades, p.orgao
            FROM atendimento a
            INNER JOIN profissionais p ON a.profissional = p.id
            WHERE a.id = $id";

$result = $conn->query($sql);

if ($result === false) {
    echo "Erro na consulta: " . $conn->error;
} elseif ($result->num_rows > 0) {
    // Obtém os dados
    $row = $result->fetch_assoc();
    $dados['data_atendimento'] = $row['data_atendimento'];
    $dados['situacao_atendimento'] = $row['situacao_atendimento'];
    $dados['assunto'] = $row['assunto'];
    $dados['descricao'] = $row['descricao'];
    $dados['acoes'] = $row['acoes'];
    $dados['veiculo_atendimento'] = $row['veiculo_atendimento'];
    $dados['nome_profissional'] = $row['nome_profissional'];
    $dados['cpf_profissional'] = $row['cpf'];
    $dados['telefone_profissional'] = $row['telefone_profissional'];
    $dados['telefone2_profissional'] = $row['telefone2'];
    $dados['registro_profissional'] = $row['registro'];
    $dados['email_profissional'] = $row['email_profissional'];
    $dados['endereco_profissional'] = $row['endereco'];
    $dados['estados_profissional'] = $row['estados'];
    $dados['especialidades_profissional'] = $row['especialidades'];
    $dados['orgao_profissional'] = $row['orgao'];
    $dados['data_nascimento_profissional'] = $row['data_nascimento_profissional']; // Correção aqui
} else {
    echo "<p>Nenhum dado encontrado para o ID informado.</p>";
}

return $dados;

}

// Estabelece a conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Chamada da função para obter os dados
$dados = dadosProf($id, $conn);

$conn->close(); // Fecha a conexão com o banco de dados
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes de Atendimento - Relacionamento Médico</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
    <style>
        .custom-textarea {
        height: 100px;
        overflow-y: auto;
        }
    </style>
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
                    <div class="titulo">
                        <p class="h1  text-light shadow" style="font-size: 25px;" > <?php echo isset($pageTitle) ? $pageTitle : "<b><p class='rm'> Relacionamento Médico </p></b>  <p class='ra'> Dados do atendimento <p/>"; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </header>
<br>
    <main class="container">
        <div class="border p-3">
        <div class="row">
            <h4><b>DADOS DO PROFISSIONAL</b></h4>
        </div>
        <div class="row">
            <div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="cpf"><b>CPF:</b></label>
                        <div class="form-control" readonly><?= isset($dados['cpf_profissional']) ? htmlspecialchars($dados['cpf_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="nome"><b><i>Nome:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['nome_profissional']) ? htmlspecialchars($dados['nome_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="nascimento"><b><i>Data de Nascimento:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['data_nascimento_profissional']) ? htmlspecialchars($dados['data_nascimento_profissional']) : "" ?></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="celular"><b><i>Celular 1:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['telefone_profissional']) ? htmlspecialchars($dados['telefone_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="celulardois"><b><i>Celular 2:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['telefone2_profissional']) ? htmlspecialchars($dados['telefone2_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="email"><b><i>E-mail:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['email_profissional']) ? htmlspecialchars($dados['email_profissional']) : "" ?></div>
                    </div>
                </div>
            </div>

            <div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="endereco"><b><i>Endereço:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['endereco_profissional']) ? htmlspecialchars($dados['endereco_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="registro"><b><i>CRM:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['registro_profissional']) ? htmlspecialchars($dados['registro_profissional']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="orgao"><b><i>Órgão:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['orgao_profissional']) ? htmlspecialchars($dados['orgao_profissional']) : "" ?></div>
                    </div>
            
                    <div class="form-group col-md-4">
                        <label for="especialidade"><b><i>Especialidade(s):</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['especialidades_profissional']) ? htmlspecialchars($dados['especialidades_profissional']) : "" ?></div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
    <br>
    <div class="border p-3">
        <div class="row">
            <h4><b>DADOS DO ATENDIMENTO</b></h4>
        </div>
        <div class="row">
            <div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="data_atendimento"><b>Data do Atendimento:</b></label>
                        <div class="form-control" readonly><?= isset($dados['data_atendimento']) ? htmlspecialchars($dados['data_atendimento']) : "" ?></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="situacao_atendimento"><b><i>Situação do Atendimento:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['situacao_atendimento']) ? htmlspecialchars($dados['situacao_atendimento']) : "" ?></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="assunto"><b><i>Assunto:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['assunto']) ? htmlspecialchars($dados['assunto']) : "" ?></div>
                    </div>
                </div>
                <div class="row">
                <div class="form-group col-md-12">
                    <label for="descricao"><b><i>Descrição:</i></b></label>
                    <div class="form-control custom-textarea" readonly><?= isset($dados['descricao']) ? htmlspecialchars($dados['descricao']) : "" ?></div>
                </div>
                    <div class="form-group col-md-6">
                        <label for="acoes"><b><i>Ações:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['acoes']) ? htmlspecialchars($dados['acoes']) : "" ?></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="veiculo_atendimento"><b><i>Veículo de Atendimento:</i></b></label>
                        <div class="form-control" readonly><?= isset($dados['veiculo_atendimento']) ? htmlspecialchars($dados['veiculo_atendimento']) : "" ?></div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
</main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>


