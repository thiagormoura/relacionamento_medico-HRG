<?php
include("conexao.php")
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relacionamento Medico - HRG</title>
    <link rel="icon" href="img\Logobordab.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="js/indexenviar.js"></script>
</head>
<style>
    .form-control {
        font-size: 0.8rem;
    }
    .form-check-label {
        font-size: 0.8rem;
    }

    label {
        font-size: 0.8rem;
        font-weight: 700;
    }

    .custom-textarea2 {
        resize: none;
    }

    .custom-textarea {
        width: 20em;
        resize: none;
    }

    #assunto {
        resize: none;
    }

    h4 {
        font-family: sans-serif;
    }
</style>
<body>
    <?php 
    $pageTitle = "Relacionamento Médico";
    $subTitle =  "Registro de atendimento" ;           
    include 'php/header.php'; 
    ?>
    <main class="container_fluid d-flex justify-content-center align-items-center">
        <div class="form-group col-8 mt-5">
            <form id="formulario_index" method="post" action="/seu_script_php.php">
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-5">
                        <div class="form-group">
                            <label for="date">Data</label>
                            <input type="date" class="form-control" id="date" name="date" onkeydown="return false; " min="<?= date('Y-m-d') ?>">
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var hoje = new Date();
                                var dia = hoje.getDate();
                                var mes = hoje.getMonth() + 1;
                                var ano = hoje.getFullYear();
                                if (mes < 10) {
                                    mes = '0' + mes; 
                                }
                                if (dia < 10) {
                                    dia = '0' + dia; 
                                }
                                var dataFormatada = ano + '-' + mes + '-' + dia;
                                document.getElementById('date').value = dataFormatada;
                            });
                        </script>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-5">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Analize">Análise</option>
                                <option value="Aberto">Aberto</option>
                                <option value="Concluido">Concluido</option>
                            </select>
                        </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("status").value = "Aberto";
    });
</script>
</div>
<div class="border p-3">
<div class="row ">
    <h4><b>DADOS DO PROFISSIONAL</b></h4>
    <div class="col-xl-3 col-md-6 mt-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="">
        <small id="cpfValidationMessage" class="text-danger"></small>
        <script>
                $(document).ready(function() {
                $('#cpf').mask('000.000.000-00', {reverse: true});
                $('#cpf').keydown(function(event) {
                    if (event.keyCode === 13) { 
                        event.preventDefault();
                        validarCPF();
                    }
                });
                $('#cpf').blur(function() {
                    validarCPF();
                });
                function validarCPF() {
                    var cpf = $('#cpf').val().replace(/[^\d]+/g,'');
                    if (cpf.length !== 11 || /^(.)\1{10}$/.test(cpf)) {
                        $('#cpfValidationMessage').text('CPF inválido').css('color', 'red');
                        return;
                    }
                    var sum = 0;
                    var rest;
                    for (var i = 1; i <= 9; i++) {
                        sum += parseInt(cpf.substring(i-1, i)) * (11 - i);
                    }
                    rest = (sum * 10) % 11;
                    if ((rest === 10) || (rest === 11)) {
                        rest = 0;
                    }
                    if (rest !== parseInt(cpf.substring(9, 10))) {
                        $('#cpfValidationMessage').text('CPF inválido').css('color', 'red');
                        return;
                    }
                    sum = 0;
                    for (var i = 1; i <= 10; i++) {
                        sum += parseInt(cpf.substring(i-1, i)) * (12 - i);
                    }
                    rest = (sum * 10) % 11;
                    if ((rest === 10) || (rest === 11)) {
                        rest = 0;
                    }
                    if (rest !== parseInt(cpf.substring(10, 11))) {
                        $('#cpfValidationMessage').text('CPF inválido').css('color', 'red');
                        return;
                    }
                    $('#cpfValidationMessage').text('CPF válido').css('color', 'green');
                }
            });
        </script>
    </div>
    <div class="col-xl-6  col-md-6 mt-2">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" maxlength="80" required>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="form-group">
            <label for="nascimento">Data de Nascimento</label>
            <input type="date" class="form-control" id="nascimento" name="nascimento">
            <small id="nascimento-error" class="text-danger"></small>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nascimentoInput = document.getElementById('nascimento');
            const errorElement = document.getElementById('nascimento-error');
            const enviarButton = document.getElementById('enviarbutton');

            function setMinMaxDate() {
                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = (today.getMonth() + 1).toString().padStart(2, '0');
                const currentDay = today.getDate().toString().padStart(2, '0');

                const maxYear = currentYear - 18;
                const minYear = 1874;

                const maxDate = `${maxYear}-${currentMonth}-${currentDay}`;
                const minDate = `${minYear}-01-01`;

                nascimentoInput.setAttribute('min', minDate);
                nascimentoInput.setAttribute('max', maxDate);
            }

            function validateDate() {
                const inputDate = nascimentoInput.value;
                const selectedDate = new Date(inputDate);

                const minAllowedDate = new Date('1874-01-01'); 

                if (selectedDate < minAllowedDate) {
                    errorElement.textContent = 'Selecione uma data válida';
                    nascimentoInput.classList.add('is-invalid');
                    enviarButton.setAttribute('disabled', 'disabled');
                    return false;
                }

                const today = new Date();
                const maxYear = today.getFullYear() - 18;
                const maxDate = new Date(maxYear, today.getMonth(), today.getDate());

                if (selectedDate > maxDate) {
                    const maxDateString = `${today.getFullYear() - 18}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
                    errorElement.textContent = `Por favor, selecione uma data até ${maxDateString}.`;
                    nascimentoInput.classList.add('is-invalid');
                    enviarButton.setAttribute('disabled', 'disabled');
                    return false;
                }

                errorElement.textContent = '';
                nascimentoInput.classList.remove('is-invalid');
                enviarButton.removeAttribute('disabled'); 
                return true;
            }

            function handleFormSubmit(event) {
                if (!validateDate()) {
                    event.preventDefault(); 
                }
            }

            nascimentoInput.addEventListener('input', validateDate); 

            enviarButton.addEventListener('click', handleFormSubmit); 

            setMinMaxDate();
        });
    </script>
    <div class="col-xl-2 col-md-6 mt-2">
        <label for="celular">Celular 1</label>
        <input type="tel" class="form-control" id="celular" maxlength="11" name="celular"  pattern="[0-9]*"   placeholder="">            
    </div>
    <div class="col-xl-2 col-md-6 mt-2">
        <label for="celular2">Celular 2</label>
        <input type="tel" class="form-control" id="celulardois" maxlength="11" name="celulardois" placeholder="" pattern="[0-9]*">
    </div>
    <div class="col-xl-4 col-md-6 mt-2">
        <label for="email">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="Digite um e-mail valido">
        <small id="emailValidationMessage" class="text-danger"></small>
        <script> $(document).ready(function() {
            $('#email').blur(function() {
                validarEmail();
            });
            $('#enviarbutton').click(function(event) {
                event.preventDefault();
                validarEmail();
            });
            function validarEmail() {
                var email = $('#email').val().trim();
                if (!isValidEmail(email)) {
                    $('#emailValidationMessage').text('Por favor, insira um e-mail válido.').css('color', 'red');
                    return;
                }
                var existeEmail = email.endsWith('@example.com'); 
                    if (existeEmail) {
                        $('#emailValidationMessage').text('O e-mail inserido já existe. Insira outro e-mail.').css('color', 'orange');
                    } else {
                        $('#emailValidationMessage').text('E-mail válido').css('color', 'green');
                    }
                }
                function isValidEmail(email) {
                    // Expressão regular para validar o formato do e-mail
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }
            });
        </script>
    </div>
    <div class="col-xl-4 col-md-6 mt-2">
        <label for="endereco">Endereço</label>
        <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite o endereço completo"  required>
    </div>
    <div class="row">
        <div class="col-xl-2 col-md-6 mt-2">
            <label for="crm">CRM</label>
            <input type="text" class="form-control" id="registro" maxlength="10" name="registro" placeholder="" pattern="[0-9]*">
        </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="form-group">
            <label for="orgao">Órgão</label>
            <select class="form-control" id="orgao" name="orgao" required>
                <option value="">Selecione...</option>
                <option value="CRM-AC">CRM-AC</option>
                <option value="CRM-AL">CRM-AL</option>
                <option value="CRM-AP">CRM-AP</option>
                <option value="CRM-AM">CRM-AM</option>
                <option value="CRM-BA">CRM-BA</option>
                <option value="CRM-CE">CRM-CE</option>
                <option value="CRM-DF">CRM-DF</option>
                <option value="CRM-ES">CRM-ES</option>
                <option value="CRM-GO">CRM-GO</option>
                <option value="CRM-MA">CRM-MA</option>
                <option value="CRM-MT">CRM-MT</option>
                <option value="CRM-MS">CRM-MS</option>
                <option value="CRM-MG">CRM-MG</option>
                <option value="CRM-PA">CRM-PA</option>
                <option value="CRM-PB">CRM-PB</option>
                <option value="CRM-PR">CRM-PR</option>
                <option value="CRM-PE">CRM-PE</option>
                <option value="CRM-PI">CRM-PI</option>
                <option value="CRM-RJ">CRM-RJ</option>
                <option value="CRM-RN">CRM-RN</option>
                <option value="CRM-RS">CRM-RS</option>
                <option value="CRM-RO">CRM-RO</option>
                <option value="CRM-RR">CRM-RR</option>
                <option value="CRM-SC">CRM-SC</option>
                <option value="CRM-SP">CRM-SP</option>
                <option value="CRM-SE">CRM-SE</option>
                <option value="CRM-TO">CRM-TO</option>
            </select>
        </div>
    </div>
    </label>
        <div class="col-xl-6 col-md-6 mt-2 mb-4">
            <label for="especialidade">Especialidade(s)</label>
            <input type="text" class="form-control" id="especialidade" name="especialidade" required>
        </div>
        </div>
    </div>
</div>
<br>
<div class="col-xl-7 col-md-6 mt-4 mb-3">
    <label for="orgao">Veículo de manifestação</label>
    <div class="row custom-checkboxes">
    <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
        <input type="radio" class="form-check-input" id="presencial" name="veiculo" value="Presencial" onclick="toggleOutroCampo(this)">
        <label class="form-check-label" for="veiculo1">Presencial</label>
    </div>
    <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
        <input type="radio" class="form-check-input" id="email" name="veiculo" value="E-mail" onclick="toggleOutroCampo(this)">
        <label class="form-check-label" for="veiculo2">E-mail</label>
    </div>
    <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
        <input type="radio" class="form-check-input" id="WhatsApp" name="veiculo" value="WhatsApp" onclick="toggleOutroCampo(this)">
        <label class="form-check-label" for="veiculo3">WhatsApp</label>
    </div>
    <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
        <input type="radio" class="form-check-input" id="outros" name="veiculo" value="Outros" onclick="toggleOutroCampo(this)">
        <label class="form-check-label" for="veiculo4">Outros</label>
    </div>
        <div class="row"></div>
            <div class="col-xl-8 col-lg-12 col-sm-12 mt-4" id="campoOutros" style="display: none;">
                <textarea class="form-control custom-textarea" id="outro" name="outro" rows="1" maxlength="1000"></textarea>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleOutroCampo(radio) {
        let campoOutros = document.getElementById('campoOutros');
        if (radio.value === 'Outros') {
            campoOutros.style.display = 'block'; 
            document.getElementById('outro').focus(); 
        } else {
            campoOutros.style.display = 'none'; 
        }
    }
</script>
<?php
    $dbhost = "localhost";
    $dbname = "relacionamentomedico";
    $dbuser = "root";
    $dbpass = "";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    $sql = "SELECT id, assunto FROM assunto ORDER BY id ASC";
    $result = $conn->query($sql);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
<div class="row">
    <title>Assuntos Tratados</title>
    <div class="col">
        <label for="assuntotratado">Assuntos Tratados</label>
        <select class="form-controlcol" id="assuntotratado" name="assunto[]" multiple>
            <optgroup label="Selecione Assunto">
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option id='valoresoption' value='" . $row["id"] . "'>" . $row["assunto"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum assunto encontrado</option>";
                    }
                $conn->close();
                ?>
            </optgroup>
        </select>
        <div class="row mt-3" style="display: none;">
            <div class="col">
                <label for="selectedIds">IDs Selecionados:</label>
                <input type="text" id="selectedIds" class="form-control" readonly>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('assuntotratado');
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Selecione assuntos',
                searchPlaceholderValue: 'Procurar'
            });
            element.addEventListener('change', function() {
                const selectedValues = Array.from(element.selectedOptions).map(option => option.value);                      
                document.getElementById('selectedIds').value = selectedValues.join(',');
                console.log(selectedIds)
                });
            });
        </script>
    <div class="border p-3 mt-2">
        <h4><b>DESCRIÇÃO DO ATENDIMENTO</b></h4>
        <div class="row ">
            <div class="col-xl-12 col-md-6 mt-3">
                <label for="assunto">Assunto</label>
                <textarea class="form-control custom-textarea2" id="assuntoatendimento" name="assunto atendimento" rows="1" maxlength="1000"></textarea>
            </div>
            <div class="col-xl-12 col-md-6 mt-3">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" maxlength="1000" required></textarea>
            </div>
            <div class="col-xl-12 col-md-6 mt-3 mb-3">
                <label for="acoes">Ações</label>
                <textarea class="form-control" id="acoes" name="acoes" rows="3" maxlength="1000" required></textarea>
            </div>
        </div>
    </div>
    <div class=" container_fluid d-flex justify-content-center align-items-center mt-4">
        <button type="submit" id="enviarbutton" class="btn col-4 btn-primary">Registrar Atendimento</button>
    </div>
    <div class=" container_fluid d-flex justify-content-center align-items-center mt-4"></div>
        </form>
        <br>
    </div>
</main>
    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00', {
                reverse: true
            });
        });
        function mostrarCampoTexto() {
            var checkbox = document.getElementById("assunto12");
            var campoTexto = document.getElementById("campoTexto");
            if (checkbox.checked) {
                campoTexto.style.display = "block";
            } else {
                campoTexto.style.display = "none";
            }
        }
    </script>
    <script>
        function enforceNumericInput(event) {
            event.target.value = event.target.value.replace(/\D/g, '');
        }
        document.getElementById('celular').addEventListener('input', enforceNumericInput);
        document.getElementById('celulardois').addEventListener('input', enforceNumericInput);
        document.getElementById('registro').addEventListener('input', enforceNumericInput);
    </script>
</body>
</html>