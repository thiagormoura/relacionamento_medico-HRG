<?php
if(isset($_GET['id'])) {
    // Conecte-se ao banco de dados
    require_once("conexao.php");
    $id_procedimento = $_GET['id'];
    $id_procedimento = filter_var($id_procedimento, FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT DATE(a.data) as data,
a.situacao as situacao,
a.id as id,
im.cpf as cpf,
im.nome as nome_profissional,
im.data_nascimento as nascimento,
im.telefone as tel1,
im.telefone2 as tel2,
im.email as email,
im.endereco as endereco,
im.registro as crm,
im.orgao as orgao,
im.especialidades as especialidade,
a.veiculo_atendimento as veiculo,
assuntos.assunto as assuntos_tratados,
a.assunto as assunto,
a.descricao as descricao,
a.acoes as acoes


FROM relacionamentomedico.atendimento AS a
JOIN relacionamentomedico.profissionais AS im ON a.profissional = im.id
WHERE a.id = $id_procedimento";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
$data = "Data: " . $row["data"] . "<br>";
$status = "Status: " . $row["situacao"] . "<br>";
$cpf = "CPF: " . $row["cpf"] . "<br>";
$nome = "Nome: " . $row["nome_profissional"] . "<br>";
$nascimento = "Nascimento: " . $row["nascimento"] . "<br>";
$telefone1 = "Telefone 1: " . $row["tel1"] . "<br>";
$telefone2 = "Telefone 2: " . $row["tel2"] . "<br>";
$email = "E-mail: " . $row["email"] . "<br>";
$endereco = "Endereco: " . $row["endereco"] . "<br>";
$crm = "CRM: " . $row["crm"] . "<br>";
$orgao = "Orgão: " . $row["orgao"] . "<br>";
$especialidade = "Especialidade: " . $row["especialidade"] . "<br>";
$assunto = "Assunto: " . $row["assunto"] . "<br>";
$descricao = "Descrição: " . $row["descricao"] . "<br>";
$acoes = "Ações: " . $row["acoes"] . "<br>";
$veiculo = "Veiculo: " . $row["veiculo"] . "<br>";

       
       
    } else {
        echo "Nenhum resultado encontrado para este ID de procedimento.";
    }

// Feche a conexão com o banco de dados
$conn->close();
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relacionamento Medico - HRG</title>
    <link rel="icon" href="view/img/Logobordab.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
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

    <!-- Parte do header e nav -->
    <?php include 'php/header.php'; ?>

    <main class="container_fluid d-flex justify-content-center align-items-center">
        <div class="form-group col-8 mt-5">
            <form  id="formulario_index" method="post" action="/seu_script_php.php">

            <div class="row">
    <div class="col-xl-2 col-md-6 mb-5">
        <div class="form-group">
            <label for="date">Data</label>
            <input type="date" class="form-control" id="date" name="date" disabled required value="<?php echo $row["data"];?>" >
        </div>
    </div>
    <div class="col-xl-2 col-md-6 mb-5">
    <div class="form-group">
        <label for="estado">Status</label>
        <select class="form-control" id="estado" name="estado" disabled required>
            <option <?php if ($row["situacao"] == "") echo "selected"; ?>>Selecione um status</option>
            <option value="Fechado" <?php if ($row["situacao"] == "Fechado") echo "selected"; ?>>Fechado</option>
            <option value="Aberto" <?php if ($row["situacao"] == "Aberto") echo "selected"; ?>>Aberto</option>
            <option value="Em andamento" <?php if ($row["situacao"] == "Em andamento") echo "selected"; ?>>Em andamento</option>
        </select>
    </div>
</div>



<div class="border p-3">
            <div class="row ">
            <h4><b>DADOS DO PROFISSIONAL</b></h4>
            <div class="col-xl-2 col-md-6 mt-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required disabled placeholder=""  value="<?php echo $row["cpf"];?>" >
    </div>

                <div class="col-xl-6  col-md-6 mt-2">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" maxlength="80" disabled  required value="<?php echo $row["nome_profissional"];?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" disabled required value="<?php echo $row["nascimento"];?>">
                    </div>
                </div>


                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular">Celular 1</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required disabled  placeholder="(99) 9 9999-9999" value="<?php echo $row["tel1"];?>">
                </div>

                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celulardois">Celular 2</label>
                    <input type="tel" class="form-control" id="celulardois" name="celulardois" required disabled  placeholder="(99) 9 9999-9999" value="<?php echo $row["tel2"];?>">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" disabled  required value="<?php echo $row["email"];?>">
                    <div class="invalid-feedback">
                        Por favor, insira um e-mail válido.
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mt-2">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" disabled  placeholder="Digite o endereço completo" required value="<?php echo $row["endereco"];?>">
                </div>



                <div class="row">
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="registro">CRM</label>
                    <input type="text" class="form-control" id="registro" name="registro" disabled  maxlength="6" required value="<?php echo $row["crm"];?>">
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="orgao">Órgão</label>
                        <select class="form-control" id="orgao" name="orgao" disabled  required value="<?php echo $row["orgao"];?>">
                            <option value="">Selecione...</option>
                            <option value="Orgao1">CRM-AC</option>
                            <option value="Orgao2">CRM-AL</option>
                            <option value="Orgao1">CRM-AP</option>
                            <option value="Orgao2">CRM-AM</option>
                            <option value="Orgao1">CRM-BA</option>
                            <option value="Orgao2">CRM-CE</option>
                            <option value="Orgao1">CRM-DF</option>
                            <option value="Orgao2">CRM-ES</option>
                            <option value="Orgao1">CRM-GO</option>
                            <option value="Orgao2">CRM-MA</option>
                            <option value="Orgao1">CRM-MT</option>
                            <option value="Orgao2">CRM-MS</option>
                            <option value="Orgao1">CRM-MG</option>
                            <option value="Orgao2">CRM-PA</option>
                            <option value="Orgao1">CRM-PB</option>
                            <option value="Orgao2">CRM-PR</option>
                            <option value="Orgao1">CRM-PE</option>
                            <option value="Orgao2">CRM-PI</option>
                            <option value="Orgao1">CRM-RJ</option>
                            <option value="Orgao2">CRM-RN</option>
                            <option value="Orgao1">CRM-RS</option>
                            <option value="Orgao2">CRM-RO</option>
                            <option value="Orgao1">CRM-RR</option>
                            <option value="Orgao2">CRM-SC</option>
                            <option value="Orgao1">CRM-SP</option>
                            <option value="Orgao2">CRM-SE</option>
                            <option value="Orgao1">CRM-TO</option>
                        </select>
                    </div>
                </div>
                </label>

                <div class="col-xl-2 col-md-6 mt-2 mb-4">
                    <label for="especialidade">Especialidade(s)</label>
                    <input type="text" class="form-control" id="especialidade" disabled  name="especialidade" maxlength="12" required value="<?php echo $row["especialidade"];?>">
                </div>

                </div>
                </div>
                </div>
                <br>

                <div class="col-xl-7 col-md-6 mt-4 mb-5">
    <label for="orgao">Veículo de manifestação</label>
    <div class="row custom-checkboxes">
        <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
            <input type="radio" class="form-check-input" id="veiculo1" name="veiculo" disabled value="Presencial" <?php if ($row["veiculo"] == "Presencial") echo "checked"; ?>>
            <label class="form-check-label" for="veiculo1">Presencial</label>
        </div>
        <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
            <input type="radio" class="form-check-input" id="veiculo2" name="veiculo" disabled value="E-mail" <?php if ($row["veiculo"] == "E-mail") echo "checked"; ?>>
            <label class="form-check-label" for="veiculo2">E-mail</label>
        </div>
        <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
            <input type="radio" class="form-check-input" id="veiculo3" name="veiculo" disabled value="WhatsApp" <?php if ($row["veiculo"] == "WhatsApp") echo "checked"; ?>>
            <label class="form-check-label" for="veiculo3">WhatsApp</label>
        </div>
        <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
            <input type="radio" class="form-check-input" id="veiculo4" name="veiculo" disabled value="Outros" <?php if ($row["veiculo"] == "Outros") echo "checked"; ?>>
            <label class="form-check-label" for="veiculo4">Outros</label>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-10 mt-2">
            <textarea class="form-control custom-textarea" id="acoes2" name="acoes2" rows="1" disabled maxlength="1000" required <?php if ($row["veiculo"] != "Outros") echo "style='display: none;'"; ?>><?php echo htmlspecialchars($row["veiculo"]); ?></textarea>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-4 col-md-6 mb-5">
        <label for="orgao">Assuntos Tratados</label>
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto1" name="assunto1"disabled value="assunto1">
                <label for="assunto1" class="ml-2">Atualização cadastral do Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto2" name="assunto2" disabled value="assunto2">
                <label for="assunto2" class="ml-3">Autorização de procedimentos</label>
            </div>
            <div  class="form-control col-sm-12">
                <input type="checkbox" id="assunto3" name="assunto3" disabled value="assunto3">
                <label for="assunto3" class="ml-2">Cadastro Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto4" name="assunto4"disabled value="assunto4">
                <label for="assunto4" class="ml-2">Demandas da Contabilidade</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto5" name="assunto5" disabled value="assunto5">
                <label for="assunto5" class="ml-2">Demandas do Faturamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto6" name="assunto6"disabled value="assunto6">
                <label for="assunto6" class="ml-3">Demandas do INCOR</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto7" name="assunto7"disabled value="assunto7">
                <label for="orgao7" class="ml-2">Demandas do RH</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto8" name="assunto8"disabled value="assunto8">
                <label for="assunto8" class="ml-2">Demandas do setor Financeiro</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4 mb-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto9" name="assunto9" disabled value="assunto9">
                <label for="assunto9" class="ml-3">Demandas do setor de TI</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto10" name="assunto10" disabled value="assunto10">
                <label for="assunto10" class="ml-3">Estacionamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto11" name="assunto11" disabled value="assunto11">
                <label for="assunto11" class="ml-3">Repasse Médico</label>
            </div>
            <div class="form-control col-sm-12">
    <input type="checkbox" id="assunto12" name="assunto12" disabled value="assunto12" onchange="mostrarCampoTexto()">
    <label for="assunto12" class="ml-3">Outros</label>
</div>

        </div>
    </div>
    <div class="form-control col-sm-12 mb-5" id="campoTexto" style="display: none;">
    <b><p>Se "Outros" for assinalado, indique a qual assunto se refere:</p></b>
    <textarea class="form-control" id="acoes3" name="acoes3" rows="1" maxlength="1000" required></textarea>
</div>
</div>


               
                <div class="border p-3 mt-4">
                <h4><b>DESCRIÇÃO DO ATENDIMENTO</b></h4>
                <div class="col-xl-12 col-md-6 mt-3">
                    <label for="assunto">Assunto</label>
                    <textarea class="form-control custom-textarea2" id="assunto" disabled name="assunto" rows="1" maxlength="1000" required value="<?php echo $row["assunto"];?>"></textarea>
                </div>




                <div class="row ">

                    <div class="col-xl-12 col-md-6 mt-3">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" maxlength="1000" disabled required><?php echo htmlspecialchars($row["descricao"]); ?></textarea>
                    </div>

                    <div class="col-xl-12 col-md-6 mt-3 mb-3">
                        <label for="acoes">Ações</label>
                        <textarea class="form-control" id="acoes" name="acoes" rows="3" maxlength="1000" disabled required><?php echo htmlspecialchars($row["acoes"]); ?></textarea>
                    </div>
                </div>
                </div>






        </form>
        <br>
    </div>  
</main>

    <script>
$(document).ready(function() {
        $('#cpf').mask('000.000.000-00', {reverse: true});
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <script src="js/indexenviar.js"></script>
</body>

</html>