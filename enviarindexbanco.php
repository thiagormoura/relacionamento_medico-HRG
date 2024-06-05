<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Conecta ao banco de dados
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para verificar se um campo está vazio
function isEmpty($value) {
    return empty(trim($value));
}

$date = !isEmpty($_POST['date']) ? $_POST['date'] : null;
$status = !isEmpty($_POST['status']) ? $_POST['status'] : null;
$nome = !isEmpty($_POST['nome']) ? $_POST['nome'] : null;
$registro = !isEmpty($_POST['registro']) ? $_POST['registro'] : null;
$estado = !isEmpty($_POST['estado']) ? $_POST['estado'] : null;
$orgao = !isEmpty($_POST['orgao']) ? $_POST['orgao'] : null;
$celular = !isEmpty($_POST['celular']) ? $_POST['celular'] : null;
$nascimento = !isEmpty($_POST['nascimento']) ? $_POST['nascimento'] : null;
$email = !isEmpty($_POST['email']) ? $_POST['email'] : null;
$descricaoespecialidades = !isEmpty($_POST['descricaoespecialidades']) ? $_POST['descricaoespecialidades'] : null;
$descricao = !isEmpty($_POST['descricao']) ? $_POST['descricao'] : null;
$acoes = !isEmpty($_POST['acoes']) ? $_POST['acoes'] : null;
$cpf = !isEmpty($_POST['cpf']) ? $_POST['cpf'] : null;

if (isEmpty($acoes) || isEmpty($descricaoespecialidades) || isEmpty($estado) || isEmpty($orgao) ||
    isEmpty($nome) || isEmpty($celular) || isEmpty($email) || isEmpty($registro) || isEmpty($nascimento) || isEmpty($cpf)) {
    die("Erro: Todos os campos devem ser preenchidos.");
} else {
    // Extrair o estado e a sigla
    if (preg_match('/^([A-Z]{2})$/', $estado, $matches)) {
        $sigla = trim($matches[1]);
    } else {
        die("Erro: Sigla do estado inválida. A sigla do estado deve conter exatamente duas letras maiúsculas.");
    }

    // Verifica se o estado já existe
    $stmt = $conn->prepare("SELECT id FROM estados WHERE sigla = ?");
    $stmt->bind_param("s", $sigla);
    $stmt->execute();
    $stmt->bind_result($estado_id);
    $stmt->fetch();
    $stmt->close();

    if (empty($estado_id)) {
        die("Erro: Sigla do estado não encontrada no banco de dados.");
    }

    // Insere os dados nas outras tabelas
    $stmt = $conn->prepare("INSERT INTO acoes (acoes) VALUES (?)");
    $stmt->bind_param("s", $acoes);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO especialidades (especialidades) VALUES (?)");
    $stmt->bind_param("s", $descricaoespecialidades);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO descricao (descricao) VALUES (?)");
    $stmt->bind_param("s", $descricao);
    $stmt->execute();
    $stmt->close();

    // Insere os dados na tabela `inform_medicos`
    $stmt = $conn->prepare("INSERT INTO inform_medicos (nome, telefone, email, cpf, id_estado, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $celular, $email, $cpf, $estado_id, $nascimento);
    $stmt->execute();
    $stmt->close();


// Consulta para obter o ID do órgão com base no nome
$stmt = $conn->prepare("SELECT id FROM orgaos WHERE orgaos = ?");
$stmt->bind_param("s", $orgao);
$stmt->execute();
$stmt->bind_result($id_orgao);
$stmt->fetch();
$stmt->close();

// Verifica se o órgão já existe
if (empty($id_orgao)) {
    // Se o órgão não existir, insere-o na tabela de orgãos
    $stmt = $conn->prepare("INSERT INTO orgaos (orgao) VALUES (?)");
    $stmt->bind_param("s", $orgao);
    $stmt->execute();
    $id_orgao = $stmt->insert_id; // Obtém o ID do órgão inserido
    $stmt->close();
}



// // Consulta para obter o ID do médico recém-inserido
// $stmt = $conn->prepare("SELECT id FROM inform_medicos WHERE id = ?");
// $stmt->bind_param("s", $id_inform_medico);
// $stmt->execute();
// $stmt->bind_result($id_info_medico);
// $stmt->fetch();
// $stmt->close();


// // Insere os dados na tabela `inform_inst_medicos`
// $stmt = $conn->prepare("INSERT INTO inform_inst_medicos (id_info_medico, id_orgao) VALUES (?, ?)");
// $stmt->bind_param("ss", $id_info_medico, $id_orgao);
// $stmt->execute();
// $stmt->close();
    // Fecha a conexão
    $conn->close();
}

// Retorna uma mensagem de sucesso
echo "Registro inserido com sucesso!";
?>
