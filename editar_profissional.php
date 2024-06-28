<?php
include("conexao.php"); // Inclui o arquivo de conexão com o banco de dados

$dbhost = "localhost";
$dbname = "relacionamentomedico";
$dbuser = "root";
$dbpass = "";

// Função para obter os dados do atendimento e do profissional


// Estabelece a conexão com o banco de dados
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Chamada da função para obter os dados
$dados = dadosProf($id, $conn);

function dadosProf($id, $conn) {
    $dados = array();


    $sql = "SELECT a.data AS data_atendimento, a.situacao AS situacao_atendimento, a.assunto, a.descricao, a.acoes, a.veiculo_atendimento,
                   p.nome AS nome_profissional,p.id As id_profissional, p.data_nascimento AS data_nascimento_profissional, p.cpf, p.telefone AS telefone_profissional, p.telefone2, p.registro, p.email AS email_profissional, 
                   p.endereco, p.especialidades, p.orgao
            FROM atendimento a
            INNER JOIN profissionais p ON a.profissional = p.id
            WHERE a.id = $id";

    $result = $conn->query($sql);

    if ($result === false) {
        echo "Erro na consulta: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        // Obtém os dados
        $row = $result->fetch_assoc();
        $dados['id_profissional'] = $row['id_profissional'];
        $dados['data_atendimento'] = $row['data_atendimento'];
        $dados['situacao_atendimento'] = $row['situacao_atendimento'];
        $dados['assunto'] = $row['assunto'];
        $dados['descricao'] = $row['descricao'];
        $dados['acoes'] = $row['acoes'];
        $dados['veiculo_atendimento'] = $row['veiculo_atendimento'];
        $dados['nome_profissional'] = $row['nome_profissional'];
        $dados['cpf_profissional'] = $row['cpf'];
        $dados['telefone_profissional'] = $row['telefone_profissional'];
        $dados['telefone2_profissional'] = $row['telefone2'];
        $dados['registro_profissional'] = $row['registro'];
        $dados['email_profissional'] = $row['email_profissional'];
        $dados['endereco_profissional'] = $row['endereco'];
        $dados['especialidades_profissional'] = $row['especialidades'];
        $dados['orgao_profissional'] = $row['orgao'];
        $dados['data_nascimento_profissional'] = $row['data_nascimento_profissional']; 
    } else {
        echo "<p>Nenhum dado encontrado para o ID informado.</p>";
    }

    echo "<pre>";
    echo "</pre>";

    return $dados;
}


$conn->close(); 
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes de Atendimento - Relacionamento Médico</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
    <style>
        .custom-textarea {
        height: 100px;
        overflow-y: auto;
        }
    </style>
</head>
<style>
    .form-control{
font-size: 0.8rem;
}

.form-check-label{
font-size: 0.8rem;
}

label{
font-size: 0.8rem;
font-weight: 700;
}

.custom-textarea2{
resize: none; 
}

.custom-textarea {
width: 20em; 
resize: none; 
}

#assunto{
    resize: none; 
}

h4{
    font-family: sans-serif;
}
</style>
<body>



<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-border-hrg">
            <div class="container-fluid">
                <a class="navbar-brand" href="http://10.1.1.31:80/centralservicos/">
                    <img src="http://10.1.1.31:80/centralservicos/resources/img/central-servicos.png" alt="Central de Serviço" style="width: 160px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarCentral" aria-controls="navBarCentral" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navBarCentral">
                </div>
            </div>
        </nav>
        <div class="content-header shadow" style="border-bottom: solid 1px gray;">
            <div class="container-fluid">
                <div class="row py-1">
                    <div class="titulo">
                        <p class="h1  text-light shadow" style="font-size: 25px;" > <?php echo isset($pageTitle) ? $pageTitle : "<b><p class='rm'> Relacionamento Médico </p></b>  <p class='ra'> Atualização de cadastro <p/>"; ?></p>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="container_fluid d-flex justify-content-center align-items-center">
    <div class="form-group col-8 mt-5">
    <form id="formulario_index" method="post">

    <div class="row">
        <div class="border p-3">
            <div class="row">
                <h4><b>DADOS DO PROFISSIONAL</b></h4>
                <div class="col-xl-6 col-md-6 mt-2 visually-hidden">
                    <label for="id_profissional">ID do Profissional</label>
                    <input type="text" class="form-control bg-white" id="id_profissional" name="id_profissional" value="<?= isset($dados['id_profissional']) ? htmlspecialchars($dados['id_profissional']) : "" ?>" readonly>
                </div>
<div class="col-xl-3 col-md-6 mt-2">
    <label for="cpf">CPF</label>
    <input type="text" class="form-control bg-white" id="cpf" name="cpf" value="<?= isset($dados['cpf_profissional']) ? htmlspecialchars($dados['cpf_profissional']) : "" ?>" onblur="validarCPF(this.value)">
    <small id="cpf-error" class="text-danger"></small>
</div>


                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control bg-white" id="nome" name="nome" maxlength="80" required value="<?= isset($dados['nome_profissional']) ? htmlspecialchars($dados['nome_profissional']) : "" ?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                <div class="form-group">
                    <label for="nascimento">Data de Nascimento</label>
                    <input type="date" class="form-control bg-white" id="nascimento" name="nascimento" value="<?= isset($dados['data_nascimento_profissional']) ? htmlspecialchars($dados['data_nascimento_profissional']) : "" ?>" max="<?= date('Y-m-d', strtotime('-16 years')) ?>">
                </div>
                <script>
                    var inputNascimento = document.getElementById('nascimento');
                    var maxDate = new Date();
                    maxDate.setFullYear(maxDate.getFullYear() - 18);
                    var maxDateFormatted = maxDate.toISOString().split('T')[0];
                    inputNascimento.setAttribute('max', maxDateFormatted);
                </script>
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="celular">Celular 1</label>
                    <input type="tel" class="form-control bg-white" id="celular" name="celular" value="<?= isset($dados['telefone_profissional']) ? htmlspecialchars($dados['telefone_profissional']) : "" ?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="celular2">Celular 2</label>
                    <input type="tel" class="form-control bg-white" id="celular2" name="celular2" value="<?= isset($dados['telefone2_profissional']) ? htmlspecialchars($dados['telefone2_profissional']) : "" ?>">
                </div>
                <?php
                function formatarTelefone($telefone) {
                    $telefone_formatado = preg_replace("/(\d{2})(\d{4,5})(\d{4})/", "($1) $2-$3", $telefone);
                    return $telefone_formatado;
                }
                ?>
                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control bg-white" id="email" name="email" required value="<?= isset($dados['email_profissional']) ? htmlspecialchars($dados['email_profissional']) : "" ?>">
                </div>
                <div class="row">
                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control bg-white" id="endereco" name="endereco" placeholder="Digite o endereço completo" required value="<?= isset($dados['endereco_profissional']) ? htmlspecialchars($dados['endereco_profissional']) : "" ?>">
                </div>
                    <div class="col-xl-2 col-md-6 mt-2">
                        <label for="registro">CRM</label>
                        <input type="text" class="form-control bg-white" id="registro" name="registro" maxlength="6" required value="<?= isset($dados['registro_profissional']) ? htmlspecialchars($dados['registro_profissional']) : "" ?>">
                    </div>
                    
                    <div class="col-xl-2 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="orgao">Órgão</label>
                        <select class="form-control" id="orgao" name="orgao" required>
                            <?php
                            // Lista de opções dos órgãos
                            $orgaos = [
                                "CRM-AC", "CRM-AL", "CRM-AP", "CRM-AM", "CRM-BA", "CRM-CE", "CRM-DF", "CRM-ES",
                                "CRM-GO", "CRM-MA", "CRM-MT", "CRM-MS", "CRM-MG", "CRM-PA", "CRM-PB", "CRM-PR",
                                "CRM-PE", "CRM-PI", "CRM-RJ", "CRM-RN", "CRM-RS", "CRM-RO", "CRM-RR", "CRM-SC",
                                "CRM-SP", "CRM-SE", "CRM-TO"
                            ];

                            // Valor atual do órgão
                            $orgao_atual = isset($dados['orgao_profissional']) ? $dados['orgao_profissional'] : "";

                            // Loop para criar as opções
                            foreach ($orgaos as $orgao) {
                                $selected = ($orgao == $orgao_atual) ? 'selected' : '';
                                echo "<option value=\"$orgao\" $selected>$orgao</option>";
                            }
                            ?>
                        </select>
                    </div>
                    </div>
                    <div class="col-xl-2 col-md-6 mt-2 mb-4">
                        <label for="especialidades">Especialidade(s)</label>
                        <input type="text" class="form-control bg-white" id="especialidades" name="especialidades" maxlength="12" required value="<?= isset($dados['especialidades_profissional']) ? htmlspecialchars($dados['especialidades_profissional']) : "" ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#orgao_select').change(function() {
        var selectedOption = $(this).val();
        $('#orgao').val(selectedOption);
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Formatação automática do celular 1
    $('#celular').on('input', function() {
        var telefone = $(this).val().replace(/[^\d]/g, ''); // Remove tudo que não é número
        if (telefone.length > 2) {
            telefone = '(' + telefone.substring(0, 2) + ') ' + telefone.substring(2);
        }
        $(this).val(telefone);
    });

    // Formatação automática do celular 2
    $('#celular2').on('input', function() {
        var telefone = $(this).val().replace(/[^\d]/g, ''); // Remove tudo que não é número
        if (telefone.length > 2) {
            telefone = '(' + telefone.substring(0, 2) + ') ' + telefone.substring(2);
        }
        $(this).val(telefone);
    });

    // Intercepta o envio do formulário via AJAX
    $('#formulario_index').submit(function(event) {
        // Impede o envio padrão do formulário
        event.preventDefault();
        
        // Verifica CPF antes de enviar
        var cpfValido = validarCPF($('#cpf').val());
        if (!cpfValido) {
            return; // Se CPF inválido, não envia o formulário
        }

        // Obtém os dados do formulário
        var formData = $(this).serialize();

        // Envia os dados via AJAX para o arquivo PHP de atualização
        $.ajax({
            type: 'POST',
            url: 'atualizar_profissional.php', // Caminho para o script PHP que processa a atualização
            data: formData,
            success: function(response) {
                // Exibe o alerta de sucesso com SweetAlert2
                Swal.fire({
                    title: "Dados atualizados",
                    text: "Seus dados foram atualizados com sucesso",
                    icon: "success"
                }).then(function() {
                    // Recarrega a página após o alerta ser fechado
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                // Se houver erro no envio AJAX
                alert('Erro ao atualizar os dados. Verifique o console para mais detalhes.');
                console.log(xhr.responseText); // Exibe a mensagem de erro no console
            }
        });
    });

    // Função para validar CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g,''); // Remove tudo que não é número

        if (cpf.length !== 11 ||
            cpf === "00000000000" ||
            cpf === "11111111111" ||
            cpf === "22222222222" ||
            cpf === "33333333333" ||
            cpf === "44444444444" ||
            cpf === "55555555555" ||
            cpf === "66666666666" ||
            cpf === "77777777777" ||
            cpf === "88888888888" ||
            cpf === "99999999999") {
            $('#cpf-error').text("CPF inválido");
            return false;
        }

        // Verifica primeiro dígito verificador
        var soma = 0;
        for (var i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        var resto = 11 - (soma % 11);
        var digitoVerificador1 = (resto === 10 || resto === 11) ? 0 : resto;

        if (digitoVerificador1 !== parseInt(cpf.charAt(9))) {
            $('#cpf-error').text("CPF inválido");
            return false;
        }

        // Verifica segundo dígito verificador
        soma = 0;
        for (var i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        var digitoVerificador2 = (resto === 10 || resto === 11) ? 0 : resto;

        if (digitoVerificador2 !== parseInt(cpf.charAt(10))) {
            $('#cpf-error').text("CPF inválido");
            return false;
        }

        // CPF válido, limpa mensagem de erro
        $('#cpf-error').text("");
        return true;
    }
});
</script>









    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<!-- Bootstrap Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+EWlScbUO8+hFQhL+8EBf4aeF0Bm+EEW0c+" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="js/indexenviar.js"></script>
</body>
</html>