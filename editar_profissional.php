<?php
include("conexao.php"); // Inclui o arquivo de conexão com o banco de dados

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para obter os dados do atendimento e do profissional


// Estabelece a conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Chamada da função para obter os dados
$dados = dadosProf($id, $conn);

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
        $dados['data_nascimento_profissional'] = $row['data_nascimento_profissional']; 
    } else {
        echo "<p>Nenhum dado encontrado para o ID informado.</p>";
    }

    var_dump($dados); // Debugging para verificar os dados

    return $dados;
}




$conn->close(); 
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
    <?php include 'php/header.php'; ?>
    <main class="container_fluid d-flex justify-content-center align-items-center">
    <div class="form-group col-8 mt-5">
    <form id="formulario_index" method="post">

    <div class="row">
        <!-- Campos de dados do atendimento -->
        <div class="col-xl-2 col-md-6 mb-5">
            <div class="form-group">
                <label for="date">Data</label>
                <input type="date" class="form-control bg-white" id="date" name="date" value="<?= isset($dados['data_atendimento']) ? htmlspecialchars($dados['data_atendimento']) : "" ?>">
            </div>
        </div>
        <div class="col-xl-2 col-md-6 mb-5">
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control bg-white" id="status" name="status" value="<?= isset($dados['situacao_atendimento']) ? htmlspecialchars($dados['situacao_atendimento']) : "" ?>">
            </div>
        </div>
        <!-- Campos de dados do profissional -->
        <div class="border p-3">
            <div class="row">
                <h4><b>DADOS DO PROFISSIONAL</b></h4>
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control bg-white" id="cpf" name="cpf" value="<?= isset($dados['cpf_profissional']) ? htmlspecialchars($dados['cpf_profissional']) : "" ?>">
                    <!-- Campo oculto com o ID do profissional -->
                    <input type="hidden" name="id_profissional" value="<?= isset($dados['id_profissional']) ? $dados['id_profissional'] : "" ?>">
                </div>
                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control bg-white" id="nome" name="nome" maxlength="80" required value="<?= isset($dados['nome_profissional']) ? htmlspecialchars($dados['nome_profissional']) : "" ?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control bg-white" id="nascimento" name="nascimento" value="<?= isset($dados['data_nascimento_profissional']) ? htmlspecialchars($dados['data_nascimento_profissional']) : "" ?>">
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular">Celular 1</label>
                    <input type="tel" class="form-control bg-white" id="celular" name="celular" value="<?= isset($dados['telefone_profissional']) ? htmlspecialchars($dados['telefone_profissional']) : "" ?>">
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular2">Celular 2</label>
                    <input type="tel" class="form-control bg-white" id="celular2" name="celular2" value="<?= isset($dados['telefone2_profissional']) ? htmlspecialchars($dados['telefone2_profissional']) : "" ?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control bg-white" id="email" name="email" required value="<?= isset($dados['email_profissional']) ? htmlspecialchars($dados['email_profissional']) : "" ?>">
                </div>
                <div class="col-xl-4 col-md-6 mt-2">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control bg-white" id="endereco" name="endereco" placeholder="Digite o endereço completo" required value="<?= isset($dados['endereco_profissional']) ? htmlspecialchars($dados['endereco_profissional']) : "" ?>">
                </div>
                <div class="row">
                    <div class="col-xl-2 col-md-6 mt-2">
                        <label for="registro">CRM</label>
                        <input type="text" class="form-control bg-white" id="registro" name="registro" maxlength="6" required value="<?= isset($dados['registro_profissional']) ? htmlspecialchars($dados['registro_profissional']) : "" ?>">
                    </div>
                    <div class="col-xl-2 col-md-6 mt-2">
                        <div class="form-group">
                            <label for="orgao">Órgão</label>
                            <input type="text" class="form-control bg-white" id="orgao" name="orgao" maxlength="6" required value="<?= isset($dados['orgao_profissional']) ? htmlspecialchars($dados['orgao_profissional']) : "" ?>">
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 mt-2 mb-4">
                        <label for="especialidades">Especialidade(s)</label>
                        <input type="text" class="form-control bg-white" id="especialidades" name="especialidades" maxlength="12" required value="<?= isset($dados['especialidades_profissional']) ? htmlspecialchars($dados['especialidades_profissional']) : "" ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Intercepta o envio do formulário via AJAX
    $('#formulario_index').submit(function(event) {
        // Impede o envio padrão do formulário
        event.preventDefault();
        
        // Obtém os dados do formulário
        var formData = $(this).serialize();

        // Envia os dados via AJAX para o arquivo PHP de atualização
        $.ajax({
            type: 'POST',
            url: 'atualizar_profissional.php', // Caminho para o script PHP que processa a atualização
            data: formData,
            success: function(response) {
                // Aqui você pode lidar com a resposta do servidor
                alert('Dados atualizados com sucesso!');
                console.log(response); // Exibe a resposta do servidor no console (opcional)
            },
            error: function(xhr, status, error) {
                // Se houver erro no envio AJAX
                alert('Erro ao atualizar os dados. Verifique o console para mais detalhes.');
                console.log(xhr.responseText); // Exibe a mensagem de erro no console
            }
        });
    });
});
</script>


        </div>
    </div>
</form>


    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="js/indexenviar.js"></script>
</body>
</html>