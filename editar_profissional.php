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


<?php
        $pageTitle = "Atualizar Profissionais";
        include 'php/header.php';
    ?>
<body>
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
                    <input type="text" class="form-control bg-white" id="cpf" name="cpf" value="<?= isset($dados['cpf_profissional']) ? htmlspecialchars($dados['cpf_profissional']) : "" ?>">
                    <small id="cpfError" class="form-text text-danger" style="display: none;">CPF inválido.</small>
                </div>
                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control bg-white" id="nome" name="nome" maxlength="80" required value="<?= isset($dados['nome_profissional']) ? htmlspecialchars($dados['nome_profissional']) : "" ?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control bg-white" id="nascimento" name="nascimento" value="<?= isset($dados['data_nascimento_profissional']) ? htmlspecialchars($dados['data_nascimento_profissional']) : "" ?>">
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular">Celular 1</label>
                    <input type="tel" class="form-control bg-white" id="celular" name="celular" value="<?= isset($dados['telefone_profissional']) ? htmlspecialchars($dados['telefone_profissional']) : "" ?>">
                    <small id="celularError" class="form-text text-danger" style="display: none;">Número de celular inválido ou não possui 11 dígitos.</small>
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular2">Celular 2</label>
                    <input type="tel" class="form-control bg-white" id="celular2" name="celular2" value="<?= isset($dados['telefone2_profissional']) ? htmlspecialchars($dados['telefone2_profissional']) : "" ?>">
                    <small id="celular2Error" class="form-text text-danger" style="display: none;">Os celulares não podem ser iguais ou não possuem 11 dígitos.</small>
                </div>
                <div class="col-xl-4 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control bg-white" id="email" name="email" required value="<?= isset($dados['email_profissional']) ? htmlspecialchars($dados['email_profissional']) : "" ?>">
                    <small id="emailError" class="form-text text-danger" style="display: none;">E-mail inválido.</small>
                </div>
                <div class="col-xl-4 col-md-6 mt-2">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control bg-white" id="endereco" name="endereco" placeholder="Digite o endereço completo" required value="<?= isset($dados['endereco_profissional']) ? htmlspecialchars($dados['endereco_profissional']) : "" ?>">
                </div>
                <div class="row">
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="registro">CRM</label>
                    <input type="text" class="form-control bg-white" id="registro" name="registro" maxlength="6" pattern="\d{5}" title="Apenas os últimos 5 dígitos" required value="<?= isset($dados['registro_profissional']) ? htmlspecialchars(substr($dados['registro_profissional'], -5)) : "" ?>">
                    <small id="crmHelpText" class="form-text text-muted" style="display: none;">Apenas os últimos 5 dígitos</small>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const registroInput = document.getElementById('registro');
                    const crmHelpText = document.getElementById('crmHelpText');

                    registroInput.addEventListener('input', function() {
                        const value = this.value.replace(/\D/g, ''); 
                        if (value.length < 5) {
                            crmHelpText.style.display = 'block';
                        } else {
                            crmHelpText.style.display = 'none';
                        }
                    });
                });
                </script>
                <div class="col-xl-2 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="orgao">Órgão</label>
                        <select class="form-control" id="orgao" name="orgao" required>
                            <?php
                            $orgaos = [
                                "CRM-AC", "CRM-AL", "CRM-AP", "CRM-AM", "CRM-BA", "CRM-CE", "CRM-DF", "CRM-ES",
                                "CRM-GO", "CRM-MA", "CRM-MT", "CRM-MS", "CRM-MG", "CRM-PA", "CRM-PB", "CRM-PR",
                                "CRM-PE", "CRM-PI", "CRM-RJ", "CRM-RN", "CRM-RS", "CRM-RO", "CRM-RR", "CRM-SC",
                                "CRM-SP", "CRM-SE", "CRM-TO"
                            ];
                            $orgao_atual = isset($dados['orgao_profissional']) ? $dados['orgao_profissional'] : "";
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
            <button type="submit" class="btn btn-primary" id="atualizarBtn">Atualizar</button>
        </div>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function checkCelularesIguais() {
        const celular1 = $('#celular').val();
        const celular2 = $('#celular2').val();
        
        if (celular1.length !== 11 || celular2.length !== 11) {
            $('#celularError').css('display', 'block');
        } else {
            $('#celularError').css('display', 'none');
        }

        if (celular1 === celular2) {
            $('#celular2Error').css('display', 'block');
        } else {
            $('#celular2Error').css('display', 'none');
        }
    }
    $('#celular').on('input', function() {
        $(this).val(formatarCelularVisual($(this).val()));
        if ($(this).val().length !== 11) {
            $('#celularError').css('display', 'block');
        } else {
            $('#celularError').css('display', 'none');
        }
        checkCelularesIguais();
    });

    $('#celular2').on('input', function() {
        $(this).val(formatarCelularVisual($(this).val()));
        if ($(this).val().length !== 11) {
            $('#celularError').css('display', 'block');
        } else {
            $('#celularError').css('display', 'none');
        }
        checkCelularesIguais();
    });
    checkCelularesIguais();
</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const atualizarBtn = document.getElementById('atualizarBtn');

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    emailInput.addEventListener('input', function() {
        if (!isValidEmail(emailInput.value)) {
            emailError.style.display = 'block';
        } else {
            emailError.style.display = 'none';
        }
    });

    atualizarBtn.addEventListener('click', function(event) {
        if (!isValidEmail(emailInput.value)) {
            event.preventDefault();
            emailError.style.display = 'block';
        } else {
            emailError.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const nascimentoInput = document.getElementById('nascimento');

    function setMinMaxDate() {
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = (today.getMonth() + 1).toString().padStart(2, '0'); // Meses são indexados de zero, então adicionamos 1
        const currentDay = today.getDate().toString().padStart(2, '0');

        const maxYear = currentYear - 18; // Subtrai 18 anos do ano atual
        const minYear = 1874;

        const maxDate = `${maxYear}-${currentMonth}-${currentDay}`;
        const minDate = `${minYear}-01-01`;

        nascimentoInput.setAttribute('min', minDate);
        nascimentoInput.setAttribute('max', maxDate);
    }

    setMinMaxDate();
});



document.addEventListener('DOMContentLoaded', function() {
    var cpfInput = document.getElementById('cpf');
    var cpfError = document.getElementById('cpfError');
    var atualizarBtn = document.getElementById('atualizarBtn');

    function formatCPF(cpf) {
        cpf = cpf.replace(/\D/g, ""); // Remove tudo o que não é dígito
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        return cpf;
    }

    function isValidCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.length !== 11) return false;
        let sum = 0;
        let remainder;

        if (cpf === "00000000000" || cpf === "11111111111" || cpf === "22222222222" || cpf === "33333333333" ||
            cpf === "44444444444" || cpf === "55555555555" || cpf === "66666666666" || cpf === "77777777777" ||
            cpf === "88888888888" || cpf === "99999999999") return false;

        for (let i = 1; i <= 9; i++) sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        remainder = (sum * 10) % 11;
        if ((remainder === 10) || (remainder === 11)) remainder = 0;
        if (remainder !== parseInt(cpf.substring(9, 10))) return false;

        sum = 0;
        for (let i = 1; i <= 10; i++) sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        remainder = (sum * 10) % 11;
        if ((remainder === 10) || (remainder === 11)) remainder = 0;
        if (remainder !== parseInt(cpf.substring(10, 11))) return false;
        return true;
    }

    cpfInput.addEventListener('input', function() {
        cpfInput.value = formatCPF(cpfInput.value);
    });

    cpfInput.addEventListener('keypress', function(event) {
        if (cpfInput.value.replace(/\D/g, '').length >= 11) {
            event.preventDefault();
        }
    });

    atualizarBtn.addEventListener('click', function(event) {
        if (!isValidCPF(cpfInput.value)) {
            event.preventDefault();
            cpfError.style.display = 'block';
        } else {
            cpfError.style.display = 'none';
        }
    });
});
</script>

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
    $('#formulario_index').submit(function(event) {
        event.preventDefault();
        if ($('#celular').val().length !== 11) {
            $('#celularError').css('display', 'block').text('O número de celular deve ter exatamente 11 dígitos.');
            return; 
        } else {
            $('#celularError').css('display', 'none');
        }

        if ($('#celular2').val().length !== 11) {
            $('#celular2Error').css('display', 'block').text('O número de celular deve ter exatamente 11 dígitos.');
            return; 
        } else {
            $('#celular2Error').css('display', 'none');
        }
        if ($('#celular').val() === $('#celular2').val()) {
            $('#celular2Error').css('display', 'block').text('Os números de celular não podem ser iguais.');
            return; 
        } else {
            $('#celular2Error').css('display', 'none');
        }
        
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: 'atualizar_profissional.php', 
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: "Dados atualizados",
                    text: "Seus dados foram atualizados com sucesso",
                    icon: "success"
                }).then(function() {
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                alert('Erro ao atualizar os dados. Verifique o console para mais detalhes.');
                console.log(xhr.responseText); 
            }
        });
    });
    $('#celular').on('input', function() {
        if ($(this).val().length > 11) {
            $(this).val($(this).val().slice(0, 11));
        }
        $('#celularError').css('display', 'none');
    });

    $('#celular2').on('input', function() {
        if ($(this).val().length > 11) {
            $(this).val($(this).val().slice(0, 11));
        }
        $('#celular2Error').css('display', 'none');
    });
});
</script>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
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