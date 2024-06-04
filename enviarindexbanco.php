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


// echo ($date);
// echo($status);
// echo ($nome);
// echo($registro);
// echo ($estado);
// echo($orgao);
// echo ($celular);
// echo($nascimento);
// echo ($email);
// echo($descricaoespecialidades);
// echo ($descricao);
// echo($acoes);

if (isEmpty($acoes) || isEmpty($descricaoespecialidades) || isEmpty($estado) || isEmpty($orgao) ||
    isEmpty($nome) || isEmpty($celular) || isEmpty($email) || isEmpty($registro) || isEmpty($nascimento)) {
    die("Erro: Todos os campos devem ser preenchidos.");
}
else
{



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


// Fecha a conexão
$conn->close();
};
// Retorna uma mensagem de sucesso
// echo "Registro inserido com sucesso!";
?>
