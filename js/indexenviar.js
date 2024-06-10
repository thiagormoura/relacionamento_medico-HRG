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

    // Definindo a função enviarFormulario
    function enviarFormulario(event) {
        event.preventDefault(); // Previne o comportamento padrão do envio do formulário

        var nome = document.getElementById("nome").value;
        var cpf = document.getElementById("cpf").value;
        var nascimento = document.getElementById("nascimento").value;
        var celular = document.getElementById("celular").value;
        var email = document.getElementById("email").value;
        var especialidade = document.getElementById("especialidade").value;
        var registro = document.getElementById("registro").value;
        var orgao = document.getElementById("orgao").value;
        var date = document.getElementById("date").value;
        var descricao = document.getElementById("descricao").value;
        var acoes = document.getElementById("acoes").value;

        // SITUACAO DO ATENDIMENTO
        let situacaoAtendimento;
        document.querySelectorAll('input[name="situacao_atendimento"]').forEach(function(elem) {
            elem.addEventListener('change', function(event) {
                situacaoAtendimento = event.target.value;
                console.log("Situação Atendimento selecionada:", situacaoAtendimento);
                //ABERTO -  FECHADO-  ANDAMENTO
            });
        });

        // Log dos valores preenchidos no formulário
        console.log({
            assunto: assunto,
            date: date,
            nome: nome,
            registro: registro,
            orgao: orgao,
            celular: celular,
            nascimento: nascimento,
            email: email,
            descricaoespecialidades: especialidade,
            descricao: descricao,
            acoes: acoes
        });

        // Lista de campos obrigatórios
        var camposObrigatorios = [
            { campo: nome, nome: "Nome" },
            { campo: email, nome: "Email" },
            { campo: date, nome: "Data" },
            { campo: registro, nome: "Registro" },
            { campo: orgao, nome: "Órgão" },
            { campo: celular, nome: "Celular" },
            { campo: nascimento, nome: "Nascimento" },
            { campo: especialidade, nome: "Descrição das Especialidades" },
            { campo: descricao, nome: "Descrição" },
            { campo: acoes, nome: "Ações" },
            { campo: cpf, nome: "Cpf" }
        ];

        // Verificação de campos obrigatórios
        var camposVazios = camposObrigatorios.filter(function(campo) {
            return campo.campo.trim() === "";
        });

        if (camposVazios.length > 0) {
            var camposNomes = camposVazios.map(function(campo) {
                return campo.nome;
            }).join(", ");

            Swal.fire({
                title: "Erro no registro",
                text: "Preencha todos os campos obrigatórios: " + camposNomes,
                icon: "error"
            });
            return; // Aborta o envio do formulário se houver campos obrigatórios vazios
        }

        // Envio dos dados via AJAX
        $.ajax({
            url: 'enviarindexbanco.php', // Substitua 'seu_arquivo_php.php' pelo caminho do seu arquivo PHP de destino
            method: 'POST',
            data: {
                repasse: assunto.repasse,
                atualizardados: assunto.atualizardados,
                admissao: assunto.admissao,
                date: date,
                situacaoAtendimento: situacaoAtendimento,
                nome: nome,
                registro: registro,
                orgao: orgao,
                celular: celular,
                nascimento: nascimento,
                email: email,
                especialidade: especialidade,
                descricao: descricao,
                acoes: acoes,
                cpf: cpf
            },
            success: function(response) {
                Swal.fire({
                    title: "Registro enviado com sucesso!",
                    text: response,
                    icon: "success"
                });
            },
            error: function(error) {
                Swal.fire({
                    title: "Erro",
                    text: "Ocorreu um erro ao enviar o registro.",
                    icon: "error"
                });
                console.error('Erro na solicitação AJAX:', error);
            }
        });
    }

    // Adiciona o evento de clique ao botão de envio
    var enviarButton = document.getElementById("enviarbutton");
    enviarButton.addEventListener("click", function(event) {
        enviarFormulario(event);
    });
});
