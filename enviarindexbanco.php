<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";
// echo( "A situação de atendimento é:$situacaoatendimento");
function enviarParaBanco($conn, $date, $situacaoatendimento, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $especialidade, $descricao, $acoes, $cpf, $tipo_atendimento) {
    $sucesso = true;

    // Inserir dados na tabela orgaos
    $sql_orgaos = "INSERT INTO orgaos (orgaos) VALUES ('$orgao')";
    if ($conn->query($sql_orgaos) !== TRUE) {
        echo "Erro ao inserir órgão na tabela orgaos: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
        // Obter o ID do órgão inserido
        $id_orgao = $conn->insert_id;
    }

    // Inserir dados na tabela especialidade
    $sql_especialidade = "INSERT INTO especialidades (especialidades) VALUES ('$especialidade')";
    if ($conn->query($sql_especialidade) !== TRUE) {
        echo "Erro ao inserir especialidade na tabela especialidade: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
        // Obter o ID da especialidade inserida
        $id_especialidade = $conn->insert_id;
    }

    // Inserir dados na tabela acoes
    $sql_acoes = "INSERT INTO acoes (acoes) VALUES ('$acoes')";
    if ($conn->query($sql_acoes) !== TRUE) {
        echo "Erro ao inserir ações na tabela acoes: " . $conn->error . "<br>";
        $sucesso = false;
    }

   

// Inserir dados na tabela profissionais
$sql_medicos = "INSERT INTO profissionais (data_nascimento, cpf, email, telefone,telefone2, nome,tipo_atendimento ,situacao_atendimento ) VALUES ('$nascimento', '$cpf', '$email', '$celular','$celulardois', '$nome','$tipo_atendimento','$situacaoatendimento')";
if ($conn->query($sql_medicos) !== TRUE) {
    echo "Erro ao inserir dados do médico na tabela profissionais: " . $conn->error . "<br>";
    $sucesso = false;
} else {
    
}



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
$tipo_atendimento = isset($_POST['tipo_atendimento']) ? $_POST['tipo_atendimento'] : null;
$celulardois = isset($_POST['celulardois']) ? $_POST['celulardois'] : null;
$situacaoatendimento = isset($_POST['situacao_atendimento']) ? $_POST['situacao_atendimento'] : null;
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
if (enviarParaBanco($conn, $date, $situacaoatendimento, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $especialidade, $descricao, $acoes, $cpf, $tipo_atendimento)) {
    echo "Dados enviados com sucesso para o banco de dados.";
} else {
    echo "Erro ao enviar dados para o banco de dados.";
}

// Fechar conexão com o banco de dados
$conn->close();

?>
