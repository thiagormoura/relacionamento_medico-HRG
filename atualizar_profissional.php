<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para atualizar os dados do profissional
function atualizarDadosProfissional($conn, $id_profissional, $cpf, $nome, $data_nascimento, $telefone, $telefone2, $email, $endereco, $registro, $orgao, $especialidades) {
    $stmt = $conn->prepare("UPDATE profissionais SET cpf = ?, nome = ?, data_nascimento = ?, telefone = ?, telefone2 = ?, email = ?, endereco = ?, registro = ?, orgao = ?, especialidades = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $cpf, $nome, $data_nascimento, $telefone, $telefone2, $email, $endereco, $registro, $orgao, $especialidades, $id_profissional);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Erro ao atualizar dados do profissional: " . $stmt->error;
        return false;
    }
}

// Conectar ao banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se os dados foram enviados via POST para atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_profissional = $_POST['id_profissional'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $data_nascimento = $_POST['nascimento'] ?? null;
    $telefone = $_POST['celular'] ?? null;
    $telefone2 = $_POST['celular2'] ?? null;
    $email = $_POST['email'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $registro = $_POST['registro'] ?? null;
    $orgao = $_POST['orgao'] ?? null;
    $especialidades = $_POST['especialidades'] ?? null;

    if ($id_profissional && $cpf !== null && $nome !== null && $data_nascimento !== null && $telefone !== null && $telefone2 !== null && $email !== null && $endereco !== null && $registro !== null && $orgao !== null && $especialidades !== null) {
        if (atualizarDadosProfissional($conn, $id_profissional, $cpf, $nome, $data_nascimento, $telefone, $telefone2, $email, $endereco, $registro, $orgao, $especialidades)) {
            echo "Dados do profissional atualizados com sucesso.";
        } else {
            echo "Erro ao atualizar dados do profissional.";
        }
    } else {
        echo "Todos os campos devem ser preenchidos.";
    }
}

// Fechar a conexão
$conn->close();

?>
