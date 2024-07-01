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
                   p.endereco, p.especialidades, p.orgao
            FROM atendimento a
            INNER JOIN profissionais p ON a.profissional = p.id
            WHERE a.id = $id";

$result = mysqli_query($conn, $sql);

// if($result && mysqli_num_row($result) > 0) {
//     while($row = mysqli_fetch_assoc($result)) {

//         $assuntonome = $row['assuntonome'];
//         // var_dump($assuntonome);
//     }

//     } else {
//         echo "Assunto não selecionados";
//     }


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
    $dados['especialidades_profissional'] = $row['especialidades'];
    $dados['orgao_profissional'] = $row['orgao'];
    $dados['data_nascimento_profissional'] = $row['data_nascimento_profissional']; // Correção aqui
    $dados['assuntonome'] = $row['assuntonome']; 

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
<style>
    .form-control{
font-size: 0.8rem;
}

.form-check-label{
font-size: 0.8rem;
}

label{
font-size: 0.8rem;
font-weight: 700;
}

.custom-textarea2{
resize: none; 
}

.custom-textarea {
width: 20em; 
resize: none; 
}

#assunto{
    resize: none; 
}

h4{
    font-family: sans-serif;
}
</style>
<body>



<body>
    <?php 
    $pageTitle = "Relacionamento Médico";
    $subTitle =  "Registro de atendimento" ; 
    include 'php/header.php'; 
    
    ?>


    <main class="container_fluid d-flex justify-content-center align-items-center">
        <div class="form-group col-8 mt-5">
            <form  id="formulario_index" method="post" action="/seu_script_php.php">
            <div class="row">
        <div class="col-xl-3 col-md-6 mb-5">
            <div class="form-group">
                <label for="date">Data</label>
                <input type="date" class="form-control bg-muted" id="date" name="date"  readonly value="<?= isset($dados['data_atendimento']) ? htmlspecialchars($dados['data_atendimento']) : "" ?>">
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-5">
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control bg-muted" id="status" name="status" readonly value="<?= isset($dados['situacao_atendimento']) ? htmlspecialchars($dados['situacao_atendimento']) : "" ?>">
            </div>
        </div>
        <div class="border p-3">
            <div class="row ">
                <h4><b>DADOS DO PROFISSIONAL</b></h4>
                <div class="col-xl-3 col-md-6 mt-2">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control bg-muted" id="cpf" name="cpf" placeholder="" disabled  value="<?= isset($dados['cpf_profissional']) ? htmlspecialchars($dados['cpf_profissional']) : "" ?>">
            </div>      
            <div class="col-xl-6  col-md-6 mt-2">
                <label for="nome">Nome</label>
                <input type="text" class="form-control bg-muted" id="nome" name="nome" maxlength="80" required readonly value="<?= isset($dados['nome_profissional']) ? htmlspecialchars($dados['nome_profissional']) : "" ?>">
            </div>
            <div class="col-xl-3 col-md-6 mt-2">
                <div class="form-group">
                    <label for="nascimento">Data de Nascimento</label>
                    <input type="date" class="form-control bg-muted" id="nascimento"  name="nascimento" readonly value="<?= isset($dados['data_nascimento_profissional']) ? htmlspecialchars($dados['data_nascimento_profissional']) : "" ?>">
                </div>
            </div>  
            <div class="col-xl-2 col-md-6 mt-2">
                <label for="celular">Celular 1</label>
                <input type="tel" class="form-control bg-muted" id="celular" name="celular" readonly value="<?= isset($dados['telefone_profissional']) ? htmlspecialchars($dados['telefone_profissional']) : "" ?>">
            </div> 
            <div class="col-xl-2 col-md-6 mt-2">
                <label for="celulardois">Celular 2</label>
                <input type="tel" class="form-control bg-muted" id="celular" name="celular" readonly value="<?= isset($dados['telefone2_profissional']) ? htmlspecialchars($dados['telefone2_profissional']) : "" ?>">
            </div>
            <div class="col-xl-4 col-md-6 mt-2">
                <label for="email">E-mail</label>
                <input type="email" class="form-control bg-muted" id="email" name="email"  required readonly value="<?= isset($dados['email_profissional']) ? htmlspecialchars($dados['email_profissional']) : "" ?>">
            </div>
            <div class="col-xl-4 col-md-6 mt-2">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control bg-muted" id="endereco" name="endereco" placeholder="Digite o endereço completo" required readonly value="<?= isset($dados['endereco_profissional']) ? htmlspecialchars($dados['endereco_profissional']) : "" ?>">
            </div>
            <div class="row">
            <div class="col-xl-3 col-md-6 mt-2">
                <label for="registro">CRM</label>
                <input type="text" class="form-control bg-muted" id="registro" name="registro" maxlength="6" required readonly value="<?= isset($dados['registro_profissional']) ? htmlspecialchars($dados['registro_profissional']) : "" ?>">
            </div>
            <div class="col-xl-3 col-md-6 mt-2">
                <div class="form-group">
                    <label for="orgao">Órgão</label>
                    <input type="text" class="form-control bg-muted" id="registro" name="registro" maxlength="6" required readonly value="<?= isset($dados['orgao_profissional']) ? htmlspecialchars($dados['orgao_profissional']) : "" ?>">
                </div>
            </div>
            </label>
            <div class="col-xl-6 col-md-6 mt-2 mb-4">
                <label for="especialidade">Especialidade(s)</label>
                <input type="text" class="form-control bg-muted" id="especialidade" name="especialidade" maxlength="12" required readonly value="<?= isset($dados['especialidades_profissional']) ? htmlspecialchars($dados['especialidades_profissional']) : "" ?>">
            </div> 
        </div>
    </div>
</div>
<br>
<div class="col-xl-7 col-md-6 mt-4 mb-5">
    <label for="orgao">Veículo de manifestação</label>
        <div class="form-group col-md-6">
            <div readonly><?= isset($dados['veiculo_atendimento']) ? htmlspecialchars($dados['veiculo_atendimento']) : "" ?></div>
        </div>
</div>

<div class="row">
    <label class="fs-4" for="">Assuntos Selecionados:</label>

    <input class="col-12 form-control p-3" value="<?= isset($dados['assuntonome']) ? htmlspecialchars($dados['assuntonome']) : "" ?>" type="text" disabled>

    <!-- <?php foreach ($dados['assuntonome'] as $assunto): ?>
        <div class="col-6">
            <input class="col-12 form-control p-3" value="<?= htmlspecialchars($assunto) ?>" type="text" disabled>
        </div>
    <?php endforeach; ?> -->


    
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
<div class="container"> 
    <title>Assuntos Tratados</title>
    <div class="col-12 ">        
    <div class="row mt-3" style="display: none;">
        <div class="col">
            <label for="selectedIds">IDs Selecionados:</label>
            <input type="text" id="selectedIds" class="form-control" readonly>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
 </div>
</div>     
    <div class="border p-3 mt-4">
        <h4><b>DESCRIÇÃO DO ATENDIMENTO</b></h4>
        <div class="row">
            <div class="col-xl-12 col-md-6 mt-3">
                <label for="assunto">Assunto</label>
                <textarea class="form-control bg-muted custom-textarea2" id="assuntoatendimento" name="assunto atendimento" rows="1" 
                maxlength="1000" readonly><?= isset($dados['assunto']) ? htmlspecialchars($dados['assunto']) : "" ?>
            </textarea>

        <div class="row ">
            <div class="col-xl-12 col-md-6 mt-3">
                <label for="descricao">Descrição</label>
                <textarea class="form-control bg-muted" id="descricao" name="descricao" rows="3" maxlength="1000" required readonly><?= isset($dados['descricao']) ? htmlspecialchars($dados['descricao']) : "" ?></textarea>
            </div>
            <div class="col-xl-12 col-md-6 mt-3 mb-3">
                <label for="acoes">Ações</label>
                <textarea class="form-control bg-muted" id="acoes" name="acoes" rows="3" maxlength="1000"  readonly><?= isset($dados['acoes']) ? htmlspecialchars($dados['acoes']) : "" ?></textarea>
            </div>
        </div>
    </div>
        </form>
        <br>
</main>
    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00', {reverse: true});
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="js/indexenviar.js"></script>
</body>
</html>