<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para verificar se um campo está vazio
function isEmpty($value) {
    return empty(trim($value));
}

// Função para inserir médico
function insertMedico($conn, $nome, $celular, $email, $cpf, $estado_id, $nascimento) {
    $stmt = $conn->prepare("INSERT INTO inform_medicos (nome, telefone, email, cpf, id_estado, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $celular, $email, $cpf, $estado_id, $nascimento);
    $stmt->execute();
    return $stmt->insert_id;
}

// Função para inserir órgão
function insertOrgao($conn, $orgao) {
    $stmt = $conn->prepare("INSERT INTO orgaos (orgaos) VALUES (?)");
    $stmt->bind_param("s", $orgao);
    $stmt->execute();
    return $stmt->insert_id;
}

// Função para inserir especialidade
function insertEspecialidade($conn, $descricaoespecialidades) {
    $stmt = $conn->prepare("INSERT INTO especialidades (especialidades) VALUES (?)");
    $stmt->bind_param("s", $descricaoespecialidades);
    $stmt->execute();
    return $stmt->insert_id;
}

// Função para inserir informação do médico
function insertInformacaoMedico($conn, $id_orgao, $infom_id, $especi_id) {
    $stmt = $conn->prepare("INSERT INTO inform_inst_medicos (id_orgao, id_info_medico, id_especialidade) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id_orgao, $infom_id, $especi_id);
    $stmt->execute();
    return $stmt->insert_id;
}

// Função para inserir descrição
function insertDescricao($conn, $descricao, $last_id) {
    $stmt = $conn->prepare("INSERT INTO descricao (descricao, id_inform_inst_medicos) VALUES (?, ?)");
    $stmt->bind_param("si", $descricao, $last_id);
    $stmt->execute();
}

// Função para inserir ações
function insertAcoes($conn, $acoes, $last_id) {
    $stmt = $conn->prepare("INSERT INTO acoes (acoes, id_inform_inst_medicos) VALUES (?, ?)");
    $stmt->bind_param("si", $acoes, $last_id);
    $stmt->execute();
}

// Verificação dos dados recebidos
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

// Verificação de campos vazios
if (isEmpty($acoes) || isEmpty($descricaoespecialidades) || isEmpty($estado) || isEmpty($orgao) ||
    isEmpty($nome) || isEmpty($celular) || isEmpty($email) || isEmpty($registro) || isEmpty($nascimento) || isEmpty($cpf)) {
    die("Erro: Todos os campos devem ser preenchidos.");
} else {
    // Verificação do estado
    if (!preg_match('/^([A-Z]{2})$/', $estado, $matches)) {
        die("Erro: Sigla do estado inválida. A sigla do estado deve conter exatamente duas letras maiúsculas.");
    }

    // Obtenção do ID do estado
    $sigla = trim($matches[1]);
    $stmt = $conn->prepare("SELECT id FROM estados WHERE sigla = ?");
    $stmt->bind_param("s", $sigla);
    $stmt->execute();
    $stmt->bind_result($estado_id);
    $stmt->fetch();
    $stmt->close();

    if (empty($estado_id)) {
        die("Erro: Sigla do estado não encontrada no banco de dados.");
    }

    // Inserção de médico
    $infom_id = insertMedico($conn, $nome, $celular, $email, $cpf, $estado_id, $nascimento);

    // Inserção de órgão
    $id_orgao = insertOrgao($conn, $orgao);

    // Inserção de especialidade
    $especi_id = insertEspecialidade($conn, $descricaoespecialidades);

    // Inserção de informação do médico
    $last_id = insertInformacaoMedico($conn, $id_orgao, $infom_id, $especi_id);

    // Inserção de descrição
    insertDescricao($conn, $descricao, $last_id);

    // Inserção de ações
    insertAcoes($conn, $acoes, $last_id);
}

// Fechamento da conexão
$conn->close();

// Mensagem de sucesso
echo "Registro inserido com sucesso!";
?>
