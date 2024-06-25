<?php

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para enviar dados para o banco de dados
function enviarParaBanco($conn, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento, $veiculoselecionado, $assuntoatendimento, $assuntosselecionados) {
    $sucesso = true;

    // Inserir dados na tabela profissionais
    $sql_profissionais = "INSERT INTO profissionais (data_nascimento, cpf, email, telefone, telefone2, nome, registro, especialidades, orgao, endereco) VALUES ('$nascimento', '$cpf', '$email', '$celular', '$celulardois', '$nome', '$registro', '$especialidade', '$orgao', '$endereco')";
    if ($conn->query($sql_profissionais) !== TRUE) {
        echo "Erro ao inserir dados na tabela profissionais: " . $conn->error . "<br>";
        $sucesso = false;
    } else {
        // Obter o ID inserido na tabela profissionais
        $id_profissional = $conn->insert_id;

        // Inserir dados na tabela atendimento
        $sql_atendimento = "INSERT INTO atendimento (profissional, assunto , acoes , descricao , situacao , veiculo_atendimento, data) VALUES ('$id_profissional', '$assuntoatendimento', '$acoes', '$descricao', '$status', '$veiculoselecionado', '$date')";
        if ($conn->query($sql_atendimento) !== TRUE) {
            echo "Erro ao inserir dados na tabela atendimento: " . $conn->error . "<br>";
            $sucesso = false;
        } else {
            // Obter o ID inserido na tabela atendimento
            $id_atendimento = $conn->insert_id;

            // Inserir dados na tabela atendimento_has_assunto
            $sql_atendimento_has_assunto = "INSERT INTO atendimento_has_assunto (atendimento,  assunto) VALUES ('$id_atendimento ' , '$assuntosselecionados')";
            if ($conn->query($sql_atendimento_has_assunto) !== TRUE) {
                echo "Erro ao inserir dados na tabela atendimento_has_assunto: " . $conn->error . "<br>";
                $sucesso = false;
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
echo($assuntosselecionados );

// Obter dados do formulário via POST
$date = isset($_POST['date']) ? $_POST['date'] : null;
$tipo_atendimento = isset($_POST['tipo_atendimento']) ? $_POST['tipo_atendimento'] : null;
$celulardois = isset($_POST['celulardois']) ? $_POST['celulardois'] : null;
$status = isset($_POST['status']) ? $_POST['status'] : null;
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$registro = isset($_POST['registro']) ? $_POST['registro'] : null;
$orgao = isset($_POST['orgao']) ? $_POST['orgao'] : null;
$celular = isset($_POST['celular']) ? $_POST['celular'] : null;
$nascimento = isset($_POST['nascimento']) ? $_POST['nascimento'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$endereco = isset($_POST['endereco']) ? $_POST['endereco'] : null;
$especialidade = isset($_POST['especialidade']) ? $_POST['especialidade'] : null;
$assunto = isset($_POST['assunto']) ? $_POST['assunto'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$acoes = isset($_POST['acoes']) ? $_POST['acoes'] : null;
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
$veiculoselecionado = isset($_POST['veiculoselecionado']) ? $_POST['veiculoselecionado'] : null;
$assuntoatendimento = isset($_POST['assuntoatendimento']) ? $_POST['assuntoatendimento'] : null;
$assuntosselecionados = isset($_POST['assuntosselecionados']) ? $_POST['assuntosselecionados'] : null;

// Enviar dados para o banco de dados
if (enviarParaBanco($conn, $date, $status, $nome, $registro, $orgao, $celular, $celulardois, $nascimento, $email, $endereco, $especialidade, $assunto, $descricao, $acoes, $cpf, $tipo_atendimento, $veiculoselecionado, $assuntoatendimento, $assuntosselecionados)) {
    echo "Dados enviados com sucesso para o banco de dados.";
} else {
    echo "Erro ao enviar dados para o banco de dados.";
}

// Fechar a conexão
$conn->close();
?>
