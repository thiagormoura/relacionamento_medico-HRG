document.addEventListener('DOMContentLoaded', function() {
    let assunto = {
        repasse: '',
        admissao: '',
        atualizardados: ''
    };

    function verificarCamposPreenchidos() {
        if (Object.values(assunto).some(campo => campo.trim() !== '')) {
            console.log("At least one field is filled:", assunto);
        } else {
            console.log("No field is filled.");
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

        let situacaoatendimento;
        document.querySelectorAll('input[name="situacao_atendimento"]').forEach(function(elem) {
            elem.addEventListener('change', function(event) {
                situacaoatendimento = event.target.value;
            });
        });

        console.log({
            situacaoatendimento:situacaoatendimento,
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
            { campo: nome, nome: "Name" },
            { campo: email, nome: "Email" },
            { campo: date, nome: "Date" },
            { campo: registro, nome: "Registration" },
            { campo: orgao, nome: "Organ" },
            { campo: celular, nome: "Cellphone" },
            { campo: celulardois, nome: "Second Cellphone" },
            { campo: nascimento, nome: "Birthdate" },
            { campo: especialidade, nome: "Specialty Description" },
            { campo: descricao, nome: "Description" },
            { campo: tipo_atendimento, nome: "Attendance" },
            { campo: acoes, nome: "Actions" },
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
                title: "Registration Error",
                text: "Please fill in all required fields: " + camposNomes,
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
                situacaoatendimento:situacaoatendimento
            },
            success: function(response) {
                Swal.fire({
                    title: "Registration Successful!",
                    text: response,
                    icon: "success"
                });
            },
            error: function(error) {
                Swal.fire({
                    title: "Error",
                    text: "An error occurred while sending the registration.",
                    icon: "error"
                });
                console.error('AJAX request error:', error);
            }
        });
    }

    var enviarButton = document.getElementById("enviarbutton");
    enviarButton.addEventListener("click", function(event) {
        enviarFormulario(event);
    });
});
