<?php

namespace Formulario;




class FormsAcoes{
    
    private $pdo;
    public function __construct() {
        try {
            $dbhost = 'localhost';
            $dbname = 'relacionamentomedico';
            $dbuser = 'root';
            $dbpass = '';

            // Conexão com o banco de dados usando PDO
            $this->pdo = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e;
        }
    }


// Função para inserir os dados em várias tabelas
    public function inserirDadosBanco($conn, $date, $orgao, $nome, $registro, $estado, $celular, $data_nascimento, $email, $especialidades, $descricao, $acoes) {
    // Inserir dados na tabela inform_medico
    $sql_inform_medico = "INSERT INTO inform_medico (nome, cpf, data_nascimento, telefone, email)
                          VALUES ('$nome', '$registro', '$data_nascimento', '$celular', '$email')";
    if ($conn->query($sql_inform_medico) !== TRUE) {
        echo "Erro ao inserir dados na tabela inform_medico: " . $conn->error;
        return false;
    }

    // Obter o ID do último registro inserido na tabela inform_medico
    $inform_medico_id = $conn->insert_id;

    // Inserir dados na tabela especialidades
    foreach ($especialidades as $especialidade) {
        $sql_especialidades = "INSERT INTO especialidades (inform_medico_id, especialidade)
                               VALUES ('$inform_medico_id', '$especialidade')";
        if ($conn->query($sql_especialidades) !== TRUE) {
            echo "Erro ao inserir dados na tabela especialidades: " . $conn->error;
            return false;
        }
    }

    // Inserir dados na tabela descricao
    $sql_descricao = "INSERT INTO descricao (inform_medico_id, descricao)
                     VALUES ('$inform_medico_id', '$descricao')";
    if ($conn->query($sql_descricao) !== TRUE) {
        echo "Erro ao inserir dados na tabela descricao: " . $conn->error;
        return false;
    }

    // Inserir dados na tabela acoes
    $sql_acoes = "INSERT INTO acoes (inform_medico_id, acoes)
                  VALUES ('$inform_medico_id', '$acoes')";
    if ($conn->query($sql_acoes) !== TRUE) {
        echo "Erro ao inserir dados na tabela acoes: " . $conn->error;
        return false;
    }

    // Inserir dados na tabela orgao
    $sql_orgao = "INSERT INTO orgao (inform_medico_id, orgao)
                  VALUES ('$inform_medico_id', '$orgao')";
    if ($conn->query($sql_orgao) !== TRUE) {
        echo "Erro ao inserir dados na tabela orgao: " . $conn->error;
        return false;
    }

    return true; // Retorna verdadeiro se todas as inserções forem bem-sucedidas
}

}
