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
    <div class="col-xl-2 col-md-6">
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

    <div class="col-xl-7 col-md-6">
        <label for="orgao">Veículo de manifestação</label>
        <div class="row custom-checkboxes">
            <div class="form-check col-lg-2 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo1" name="veiculo1" value="veiculo1">
                <label class="form-check-label" for="veiculo1">Presencial</label>
            </div>
            <div class="form-check col-lg-2 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo2" name="veiculo2" value="veiculo2">
                <label class="form-check-label" for="veiculo2">E-mail</label>
            </div>
            <div class="form-check col-lg-2 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo3" name="veiculo3" value="veiculo3">
                <label  class="form-check-label" for="veiculo3">WhatsApp</label>
            </div>
            <div class="form-check col-lg-2 col-md-4 col-sm-3 mt-2">
                <input type="radio" class="form-check-input" id="veiculo4"  name="veiculo4" value="veiculo4" >
                <label class="form-check-label" for="veiculo4">Outros</label>
            </div>
            <div class="col-lg-4 col-sm-6 mt-2">
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



                <div class="border p-3">
                <div class="row d-flex">
                    <label for="">
                        <h4> Dados do Profissional </h4>
                    </label>
 <div class="col-xl-3 col-md-6 mt-3">
                        <label for="celular">Cpf </label>
                        <script>
    $(document).ready(function() {
        $('#cpf').mask('000.000.000-00', {reverse: true});
    });
</script>
    <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="">
                    </div>

    
                    <div class="col-xl-6 col-md-6 mt-3">
                        <label for="nome">Nome do Profissional </label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="80" required>
                    </div>
                   
                    <div class="col-xl-3 col-md-6 mt-3">
                        <div class="form-group">
                            <label for="nascimento">Data de Nascimento </label>
                            <input type="date" class="form-control" id="nascimento" name="nascimento" required>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mt-3">
                        <label for="celular">Número de Celular </label>
                        <input type="text" class="form-control" id="celular" name="celular"  required placeholder="">
                    </div>
                    <div class="col-xl-4 col-md-6 mt-3">
                        <label for="celulardois">Número de Celular 2</label>
                        <input type="text" class="form-control" id="celulardois" name="celulardois" required placeholder="">
                    </div>
                    <div class="col-xl-4 col-md-6 mt-3">
                        <label for="email">E-mail </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, insira um e-mail válido.
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mt-3">
                        <div class="form-group">
                            <label for="especialidade"> Especialidades</label>

                            <select class="form-control" id="especialidade" name="especialidade" value="Especialidade" required>
                                <option value="">Selecione uma especialidade </option>
                                <option value="Cardiologia">Cardiologia</option>
                                <option value="Dermatologia">Dermatologia</option>
                                <option value="Ginecologia">Ginecologia</option>
                                <option value="Ortopedia">Ortopedia</option>
                                <option value="Pediatria">Pediatria</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mt-3">
                        <label for="registro">Registro do Profissional</label>
                        <input type="text" class="form-control" id="registro" name="registro" maxlength="12" required>
                    </div>

                    <div class="col-xl-4 col-md-6 mt-3 mb-5">
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
                   

                 
                   
                    <label for="">
                        <h4> Dados do Atendimento </h4>
                    </label>

                    <div class="col-xl-3 col-md-6 mt-3">
                        <label for="data"> data</label>
                        <div class="form-group">
                            <input type="date" class="form-control" id="date" name="date" required>

                        </div>
                        <script>
                            // Função para definir a data atual no campo de data e definir o valor mínimo
                            function setDateToToday() {
                                var today = new Date();
                                var yyyy = today.getFullYear();
                                var mm = String(today.getMonth() + 1).padStart(2, '0'); // Mês com zero à esquerda
                                var dd = String(today.getDate()).padStart(2, '0'); // Dia com zero à esquerda

                                var todayFormatted = yyyy + '-' + mm + '-' + dd;
                                var todayDate = todayFormatted; // Variável para armazenar a data de hoje formatada

                                var dateInput = document.getElementById('date');
                                dateInput.value = todayDate;
                                dateInput.min = todayDate; // Define o valor mínimo para a data de hoje
                            }

                            // Chama a função para definir a data ao carregar a página
                            window.onload = function() {
                                setDateToToday();
                            };
                        </script>

                    </div>



                    <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                        <label for="atendimento"> Situação Atendimento </label>
                        <label class="form-control">
                            <input type="radio" class="andamento" name="situacao_atendimento" id="andamento" value="Andamento"> Andamento
                        </label>
                    </div>

                    <!---ABA DE OBJETIVO - TREINAMENTO---->
                    <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                        <label for=""></label>
                        <label class="form-control">
                            <input type="radio" class="aberto" name="situacao_atendimento" id="aberto" value="Aberto"> Aberto
                        </label>
                    </div>
                    <!---ABA DE OBJETIVO - CONSULTA---->
                    <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                        <label for=""></label>
                        <label class="form-control">
                            <input type="radio" class="fechado" name="situacao_atendimento" id="fechado" value="Fechado"> Fechado
                        </label>

                        <!-- <script>
                            let situacaoAtendimento;

                            // Adiciona um evento de mudança a todos os inputs de rádio
                            document.querySelectorAll('input[name="situacao_atendimento"]').forEach(function(elem) {
                                elem.addEventListener('change', function(event) {
                                    situacaoAtendimento = event.target.value;
                                    console.log("Situação Atendimento selecionada:", situacaoAtendimento);
                                });
                            });
                        </script> -->
                    </div>




                    <div class="col-xl-3 col-lg-xl-4  col-md-4 mt-3">
                        <label for="assunestadoto">Assunto tratado</label>



                        <label class="form-control">
                            <input type="checkbox" class="admissao" name="admissao" id="admissao" value="Admissão">Admissão
                        </label>
                    </div>
                    <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                        <label for=""></label>
                        <label class="form-control">
                            <input type="checkbox" class="atualizardados" name="atualizardados" id="atualizardados" value="⁠Atualização de Dados"> ⁠Atualização de Dados
                        </label>
                    </div>

                    <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                        <label for=""></label>
                        <label class="form-control">
                            <input type="checkbox" class="repasse" name="repasse" id="repasse" value="Repasse">Repasse
                        </label>
                    </div>

                    <div class="col-xl-3 col-md-6 mt-3">
                        <div class="form-group">
                            <label for=""> Tipo Atendimento</label>

                            <select class="form-control" id="tipo_atendimento" name="atendimento"   value="Atendimento" required>
                                <option value="">Selecione atendimento...</option>
                                <option value="Presencial">Presencial</option>
                                <option value="E-mail">email</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="outros">Outros</option>
                                <!-- Adicione mais estados aqui -->
                            </select>
                        </div>
                    </div>
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





            </form>




    </main>
    <div class=" container_fluid d-flex justify-content-center align-items-center mb-5">
        <button type="submit" id="enviarbutton" class="btn btn-primary">Enviar</button>
       
    </div>
    <script>
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