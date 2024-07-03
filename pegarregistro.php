<?php
header('Content-Type: application/json');

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Conectar ao banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$mes = isset($_GET['mes']) ? $_GET['mes'] : date('Y-m'); // Padrão para o mês atual

// SQL para recuperar os dados dos atendimentos filtrados por mês
$sql = "SELECT situacao, COUNT(*) as count FROM atendimento WHERE DATE_FORMAT(data, '%Y-%m') = '$mes' GROUP BY situacao";
$result = $conn->query($sql);

$statuses = array(
    'Aberto' => 0,
    'Analize' => 0,
    'Concluido' => 0
);


while ($row = $result->fetch_assoc()) {
    $status = ucfirst(strtolower($row['situacao'])); // Garante que o status seja tratado corretamente
    if (array_key_exists($status, $statuses)) {
        $statuses[$status] = $row['count'];
    }
}


$conn->close();

echo json_encode($statuses);
?>