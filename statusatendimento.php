<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Status dos Atendimentos Médicos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/selectize.bootstrap5.min.css">
    <link rel="stylesheet" href="css/multi-select-tag.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .navbar {
            display: flex;
            justify-content: center;
            margin-bottom: 1px;
        }

        .navbar button {
            margin: 0 10px;
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .navbar button.active {
            background-color: #007bff;
            color: white;
        }

        #statusChart {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <main class="container mt-5">
    <div class="row align-items-center">
          <h1>Solicitações por status</h1>

  
    <div class="navbar" id="navbar">
        <!-- Os botões dos meses serão gerados dinamicamente aqui -->
    </div>   <canvas id="statusChart" width="800" height="200"></canvas>
    </div>   
 </main>
   
    <script>

        // Função para obter o mês atual no formato 'YYYY-MM'
function getMesAtual() {
    const dataAtual = new Date();
    const mes = (dataAtual.getMonth() + 1).toString().padStart(2, '0'); // Mês atual, ajustando para 2 dígitos
    const ano = dataAtual.getFullYear().toString(); // Ano atual
    return `${ano}-${mes}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const mesAtual = getMesAtual();
    atualizarGrafico(mesAtual); // Carregar gráfico com o mês atual ao carregar a página
});
        let statusChart;

        function atualizarGrafico(mes) {
            fetch(`pegarregistro.php?mes=${mes}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Para depuração

                    const ctx = document.getElementById('statusChart').getContext('2d');

                    // Calcular a quantidade total de atendimentos
                    const totalAberto = parseInt(data.Aberto) || 0;
                    const totalAnalize = parseInt(data.Analize) || 0;
                    const totalConcluido = parseInt(data.Concluido) || 0;
                    const totalAtendimentos = totalAberto + totalAnalize + totalConcluido;

                    const chartData = {
                        labels: ['Aberto', 'Analize', 'Concluido'],
                        datasets: [{
                            label: `Atendimentos (${totalAtendimentos})`,
                            data: [totalAberto, totalAnalize, totalConcluido],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };


                    if (statusChart) {
                        statusChart.destroy();
                    }

                    statusChart = new Chart(ctx, {
                        type: 'bar',
                        data: chartData,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Atualiza a URL com o mês selecionado
                    const url = new URL(window.location);
                    url.searchParams.set('mes', mes);
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Erro ao buscar dados:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar');
            const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

            // Cria os botões dos meses dinamicamente
            months.forEach((month, index) => {
                const button = document.createElement('button');
                button.textContent = month;
                button.addEventListener('click', function() {
                    // Remove a classe 'active' de todos os botões
                    navbar.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));

                    // Adiciona a classe 'active' ao botão clicado
                    this.classList.add('active');

                    // Atualiza o gráfico com o índice do mês + 1 (pois os meses são baseados em 1)
                    atualizarGrafico(`${new Date().getFullYear()}-${index + 1 < 10 ? '0' + (index + 1) : (index + 1)}`);
                });

                navbar.appendChild(button);
            });

            // Define o botão do mês atual como ativo ao carregar a página
            const currentMonth = new Date().getMonth();
            navbar.querySelector('button:nth-child(' + (currentMonth + 1) + ')').classList.add('active');

            // Carrega o gráfico com o mês da URL ou o mês atual ao carregar a página
            const urlParams = new URLSearchParams(window.location.search);
            const mesUrl = urlParams.get('mes') || `${new Date().getFullYear()}-${currentMonth + 1 < 10 ? '0' + (currentMonth + 1) : (currentMonth + 1)}`;
            atualizarGrafico(mesUrl);
        });
    </script>
</body>

</html>