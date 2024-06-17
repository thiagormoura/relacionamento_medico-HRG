document.addEventListener('DOMContentLoaded', function() {
    let assunto = {
        repasse: '',
        admissao: '',
        atualizardados: ''
    };

    function verificarCamposPreenchidos() {
        if (Object.values(assunto).some(campo => campo.trim() !== '')) {
            console.log("Pelo menos um campo está preenchido:", assunto);
        } else {
            console.log("Nenhum campo está preenchido.");
        }
    }

    document.getElementById('repasse').addEventListener('input', function(event) {
        assunto.repasse = event.target.value;
        verificarCamposPreenchidos();
    });

    document.getElementById('admissao').addEventListener('input', function(event) {
        assunto.admissao = event.target.value;
        verificarCamposPreenchidos();
    });

    document.getElementById('atualizardados').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });

    function enviarFormulario(event) {
        event.preventDefault();

        var nome = document.getElementById("nome").value || '';
        var cpf = document.getElementById("cpf").value || '';
        var nascimento = document.getElementById("nascimento").value || '';
        var celular = document.getElementById("celular").value || '';
        var celulardois = document.getElementById("celulardois").value || '';
        var email = document.getElementById("email").value || '';
        var especialidade = document.getElementById("especialidade").value || '';
        var registro = document.getElementById("registro").value || '';
        var orgao = document.getElementById("orgao").value || '';
        var date = document.getElementById("date").value || '';
        var tipo_atendimento = document.getElementById("tipo_atendimento").value || '';
        var descricao = document.getElementById("descricao").value || '';
        var acoes = document.getElementById("acoes").value || '';
        
        // Obter o valor do elemento selecionado em situacao_atendimento
        var situacao_atendimento = '';
        var situacao_atendimento_elements = document.getElementsByName("situacao_atendimento");
        
        for (var i = 0; i < situacao_atendimento_elements.length; i++) {
            if (situacao_atendimento_elements[i].checked) {
                situacao_atendimento = situacao_atendimento_elements[i].value;
                break;
            }
        }

        console.log("Situação Atendimento selecionada:", situacao_atendimento);

        console.log({
            tipo_atendimento: tipo_atendimento,
            assunto: assunto,
            date: date,
            nome: nome,
            registro: registro,
            orgao: orgao,
            celular: celular,
            celulardois: celulardois,
            nascimento: nascimento,
            email: email,
            especialidade: especialidade,
            descricao: descricao,
            acoes: acoes,
            cpf: cpf
        });

        var camposObrigatorios = [
            { campo: nome, nome: "Nome" },
            { campo: email, nome: "Email" },
            { campo: date, nome: "Data" },
            { campo: registro, nome: "Registro" },
            { campo: orgao, nome: "Órgão" },
            { campo: celular, nome: "Celular" },
            { campo: celulardois, nome: "Segundo Celular" },
            { campo: nascimento, nome: "Data de Nascimento" },
            { campo: especialidade, nome: "Descrição da Especialidade" },
            { campo: descricao, nome: "Descrição" },
            { campo: tipo_atendimento, nome: "Tipo de Atendimento" },
            { campo: acoes, nome: "Ações" },
            { campo: cpf, nome: "CPF" }
        ];

        var camposVazios = camposObrigatorios.filter(function(campo) {
            return campo.campo.trim() === "";
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
            return;
        }

        $.ajax({
            url: 'enviarindexbanco.php',
            method: 'POST',
            data: {
                repasse: assunto.repasse,
                atualizardados: assunto.atualizardados,
                admissao: assunto.admissao,
                date: date,
                nome: nome,
                registro: registro,
                orgao: orgao,
                celular: celular,
                celulardois: celulardois,
                nascimento: nascimento,
                email: email,
                especialidade: especialidade,
                descricao: descricao,
                acoes: acoes,
                tipo_atendimento: tipo_atendimento,
                cpf: cpf,
                situacao_atendimento: situacao_atendimento
            },
            success: function(response) {
                Swal.fire({
                    title: "Cadastro realizado com sucesso!",
                    text: response,
                    icon: "success"
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

    var enviarButton = document.getElementById("enviarbutton");
    enviarButton.addEventListener("click", function(event) {
        enviarFormulario(event);
    });

    // Evento para verificar CPF ao pressionar Enter no campo CPF
    document.getElementById('cpf').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            var cpf = event.target.value.trim();
            console.log("CPF digitado:", cpf);

            if (cpf !== '') {
                verificarCPF(cpf);
            }
        }
    });

    // Função para verificar o CPF
    function verificarCPF(cpf) {
        $.ajax({
            url: 'verificarcpf.php',
            method: 'POST',
            data: { cpf: cpf },
            dataType: 'json',
            success: function(response) {
                console.log("Resposta da verificação do CPF:", response);
                if (response.exists) {
                    Swal.fire({
                        title: "CPF já cadastrado",
                        text: "O CPF informado já está registrado no sistema. As informações foram preenchidas.",
                        icon: "info"
                    });

                    preencherCampos(response.data);
                } else {
                    Swal.fire({
                        title: "CPF disponível",
                        text: "O CPF informado está disponível para cadastro.",
                        icon: "success"
                    });

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

    // Função para preencher os campos do formulário
    function preencherCampos(data) {
        document.getElementById('nome').value = data.nome || '';
        document.getElementById('nascimento').value = formatarData(data.data_nascimento) || '';
        document.getElementById('celular').value = data.telefone || '';
        document.getElementById('celulardois').value = data.telefone2 || '';
        document.getElementById('email').value = data.email || '';
    }

    // Função para limpar os campos do formulário
    function limparCampos() {
        document.getElementById('nome').value = '';
        document.getElementById('nascimento').value = '';
        document.getElementById('celular').value = '';
        document.getElementById('celulardois').value = '';
        document.getElementById('email').value = '';
        document.getElementById('especialidade').value = '';
        document.getElementById('registro').value = '';
        document.getElementById('orgao').value = '';
    }

    // Função para formatar data
    function formatarData(data) {
        if (!data) return '';
        var partes = data.split('-');
        if (partes.length !== 3) return '';
        var dia = partes[2];
        var mes = partes[1];
        var ano = partes[0];
        return `${dia}/${mes}/${ano}`;
    }
});
