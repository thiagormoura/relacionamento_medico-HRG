<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

function enviarParaBanco($conn, $date, $situacaoAtendimento, $nome, $registro, $orgao, $celular, $nascimento, $email, $especialidade, $descricao, $acoes, $cpf) {
    // Inicializa uma variável para verificar se todas as inserções foram bem-sucedidas
    $sucesso = true;

    // Inserir dados na tabela inform_medicos
    $sql_medicos = "INSERT INTO inform_medicos (data_nascimento, cpf, email, telefone, nome) VALUES ('$nascimento', '$cpf', '$email', '$celular', '$nome')";
    if ($conn->query($sql_medicos) !== TRUE) {
        echo "Erro ao inserir dados do médico na tabela inform_medicos: " . $conn->error;
        $sucesso = false;
    }else {
        // Obter o ID do médico inserido
        $id_medico = $conn->insert_id;
    }
    

    // Inserir dados na tabela acoes
    $sql_acoes = "INSERT INTO acoes (acoes) VALUES ('$acoes')";
    if ($conn->query($sql_acoes) !== TRUE) {
        echo "Erro ao inserir ações na tabela acoes: " . $conn->error;
        $sucesso = false;
    }

    // Inserir dados na tabela descricao
    $sql_descricao = "INSERT INTO descricao (descricao) VALUES ('$descricao')";
    if ($conn->query($sql_descricao) !== TRUE) {
        echo "Erro ao inserir descrição na tabela descricao: " . $conn->error;
        $sucesso = false;
    }

    // Inserir dados na tabela orgaos
    $sql_orgaos = "INSERT INTO orgaos (orgaos) VALUES ('$orgao')";
    if ($conn->query($sql_orgaos) !== TRUE) {
        echo "Erro ao inserir órgão na tabela orgaos: " . $conn->error;
        $sucesso = false;
    }
    else {
        // Obter o ID do órgão inserido
        $id_orgao = $conn->insert_id;
    }

// Inserir dados na tabela especialidade
$sql_especialidade = "INSERT INTO especialidades (especialidades) VALUES ('$especialidade')";
if ($conn->query($sql_especialidade) !== TRUE) {
    echo "Erro ao inserir especialidade na tabela especialidade: " . $conn->error;
    $sucesso = false;
}
else {
    // Obter o ID da especialidade inserida
    $id_especialidade = $conn->insert_id;
}



$sql_inform_inst_medicos = "INSERT INTO inform_inst_medicos (id_info_medico, id_orgao, id_especialidade) VALUES ('$id_medico', '$id_orgao', '$id_especialidade')";
    if ($conn->query($sql_inform_inst_medicos) !== TRUE) {
        echo "Erro ao inserir dados na tabela inform_inst_medicos: " . $conn->error;
        $sucesso = false;
    }
    // Retorna verdadeiro se todas as inserções foram bem-sucedidas
    return $sucesso;
}


// Conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificação dos dados recebidos
$date = isset($_POST['date']) ? $_POST['date'] : null;
$situacaoAtendimento = isset($_POST['situacaoAtendimento']) ? $_POST['situacaoAtendimento'] : null;
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$registro = isset($_POST['registro']) ? $_POST['registro'] : null;
$orgao = isset($_POST['orgao']) ? $_POST['orgao'] : null;
$celular = isset($_POST['celular']) ? $_POST['celular'] : null;
$nascimento = isset($_POST['nascimento']) ? $_POST['nascimento'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$especialidade = isset($_POST['especialidade']) ? $_POST['especialidade'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$acoes = isset($_POST['acoes']) ? $_POST['acoes'] : null;
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;

// Chamada da função para enviar os dados para o banco
if (enviarParaBanco($conn, $date, $situacaoAtendimento, $nome, $registro, $orgao, $celular, $nascimento, $email, $especialidade, $descricao, $acoes, $cpf)) {
    echo "Dados enviados com sucesso para o banco de dados.";
} else {
    echo "Erro ao enviar dados para o banco de dados.";
}

// Fechar conexão com o banco de dados
$conn->close();

?>
