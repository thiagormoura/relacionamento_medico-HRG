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

    function formatarDataExibicao(data) {
        if (!data) return '';
        // Convertendo a data para formato ISO para garantir consistência
        var dataObj = new Date(data + 'T00:00:00'); // Adicionando 'T00:00:00' para garantir a hora zero
        var dia = dataObj.getDate().toString().padStart(2, '0');
        var mes = (dataObj.getMonth() + 1).toString().padStart(2, '0');
        var ano = dataObj.getFullYear();
        return ano + '-' + mes + '-' + dia; // Formato yyyy-MM-dd
    }

    // Função para preencher os campos do formulário com os dados recebidos
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
        document.getElementById('endereco').value = '';
    }

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
    console.log("CPF a ser enviado:", cpf); // Adicione esta linha para depuração

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

                    // Formatar e exibir data de nascimento no formato dia/mês/ano
                    if (response.data && response.data.data_nascimento) {
                        document.getElementById('nascimento').value = formatarDataExibicao(response.data.data_nascimento);
                    }
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

    // Evento para enviar o formulário
    var enviarButton = document.getElementById("enviarbutton");
    enviarButton.addEventListener("click", function(event) {
        enviarFormulario(event);
    });

    // Função para enviar o formulário
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
        var status = document.getElementById("status").value || '';
        var endereco = document.getElementById("endereco").value || '';
        var descricao = document.getElementById("descricao").value || '';
        var acoes = document.getElementById("acoes").value || '';
        var assuntosselecionados = document.getElementById("selectedIds").value || '';
       


        var assuntoatendimento = document.getElementById("assuntoatendimento").value || '';
        let veiculoselecionado = document.querySelector('input[name="veiculo"]:checked').value;
        // Obter o valor do campo de texto "Outros" se estiver visível e concatenar
        if (veiculoselecionado === 'Outros') {
            veiculoselecionado += ': ' + document.getElementById('outro').value;
        }
        // Exemplo de uso:
        console.log(veiculoselecionado);


        
        console.log("Assuntos de atendimento:", assuntoatendimento);
        


        
        console.log({
            assunto: assunto,
            date: date,
            nome: nome,
            crm: registro,
            orgao: orgao,
            celular: celular,
            celulardois: celulardois,
            nascimento: nascimento,
            email: email,
            especialidade: especialidade,
            descricao: descricao,
            acoes: acoes,
            cpf: cpf,
            assuntoatendimento:assuntoatendimento
        });

        var camposObrigatorios = [
            { campo: nome, nome: "Nome" },
            { campo: email, nome: "Email" },
            { campo: registro, nome: "Registro" },
            { campo: orgao, nome: "Órgão" },
            { campo: celular, nome: "Celular" },
            { campo: celulardois, nome: "Segundo Celular" },
            { campo: nascimento, nome: "Data de Nascimento" },
            { campo: especialidade, nome: "Descrição da Especialidade" },
            { campo: descricao, nome: "Descrição" },
            { campo: acoes, nome: "Ações" },
            { campo: cpf, nome: "CPF" },
            { campo: assuntoatendimento, nome: "Assunto Atendimento" }


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
                assuntoatendimento: assuntoatendimento,
                status: status,
                cpf: cpf,
                nome: nome,
                registro: registro,
                orgao: orgao,
                celular: celular,
                celulardois: celulardois,
                nascimento: nascimento, // Enviar a data formatada para o backend
                email: email,
                endereco: endereco,
                especialidade: especialidade,
                descricao: descricao,
                acoes: acoes,
                veiculoselecionado: veiculoselecionado, // Enviar a string JSON
                date :date ,
                assuntosselecionados:assuntosselecionados
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
});

document.getElementById('enviarbutton').addEventListener('click', function(event) {
    if (!validateNumbers()) {
        event.preventDefault();
    }
});

function validateNumbers() {
    const celular = document.getElementById('celular');
    const celulardois = document.getElementById('celulardois');
    const celularFeedback = document.getElementById('celularFeedback');
    const celulardoisFeedback = document.getElementById('celulardoisFeedback');

    let isValid = true;

    if (celular.value.length !== 11) {
        celular.classList.add('is-invalid');
        celularFeedback.style.display = 'block';
        isValid = false;
    } else {
        celular.classList.remove('is-invalid');
        celularFeedback.style.display = 'none';
    }

    if (celulardois.value.length !== 11) {
        celulardois.classList.add('is-invalid');
        celulardoisFeedback.style.display = 'block';
        isValid = false;
    } else {
        celulardois.classList.remove('is-invalid');
        celulardoisFeedback.style.display = 'none';
    }

    return isValid;
}
