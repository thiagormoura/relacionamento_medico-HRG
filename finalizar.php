<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para atualizar a situação do atendimento
function finalizarAtendimento($conn, $id_atendimento) {
    $stmt = $conn->prepare("UPDATE atendimento SET situacao = 'Finalizado' WHERE id = ?");
    $stmt->bind_param("i", $id_atendimento);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Erro ao atualizar a situação do atendimento: " . $stmt->error;
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
    $id_atendimento = $_POST['id_atendimento'] ?? null;

    if ($id_atendimento !== null) {
        if (finalizarAtendimento($conn, $id_atendimento)) {
            echo "Situação do atendimento atualizada para 'fechado' com sucesso.";
        } else {
            echo "Erro ao atualizar a situação do atendimento.";
        }
    } else {
        echo "ID do atendimento não fornecido.";
    }
}

// Fechar a conexão
$conn->close();

?>
