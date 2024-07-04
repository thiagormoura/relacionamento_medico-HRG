document.addEventListener('DOMContentLoaded', function() {
    // Código inicial para preparar o formulário
    let assunto = {
        assunto1: '',
        assunto2: '',
        assunto3: '',
        assunto4: '',
        assunto5: '',
        assunto6: '',
        assunto7: '',
        assunto8: '',
        assunto9: '',
        assunto10: '',
        assunto11: '',
        assunto12: ''
    };
    function formatarDataExibicao(data) {
        if (!data) return '';
        var dataObj = new Date(data + 'T00:00:00');
        var dia = dataObj.getDate().toString().padStart(2, '0');
        var mes = (dataObj.getMonth() + 1).toString().padStart(2, '0');
        var ano = dataObj.getFullYear();
        return ano + '-' + mes + '-' + dia;
    }
    function preencherCampos(data) {
        document.getElementById('nome').value = data.nome || '';
        document.getElementById('nascimento').value = formatarDataExibicao(data.data_nascimento) || '';
        document.getElementById('celular').value = data.telefone || '';
        document.getElementById('celulardois').value = data.telefone2 || '';
        document.getElementById('email').value = data.email || '';
        document.getElementById('registro').value = data.registro || '';
        document.getElementById('endereco').value = data.endereco || '';
        document.getElementById('especialidade').value = data.especialidades || '';
        var orgaoSelect = document.getElementById('orgao');
        for (var i = 0; i < orgaoSelect.options.length; i++) {
            if (orgaoSelect.options[i].value === data.orgao) {
                orgaoSelect.selectedIndex = i;
                break;
            }
        }
    }
    function limparCampos() {
        document.getElementById('nome').value = '';
        document.getElementById('nascimento').value = '';
        document.getElementById('celular').value = '';
        document.getElementById('celulardois').value = '';
        document.getElementById('email').value = '';
        document.getElementById('especialidade').value = '';
        document.getElementById('registro').value = '';
        document.getElementById('orgao').value = '';
        document.getElementById('assunto').value = '';
        document.getElementById('descricao').value = '';
        document.getElementById('acoes').value = '';
        document.getElementById('endereco').value = '';
    }
    document.getElementById('cpf').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            var cpf = event.target.value.trim();
            if (cpf !== '') {
                verificarCPF(cpf);
            }
        }
    });
    function verificarCPF(cpf) {
        $.ajax({
            url: 'verificarcpf.php',
            method: 'POST',
            data: { cpf: cpf },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    preencherCampos(response.data);
                    if (response.data && response.data.data_nascimento) {
                        document.getElementById('nascimento').value = formatarDataExibicao(response.data.data_nascimento);
                    }
                } else {
                    limparCampos();
                }
            },
            error: function(error) {
                Swal.fire({
                    title: "Erro",
                    text: "Ocorreu um erro ao verificar o CPF.",
                    icon: "error"
                });
                console.error('Erro na verificação do CPF:', error);
            }
        });
    }
    var enviarButton = document.getElementById("enviarbutton");
    enviarButton.addEventListener("click", function(event) {
        enviarFormulario(event);
    });
    function enviarFormulario(event) {
        event.preventDefault();
        var nome = document.getElementById("nome").value ;
        var cpf = document.getElementById("cpf").value ;
        var nascimento = document.getElementById("nascimento").value ;
        var celular = document.getElementById("celular").value ;
        var celulardois = document.getElementById("celulardois").value ;
        var email = document.getElementById("email").value ;
        var especialidade = document.getElementById("especialidade").value ;
        var registro = document.getElementById("registro").value ;
        var orgao = document.getElementById("orgao").value ;
        var date = document.getElementById("date").value ;
        var status = document.getElementById("status").value ;
        var endereco = document.getElementById("endereco").value ;
        var descricao = document.getElementById("descricao").value ;
        var acoes = document.getElementById("acoes").value ;
        var assuntosselecionados = document.getElementById("selectedIds").value ;
        var assuntosselecionados_array = assuntosselecionados.split(",");
        var assuntoatendimento = document.getElementById("assuntoatendimento").value ;
        
        let veiculoselecionado = document.querySelector('input[name="veiculo"]:checked');
        if (veiculoselecionado) {
            veiculoselecionado = veiculoselecionado.value;
            // Se o valor selecionado for "Outros", concatenar com o valor do campo "outro"
            if (veiculoselecionado === 'Outros') {
                veiculoselecionado = document.getElementById('outro').value;
                // Verificar se o campo "outro" está preenchido
                if (!document.getElementById('outro').value.trim()) {
                    Swal.fire({
                        title: "Erro no Cadastro",
                        text: "Por favor, preencha o campo 'Outros'.",
                        icon: "error"
                    });
                    return;
                }
            }
        } else {
            // Se nenhum veículo estiver selecionado
            veiculoselecionado = null;
        }
        var camposObrigatorios = [
            { campo: cpf, nome: "CPF" },
            { campo: nome, nome: "Nome" },
            // { campo: nascimento, nome: "Data de Nascimento" },
            // { campo: celular, nome: "Celular" },
            // { campo: email, nome: "Email" },
            // { campo: endereco, nome: "Endereço" },
            { campo: registro, nome: "Registro" },
            // { campo: orgao, nome: "Órgão" },
            // { campo: especialidade, nome: "Descrição da Especialidade" },
            { campo: veiculoselecionado, nome: "Veículo" },
            { campo: assuntosselecionados, nome: "Assunto Tratado" },
            { campo: assuntoatendimento, nome: "Assunto " },
            { campo: descricao, nome: "Descrição" },
            { campo: acoes, nome: "Ações" },
        ];
        var camposVazios = camposObrigatorios.filter(function(campo) {
            return !campo.campo || (typeof campo.campo !== 'string') || campo.campo.trim() === "";
        });
        if (camposVazios.length > 0) {
            var camposNomes = camposVazios.map(function(campo) {
                return campo.nome;
            }).join(", ");
            Swal.fire({
                title: "Erro no Cadastro",
                text: "Por favor, preencha todos os campos obrigatórios: " + camposNomes,
                icon: "error"
            });
            return; // Retorna aqui para impedir o envio do AJAX
        }
        $.ajax({
            url: 'enviarindexbanco.php',
            method: 'POST',
            data: {
                assuntoatendimento: assuntoatendimento,
                status: status,
                cpf: cpf,
                nome: nome,
                registro: registro,
                orgao: orgao,
                celular: celular,
                celulardois: celulardois,
                nascimento: nascimento,
                email: email,
                endereco: endereco,
                especialidade: especialidade,
                descricao: descricao,
                acoes: acoes,
                veiculoselecionado: veiculoselecionado,
                date: date,
                assuntosselecionados_array: assuntosselecionados_array
            },
            success: function(response) {
                Swal.fire({
                    title: "Cadastro realizado com sucesso!",
                    text: response,
                    icon: "success"
                }).then(function() {
                    // Limpar todos os campos e recarregar a página
                limparCampos();
                    window.location.href = "historico.php";
                });
            },
            error: function(error) {
                Swal.fire({
                    title: "Erro",
                    text: "Ocorreu um erro ao enviar o cadastro.",
                    icon: "error"
                });
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
    function limparCampos() {
        document.getElementById('presencial').value = '';
        document.getElementById('nome').value = '';
        document.getElementById('cpf').value = '';
        document.getElementById('nascimento').value = '';
        document.getElementById('celular').value = '';
        document.getElementById('celulardois').value = '';
        document.getElementById('email').value = '';
        document.getElementById('especialidade').value = '';
        document.getElementById('registro').value = '';
        document.getElementById('orgao').value = '';
        document.getElementById('date').value = '';
        document.getElementById('status').value = '';
        document.getElementById('endereco').value = '';
        document.getElementById('descricao').value = '';
        document.getElementById('acoes').value = '';
        document.getElementById('selectedIds').value = '';
        document.getElementById('assuntoatendimento').value = '';

        // Limpar campos adicionais conforme necessário
    }
});