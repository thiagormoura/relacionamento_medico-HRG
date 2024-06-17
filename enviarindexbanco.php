<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";


function enviarParaBanco($conn, $date, $situacaoatendimento, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento) {
    $sucesso = true;


    $sql_orgaos = "INSERT INTO orgaos (orgaos) VALUES ('$orgao')";
    if ($conn->query($sql_orgaos) !== TRUE) {
        echo "Erro ao inserir órgão na tabela orgaos: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
     
        $id_orgao = $conn->insert_id;
    }

    $sql_especialidade = "INSERT INTO especialidades (especialidades) VALUES ('$especialidade')";
    if ($conn->query($sql_especialidade) !== TRUE) {
        echo "Erro ao inserir especialidade na tabela especialidade: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
    
        $id_especialidade = $conn->insert_id;
    }

    $sql_medicos = "INSERT INTO profissionais (data_nascimento, cpf, email, endereco, telefone, telefone2, nome, tipo_atendimento) VALUES ('$nascimento', '$cpf', '$email', '$endereco', '$celular', '$celulardois', '$nome', '$tipo_atendimento')";
    if ($conn->query($sql_medicos) !== TRUE) {
        echo "Erro ao inserir dados do médico na tabela profissionais: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
    
        $id_profissional = $conn->insert_id;
    }

    $sql_atendimento = "INSERT INTO atendimento (assunto, descricao, acoes, profissional) VALUES ('$assunto', '$descricao', '$acoes', '$id_profissional')";
    if ($conn->query($sql_atendimento) !== TRUE) {
        echo "Erro ao inserir atendimento na tabela atendimento: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
    
        $id_atendimento = $conn->insert_id;
    }

    return $sucesso;
}


$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


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
$endereco = isset($_POST['endereco']) ? $_POST['endereco'] : null;
$especialidade = isset($_POST['especialidade']) ? $_POST['especialidade'] : null;
$assunto = isset($_POST['assunto']) ? $_POST['assunto'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$acoes = isset($_POST['acoes']) ? $_POST['acoes'] : null;
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;


if (enviarParaBanco($conn, $date, $situacaoatendimento, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento)) {
    echo "Dados enviados com sucesso para o banco de dados.";
} else {
    echo "Erro ao enviar dados para o banco de dados.";
}

$conn->close();

