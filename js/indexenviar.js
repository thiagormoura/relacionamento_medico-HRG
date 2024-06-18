document.addEventListener('DOMContentLoaded', function() {
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

    let veiculo = '';

    function verificarCamposPreenchidos() {
        if (Object.values(assunto).some(campo => campo.trim() !== '')) {
            console.log("Pelo menos um campo está preenchido:", assunto);
        } else {
            console.log("Nenhum campo está preenchido.");
        }
    }

    document.getElementById('assunto1').addEventListener('input', function(event) {
        assunto.repasse = event.target.value;
        verificarCamposPreenchidos();
    });

    document.getElementById('assunto2').addEventListener('input', function(event) {
        assunto.admissao = event.target.value;
        verificarCamposPreenchidos();
    });

    document.getElementById('assunto3').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto4').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto5').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto6').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto7').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto8').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto9').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto10').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto11').addEventListener('input', function(event) {
        assunto.atualizardados = event.target.value;
        verificarCamposPreenchidos();
    });
    document.getElementById('assunto12').addEventListener('input', function(event) {
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
        var status = document.getElementById("estado").value || '';
        var cpf = document.getElementById("cpf").value || '';
        var nome = document.getElementById("nome").value || '';
        var nascimento = document.getElementById("nascimento").value || '';
        var celular = document.getElementById("celular").value || '';
        var celulardois = document.getElementById("celulardois").value || '';
        var email = document.getElementById("email").value || '';
        var endereco = document.getElementById("endereco").value || '';
        var crm = document.getElementById("registro").value || '';
        var orgao = document.getElementById("orgao").value || '';
        var especialidade = document.getElementById("especialidade").value || '';
        var assunto = document.getElementById("assunto").value || '';
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
            assunto: assunto,
            date: date,
            nome: nome,
            crm: crm,
            orgao: orgao,
            celular: celular,
            celulardois: celulardois,
            nascimento: nascimento,
            email: email,
            especialidade: especialidade,
            assunto: assunto,
            descricao: descricao,
            acoes: acoes,
            cpf: cpf,
            veiculo: veiculo,
            acoes3 : acoes3
        });

        var camposObrigatorios = [
            { campo: nome, nome: "Nome" },
            { campo: email, nome: "Email" },
            { campo: date, nome: "Data" },
            { campo: crm, nome: "Registro" },
            { campo: orgao, nome: "Órgão" },
            { campo: celular, nome: "Celular" },
            { campo: celulardois, nome: "Segundo Celular" },
            { campo: nascimento, nome: "Data de Nascimento" },
            { campo: especialidade, nome: "Descrição da Especialidade" },
            { campo: descricao, nome: "Descrição" },
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
                assunto1: assunto.assunto1,
                assunto2: assunto.assunto2,
                assunto3: assunto.assunto3,
                assunto4: assunto.assunto4,
                assunto5: assunto.assunto5,
                assunto6: assunto.assunto6,
                assunto7: assunto.assunto7,
                assunto8: assunto.assunto8,
                assunto9: assunto.assunto9,
                assunto10: assunto.assunto10,
                assunto11: assunto.assunto11,
                assunto12: assunto.assunto12,
                date: date,
                status: status,
                cpf: cpf,
                nome: nome,
                registro: registro,
                orgao: orgao,
                celular: celular,
                celulardois: celulardois,
                nascimento: nascimento,
                celular: celular,
                celulardois: celulardois,
                email: email,
                endereco: endereco,
                crm: crm,
                orgao: orgao,
                especialidade: especialidade,
                assunto: assunto,
                descricao: descricao,
                acoes: acoes,
                veiculo: veiculo
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

                preencherCampos(response.data); // Preencher os campos com os dados existentes
            } else {
                Swal.fire({
                    title: "CPF disponível",
                    text: "O CPF informado está disponível para cadastro.",
                    icon: "success"
                });

                limparCampos(); // Limpar os campos do formulário
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
    document.getElementById('nascimento').value = formatarDataParaCampo(data.data_nascimento) || '';
    console.log("Data de nascimento recebida:", data.data_nascimento); // Adicione este console log
    document.getElementById('celular').value = data.telefone || '';
    document.getElementById('celulardois').value = data.telefone2 || '';
    document.getElementById('email').value = data.email || '';
    document.getElementById('registro').value = data.registro || '';

    
    // Preencher o campo 'orgao'
    var orgaoSelect = document.getElementById('orgao');
    for (var i = 0; i < orgaoSelect.options.length; i++) {
        if (orgaoSelect.options[i].value === data.orgao) {
            orgaoSelect.selectedIndex = i;
            break;
        }
    }

    console.log("Especialidade recebida:", data.especialidades);

var especialidadeSelect = document.getElementById('especialidade');
for (var j = 0; j < especialidadeSelect.options.length; j++) {
    console.log("Opção:", especialidadeSelect.options[j].value);
    if (especialidadeSelect.options[j].value === data.especialidades) {
        console.log("Correspondência encontrada para especialidade:", especialidadeSelect.options[j].value);
        especialidadeSelect.selectedIndex = j;
        break;
    }
}
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
        document.getElementById('assunto').value = '';
        document.getElementById('descricao').value = '';
        document.getElementById('acoes').value = '';
    }

    function formatarDataParaCampo(data) {
        if (!data) return '';
    
        // Separar a data em partes
        var partes = data.split('-');
        if (partes.length !== 3) return '';
    
        var ano = partes[0];
        var mes = partes[1];
        var dia = partes[2];
    
        // Retornar no formato dd/mm/aaaa
        return `${dia}/${mes}/${ano}`;
    }
});
