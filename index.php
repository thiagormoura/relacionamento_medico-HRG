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
<body>
    
<!-- Parte do header e nav -->
<?php include 'php/header.php'; ?>

<main class="container_fluid d-flex justify-content-center align-items-center">
    <div class="form-group col-8 mt-5">
        <form id="occurrenceForm"  method="post" onsubmit="return validateForm()">

    
            <div class="row ">
                <div class="col-xl-2 col-md-6 mt-3">
                    <label for="data"> data</label>
                    <div class="form-group">
                        <input type="date" class="form-control" id="date" name="date" required>
                        
                    </div>
                </div>
             
              
    
      
               
         <div class="col-xl-3 col-lg-xl-4  col-md-4 mt-3">
         <label for="assunto"> Assunto Tratado</label>

                 
                
        <label class="form-control">
            <input type="checkbox" class="admissao" name="admissao" id="admissao" value="Admissão/Contrato de Prestador de Serviços">Admissão 
        </label>
    </div> 
            <div class="col-xl-3 col-lg-xl-4 col-md-4 mt-3">
                <label for=""></label>
        <label class="form-control">
            <input type="checkbox" class="atualizardados" name="atualizardados" id="atualizardados" value=" ⁠Atualização de Dados"> ⁠Atualização de Dados
        </label>
    </div> 

            <div class="col-xl-2 col-lg-xl-4 col-md-4 mt-3">
                <label for=""></label>
        <label class="form-control">
            <input type="checkbox" class="repasse" name="repasse" id="repasse" value="repasse">Repasse
        </label>
    </div>


                  <div class="col-xl-2 col-md-6 mt-3">
                    <div class="form-group">
                    <label for="status"> Status</label>

                        <select class="form-control" id="status" name="status" required>
                            <option value="">Selecione um status...</option>
                            <option value="Fechado">Fechado</option>
                            <option value="Aberto">Aberto</option>
                            <!-- Adicione mais estados aqui -->
                        </select>
                    </div>
                </div>
</div>

<div class="row ">


                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="nome">Nome do Profissional</label>
                    <input type="text" class="form-control" id="nome" name="nome" maxlength="80" required>
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="registro">Registro do Profissional</label>
                    <input type="text" class="form-control" id="registro" name="registro" maxlength="12" required>
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                 
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="">Selecione um estado...</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                            <!-- Adicione mais estados aqui -->
                        </select>
                    
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
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
                </div>

                <div class="row ">

               
                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="celular">Cpf</label>
                    <input type="tel" class="form-control" id="cpf" name="cpf" required placeholder="999.999.999-99">
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" required>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="celular">Número de Celular</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required placeholder="(99) 9 9999-9999">
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Por favor, insira um e-mail válido.
                    </div>
                </div>
                  </div>
                <div class="col-xl-12 col-md-6 mt-3">
                    <label for="descricao">Especialidades</label>
                    <textarea class="form-control" id="descricaoespecialidades" name="descricaoespecialidades" rows="3" maxlength="1000" required></textarea>
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



 <div>
               <button type="button" id="enviarbutton" class="btn btn-primary">Enviar</button>
        </div>

        </form>
       
     

    
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/indexenviar.js"></script>
</body>
</html>
