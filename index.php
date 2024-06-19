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
        <script>
    // Script para formatar e limitar o CPF (000.000.000-00)
    document.getElementById('cpf').addEventListener('input', function() {
        var cpf = this.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        if (cpf.length > 11) {
            cpf = cpf.substring(0, 11); // Limita a 11 dígitos
        }
        if (cpf.length > 3) {
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o primeiro ponto
        }
        if (cpf.length > 6) {
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o segundo ponto
        }
        if (cpf.length > 9) {
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona o traço
        }
        this.value = cpf;
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
                        <input type="date" class="form-control" id="nascimento"  name="nascimento" >
                    </div>

                  
                </div>
                
               
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celular">Celular 1</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required placeholder="(99) 9 9999-9999">
                </div>
               
                <div class="col-xl-2 col-md-6 mt-2">
                    <label for="celulardois">Celular 2</label>
                    <input type="tel" class="form-control" id="celulardois" name="celulardois" required placeholder="(99) 9 9999-9999">
                </div>
                <div class="col-xl-3 col-md-6 mt-2">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email"  required>
                    <div class="invalid-feedback">
                        Por favor, insira um e-mail válido.
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mt-2">
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
            
                <div class="col-xl-2 col-md-6 mt-2 mb-4">
                    <label for="especialidade">Especialidade(s)</label>
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
                <input type="radio" class="form-check-input" id="veiculo1" name="veiculo" value="Presencial">
                <label class="form-check-label" for="veiculo1">Presencial</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo2" name="veiculo" value="E-mail">
                <label class="form-check-label" for="veiculo2">E-mail</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo3" name="veiculo" value="WhatsApp">
                <label  class="form-check-label" for="veiculo3">WhatsApp</label>
            </div>
            <div class="form-check col-xl-2 col-lg-3 col-md-3 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo4"  name="veiculo" value="Outros" >
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
                <input type="checkbox" id="assunto1" name="assunto1" value="assunto1">
                <label for="assunto1" class="ml-2">Atualização cadastral do Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto2" name="assunto2" value="assunto2">
                <label for="assunto2" class="ml-3">Autorização de procedimentos</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto3" name="assunto3" value="assunto3">
                <label for="assunto3" class="ml-2">Cadastro Médico</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto4" name="assunto4" value="assunto4">
                <label for="assunto4" class="ml-2">Demandas da Contabilidade</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto5" name="assunto5" value="assunto5">
                <label for="assunto5" class="ml-2">Demandas do Faturamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto6" name="assunto6" value="assunto6">
                <label for="assunto6" class="ml-3">Demandas do INCOR</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto7" name="assunto7" value="assunto7">
                <label for="orgao7" class="ml-2">Demandas do RH</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto8" name="assunto8" value="assunto8">
                <label for="assunto8" class="ml-2">Demandas do setor Financeiro</label>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-4 mb-4">
        <div class="row custom-checkboxes">
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto9" name="assunto9" value="assunto9">
                <label for="assunto9" class="ml-3">Demandas do setor de TI</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto10" name="assunto10" value="assunto10">
                <label for="assunto10" class="ml-3">Estacionamento</label>
            </div>
            <div class="form-control col-sm-12">
                <input type="checkbox" id="assunto11" name="assunto11" value="assunto11">
                <label for="assunto11" class="ml-3">Repasse Médico</label>
            </div>
            <div class="form-control col-sm-12">
    <input type="checkbox" id="assunto12" name="assunto12" value="assunto12" onchange="mostrarCampoTexto()">
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
                    <textarea class="form-control custom-textarea2" id="assunto" name="assunto" rows="1" maxlength="1000" required></textarea>
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