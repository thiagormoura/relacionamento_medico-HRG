// Definindo a função enviarFormulario
function enviarFormulario() {
    var date = document.getElementById("date").value;
    var status = document.getElementById("status").value;
    var nome = document.getElementById("nome").value;
    var registro = document.getElementById("registro").value;
    var estado = document.getElementById("estado").value;
    var orgao = document.getElementById("orgao").value;
    var celular = document.getElementById("celular").value;
    var nascimento = document.getElementById("nascimento").value;
    var email = document.getElementById("email").value;
    var descricaoespecialidades = document.getElementById("descricaoespecialidades").value;
    var descricao = document.getElementById("descricao").value;
    var acoes = document.getElementById("acoes").value;




    // Log dos valores preenchidos no formulário
    console.log({
        date: date,
        status: status,
        nome: nome,
        registro: registro,
        estado: estado,
        orgao: orgao,
        celular: celular,
        nascimento: nascimento,
        email: email,
        descricaoespecialidades: descricaoespecialidades,
        descricao: descricao,
        acoes: acoes
    });

    // Lista de campos obrigatórios
    var camposobrigatorios = [
        { campo: nome, nome: "Nome" },
        { campo: email, nome: "Email" },
        { campo: date, nome: "Data" },
        { campo: registro, nome: "Registro" },
        { campo: estado, nome: "Estado" },
        { campo: orgao, nome: "Órgão" },
        { campo: celular, nome: "Celular" },
        { campo: nascimento, nome: "Nascimento" },
        { campo: descricaoespecialidades, nome: "Descrição das Especialidades" },
        { campo: descricao, nome: "Descrição" },
        { campo: acoes, nome: "Ações" }
    ];

    // Verificação de campos obrigatórios
    var camposVazios = camposobrigatorios.filter(function(campo) {
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
        return;
        // Aborta o envio do formulário se houver campos obrigatórios vazios
    }

   

    $.ajax({
        url: 'enviarindexbanco.php', // Substitua 'seu_arquivo_php.php' pelo caminho do seu arquivo PHP de destino
        method: 'POST',
        data: {
            date: date,
            status: status,
            nome: nome,
            registro: registro,
            estado: estado,
            orgao: orgao,
            celular: celular,
            nascimento: nascimento,
            email: email,
            descricaoespecialidades: descricaoespecialidades,
            descricao: descricao,
            acoes: acoes
        },
        success: function (response) {
            Swal.fire({
                title: "Registro enviado com sucesso!",
                text: response,
                icon: "success"
               
            }); 
            setTimeout(function() {
                window.location.href = 'historico.php' ;
                
            }, 1500);
        },
        error: function (error) {
            Swal.fire({
                title: "Erro",
                text: "Ocorreu um erro ao enviar o registro.",
                icon: "error"
            });
            console.error('Erro na solicitação AJAX:', error);
        }
    });
}

var enviarButton = document.getElementById("enviarbutton");

enviarButton.addEventListener("click", function() {
    enviarFormulario();
});
