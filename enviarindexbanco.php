<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para enviar dados para o banco de dados
function enviarParaBanco($conn, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento, $veiculoselecionado, $assuntoatendimento, $assuntosselecionados_array) {
    $sucesso = true;

    // Inserir dados na tabela profissionais
    $stmt_profissionais = $conn->prepare("INSERT INTO profissionais (data_nascimento, cpf, email, telefone, telefone2, nome, registro, especialidades, orgao, endereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_profissionais->bind_param("ssssssssss", $nascimento, $cpf, $email, $celular, $celulardois, $nome, $registro, $especialidade, $orgao, $endereco);

    if (!$stmt_profissionais->execute()) {
        echo "Erro ao inserir dados na tabela profissionais: " . $stmt_profissionais->error . "<br>";
        $sucesso = false;
    } else {

        // print_r($assuntosselecionados_array);
        $id_profissional = $stmt_profissionais->insert_id;

        $stmt_atendimento = $conn->prepare("INSERT INTO atendimento (profissional, assunto, acoes, descricao, situacao, veiculo_atendimento, data) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_atendimento->bind_param("issssss", $id_profissional, $assuntoatendimento, $acoes, $descricao, $status, $veiculoselecionado, $date);

        if (!$stmt_atendimento->execute()) {
            echo "Erro ao inserir dados na tabela atendimento: " . $stmt_atendimento->error . "<br>";
            $sucesso = false;
        } else {
            // Obter o ID inserido na tabela atendimento
            $id_atendimento = $stmt_atendimento->insert_id;

            foreach ($assuntosselecionados_array as $assuntosse) {
                // Inserir dados na tabela atendimento_has_assunto
                $stmt_atendimento_has_assunto = $conn->prepare("INSERT INTO atendimento_has_assunto (atendimento, assunto) VALUES (?, ?)");
                $stmt_atendimento_has_assunto->bind_param("is", $id_atendimento, $assuntosse);

                if (!$stmt_atendimento_has_assunto->execute()) {
                    echo "Erro ao inserir dados na tabela atendimento_has_assunto: " . $stmt_atendimento_has_assunto->error . "<br>";
                    $sucesso = false;
                }
            }
        }
    }

    return $sucesso;
}

// Conectar ao banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter dados do formulário via POST
$date = $_POST['date'] ?? null;
$tipo_atendimento = $_POST['tipo_atendimento'] ?? null;
$celulardois = $_POST['celulardois']  ;
$status = $_POST['status'] ?? null;
$nome = $_POST['nome'] ?? null;
$registro = $_POST['registro'] ?? null;
$orgao = $_POST['orgao'] ?? null;
$celular = $_POST['celular'] ?? null;
$nascimento = $_POST['nascimento'] ?? null;
$email = $_POST['email'] ?? null;
$endereco = $_POST['endereco'] ?? null;
$especialidade = $_POST['especialidade'] ?? null;
$assunto = $_POST['assunto'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$acoes = $_POST['acoes'] ?? null;
$cpf = $_POST['cpf'] ?? null;
$veiculoselecionado = $_POST['veiculoselecionado'] ?? null;
$assuntoatendimento = $_POST['assuntoatendimento'] ?? null;
$assuntosselecionados_array = $_POST['assuntosselecionados_array'] ?? [];

if (!is_array($assuntosselecionados_array)) {
    echo "Erro: assuntosselecionados não é um array.<br>";
    
    exit;
}

// echo "<pre>";
// print_r($assuntosselecionados_array);
// echo "</pre>";

// Enviar dados para o banco de dados
if (enviarParaBanco($conn, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento, $veiculoselecionado, $assuntoatendimento, $assuntosselecionados_array)) {
    echo "Dados enviados com sucesso para o banco de dados.";
} else {
    echo "Erro ao enviar dados para o banco de dados.";
}

// Fechar a conexão
$conn->close();
?>
