<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para atualizar dados no banco de dados
function atualizarProfissional($conn, $id_atendimento, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $cpf) {
    $sucesso = true;

    // Primeiro, obter o id_profissional da tabela atendimento
    $sql_obter_id_profissional = "SELECT profissional FROM atendimento WHERE id = $id_atendimento";
    $result = $conn->query($sql_obter_id_profissional);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_profissional = $row['profissional'];

        // Monta a consulta SQL para atualização na tabela profissionais
        $sql_profissionais = "UPDATE profissionais SET 
                                data_nascimento = '$nascimento',
                                cpf = '$cpf',
                                email = '$email',
                                telefone = '$celular',
                                telefone2 = '$celulardois',
                                nome = '$nome',
                                registro = '$registro',
                                especialidades = '$especialidade',
                                orgao = '$orgao',
                                endereco = '$endereco'
                              WHERE id = $id_profissional";

        // Exiba a consulta SQL para depuração
        echo "SQL para atualização na tabela profissionais: $sql_profissionais<br>";

        // Executa a consulta SQL
        if ($conn->query($sql_profissionais) !== TRUE) {
            echo "Erro ao atualizar dados na tabela profissionais: " . $conn->error . "<br>";
            $sucesso = false;
        }
    } else {
        echo "Não foi possível obter o id_profissional da tabela atendimento.";
        $sucesso = false;
    }

    return $sucesso;
}

// Conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi submetido via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados enviados via POST
    $id_atendimento = isset($_POST['id_atendimento']) ? $_POST['id_atendimento'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : null;
    $registro = isset($_POST['registro']) ? $_POST['registro'] : null;
    $orgao = isset($_POST['orgao']) ? $_POST['orgao'] : null;
    $celular = isset($_POST['celular']) ? $_POST['celular'] : null;
    $celulardois = isset($_POST['celular2']) ? $_POST['celular2'] : null;
    $nascimento = isset($_POST['nascimento']) ? $_POST['nascimento'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : null;
    $especialidade = isset($_POST['especialidades']) ? $_POST['especialidades'] : null;
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;

    // Executa a função de atualização de dados
    if (atualizarProfissional($conn, $id_atendimento, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $cpf)) {
        echo "Dados do profissional atualizados com sucesso.";
    } else {
        echo "Erro ao atualizar dados do profissional.";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
