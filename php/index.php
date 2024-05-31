

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relacionamento Medico - HRG</title>
    <link rel="icon" href="view/img/Logobordab.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-grid.css">
    <link rel="stylesheet" href="../css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/multi-select-tag.css">
</head>
<body>
    
<!-- Parte do header e nav -->
<?php include '../php/header.php'; ?>

<main class="container-fluid d-flex justify-content-center align-items-center">
    <div class="form-group col-8 mt-5">
        <form id="occurrenceForm" action="process_form.php" method="post" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-xl-3 col-md-6 mt-3">
                    <div class="form-group">
                        <label for="date">Data</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>
             
                <div class="col-xl-7 col-md-6 mt-3">
    <div class="form-group ">
        <label for="orgao">Assunto  Tratado </label>
        <div class="custom-checkboxes d-flex justify-content-between ">
            <div class="option form-control"><input type="checkbox" id="orgao1" name="orgao1" value="Orgao1">Consulta </div>
            <div class="option form-control"><input type="checkbox" id="orgao2" name="orgao2" value="Orgao2">Treinamento </div>
            <div class="option form-control"><input type="checkbox" id="orgao2" name="orgao2" value="Orgao2">Reuniao </div>

        </div>
    </div>
</div>


                  <div class="col-xl-2 col-md-6 mt-3">
                    <div class="form-group">
                        <label for="orgao">Status</label>
                        <button class="form-control">Fechado</button>
                    </div>
                </div>

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

                <div class="col-xl-3 col-md-6 mt-3">
                    <label for="celular">Cpf</label>
                    <input type="tel" class="form-control" id="celular" name="celular" required placeholder="999.999.999-99">
                </div>
                <div class="col-xl-3 col-md-6 mt-3">
                    <div class="form-group">
                        <label for="date">Data de Nascimento</label>
                        <input type="date" class="form-control" id="date" name="date" required>
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
                 
                <div class="col-xl-12 col-md-6 mt-3">
                    <label for="descricao">Especialidades</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" maxlength="1000" required></textarea>
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
            <button type="submit" class="btn btn-primary" onclick="enviarFormulario()">Enviar</button>
            <script>
       function enviarFormulario() {
            window.location.href = '../php/historico.php'; // Altere o nome do arquivo e o caminho conforme necessário
        }



            </script>

        </form>
    </div>  
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/indexenviar.js"></script>
</body>
</html>
