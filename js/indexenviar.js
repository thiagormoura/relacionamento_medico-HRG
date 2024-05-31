
function validateForm() {
    var isValid = true;
    var requiredFields = ['date', 'orgao', 'assunto', 'nome', 'registro', 'celular', 'email', 'descricao', 'acoes'];

    requiredFields.forEach(function(field) {
        var input = document.getElementById(field);
        if (!input.value) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    var email = document.getElementById('email');
    var emailValue = email.value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(emailValue)) {
        email.classList.add('is-invalid');
        isValid = false;
    } else {
        email.classList.remove('is-invalid');
    }

    console.log('Formulário é válido?', isValid);
    return isValid;
}


function enviarFormulario() {
    // Validar os campos do formulário
    if (validateForm(isValid)) {

        $.ajax({
            url: 'forms/enviarindexbanco.php', // Verifique se o caminho está correto
            type: 'POST',
            data: $('#occurrenceForm').serialize(), // Serialize o formulário para enviar os dados
            success: function(response) {
                // Exibir uma mensagem de sucesso
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response, // Exemplo: exibir a resposta do PHP (como "Dados inseridos com sucesso!")
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirecionar para a página de histórico após clicar em "OK"
                        window.location.href = '../php/historico.php'; // Verifique se o caminho está correto
                        console.log('Redirecionamento para histórico após confirmação');
                    }
                });
            },
            error: function(xhr, status, error) {
                // Lidar com erros de requisição, se necessário
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Ocorreu um erro ao enviar o formulário. Por favor, tente novamente.', // Mensagem de erro padrão
                    confirmButtonText: 'OK'
                });
                console.error(xhr.responseText); // Exemplo: exibir o erro retornado pelo PHP
            }
        });
    } else {
        // Se o formulário não for válido, exibir uma mensagem de erro
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Por favor, preencha todos os campos obrigatórios.', // Mensagem de erro para campos não preenchidos
            confirmButtonText: 'OK'
        });
    }
}
