<?php
include("conexao.php")


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
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
    </div>
    <div class="col-xl-2 col-md-6 mb-5">
        <div class="form-group">
            <label for="estado">Status</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="">Selecione um status</option>
                <option value="Fechado">Fechado</option>
                <option value="Aberto">Aberto</option>
                <option value="Emandamento">Em andamento</option>
            </select>
        </div>
    </div>



<div class="border p-3">
            <div class="row ">
            <h4><b>DADOS DO PROFISSIONAL</b></h4>
            <div class="col-xl-2 col-md-6 mt-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="">
    </div>
                
                <div class="col-xl-6  col-md-6 mt-2">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" maxlength="80" required>
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="date">Data de Nascimento</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>
                
               
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular">Telefone</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required placeholder="(99) 9 9999-9999">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Por favor, insira um e-mail válido.
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mt-2">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite o endereço completo" required>
                </div>



                <div class="row">
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="registro">CRM</label>
                    <input type="text" class="form-control" id="registro" name="registro" maxlength="6" required>
                </div>
                <div class="col-xl-2 col-md-6 mt-2">
                    <div class="form-group">
                        <label for="orgao">Órgão</label>
                        <select class="form-control" id="orgao" name="orgao" required>
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
                    <label for="descricao">Especialidade(s)</label>
                    <input type="text" class="form-control" id="especialidade" name="especialidade" maxlength="12" required>
                </div>
               
                </div>
                </div>
                </div>
                <br>


                <div class="col-xl-7 col-md-6 mt-4 mb-5">
        <label for="orgao">Veículo de manifestação</label>
        <div class="row custom-checkboxes">
            <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo1" name="veiculo1" value="veiculo1">
                <label class="form-check-label" for="veiculo1">Presencial</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo2" name="veiculo2" value="veiculo2">
                <label class="form-check-label" for="veiculo2">E-mail</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo3" name="veiculo3" value="veiculo3">
                <label  class="form-check-label" for="veiculo3">WhatsApp</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo4"  name="veiculo4" value="veiculo4" >
                <label class="form-check-label" for="veiculo4">Outros</label>
            </div>
            <div class="col-xl-2  col-lg-4 col-sm-10 mt-2">
                <textarea class="form-control custom-textarea" id="acoes2" name="acoes2" rows="1" maxlength="1000" required></textarea>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-5">
        <label for="orgao">Assuntos Tratados</label>
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao1" name="orgao1" value="Orgao1">
                <label for="orgao1" class="ml-2">Atualização cadastral do Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao2" name="orgao2" value="Orgao2">
                <label for="orgao2" class="ml-3">Autorização de procedimentos</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao3" name="orgao3" value="Orgao3">
                <label for="orgao3" class="ml-2">Cadastro Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao4" name="orgao4" value="Orgao4">
                <label for="orgao4" class="ml-2">Demandas da Contabilidade</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao5" name="orgao5" value="Orgao5">
                <label for="orgao5" class="ml-2">Demandas do Faturamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao6" name="orgao6" value="Orgao6">
                <label for="orgao6" class="ml-3">Demandas do INCOR</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao7" name="orgao7" value="Orgao7">
                <label for="orgao7" class="ml-2">Demandas do RH</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao8" name="orgao8" value="Orgao8">
                <label for="orgao8" class="ml-2">Demandas do setor Financeiro</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4 mb-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao9" name="orgao9" value="Orgao9">
                <label for="orgao9" class="ml-3">Demandas do setor de TI</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao10" name="orgao10" value="Orgao10">
                <label for="orgao10" class="ml-3">Estacionamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="orgao11" name="orgao11" value="Orgao11">
                <label for="orgao11" class="ml-3">Repasse Médico</label>
            </div>
            <div class="form-control col-sm-12">
    <input type="checkbox" id="orgao12" name="orgao12" value="Orgao12" onchange="mostrarCampoTexto()">
    <label for="orgao12" class="ml-3">Outros</label>
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
                    <label for="descricao">Assunto</label>
                    <textarea class="form-control custom-textarea2" id="descricao" name="descricao" rows="1" maxlength="1000" required></textarea>
                </div>


                <div class="row ">

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
        <button type="submit" id="enviarbutton" class="btn btn-primary">Enviar</button>
       
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
        var checkbox = document.getElementById("orgao12");
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