<?php
header('Content-Type: application/json');

if (isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
    
    // Conecte ao seu banco de dados MySQL
    $conn = new mysqli("localhost", "root", "", "relacionamentomedico");

    // Verifique a conexão
    if ($conn->connect_error) {
        die(json_encode(['error' => "Conexão falhou: " . $conn->connect_error]));
    }

    // Prepare a consulta SQL
    $stmt = $conn->prepare("SELECT nome, DATE_FORMAT(data_nascimento, '%d/%m/%Y') as data_nascimento, telefone, telefone2, email,registro,especialidades ,orgao  FROM profissionais WHERE cpf = ?");
    if ($stmt === false) {
        die(json_encode(['error' => 'Erro na preparação da declaração: ' . $conn->error]));
    }
    
    // Vincule o CPF como parâmetro à declaração preparada
    $stmt->bind_param("s", $cpf);
    
    // Execute a consulta
    $stmt->execute();
    
    // Obtenha o resultado da consulta
    $result = $stmt->get_result();

    // Verifique se há resultados
    if ($result->num_rows > 0) {
        // Capture os dados
        $data = $result->fetch_assoc();
        
        // Retorne os dados encontrados no formato JSON
        echo json_encode(['exists' => true, 'data' => $data]);
    } else {
        // Caso não haja resultados, retorne 'exists' como false
        echo json_encode(['exists' => false]);
    }

    // Feche a declaração preparada e a conexão com o banco de dados
    $stmt->close();
    $conn->close();
} else {
    // Se o CPF não foi fornecido via POST, retorne um erro
    echo json_encode(['error' => 'CPF não fornecido']);
}
?>
