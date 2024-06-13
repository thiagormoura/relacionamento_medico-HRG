<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
    $sql_verificar_cpf = "SELECT cpf FROM inform_medicos WHERE cpf = ' $cpf'";
    $resultado = $conn->query($sql_verificar_cpf);

    if ($resultado->num_rows > 0) {
        echo "CPF exists";
    } else {
        echo "CPF does not exist";
    }
}

$conn->close();

?>
