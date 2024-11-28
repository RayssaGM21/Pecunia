<?php
session_start();
require_once('conexao.php');

$sql_resumo = "
SELECT
   SUM(CASE WHEN tipo = 'ENTRADA' THEN valor ELSE 0 END) AS receitas,
   SUM(CASE WHEN tipo = 'SAÍDA' THEN valor ELSE 0 END) AS despesas,
   (SUM(CASE WHEN tipo = 'ENTRADA' THEN valor ELSE 0 END) - 
   SUM(CASE WHEN tipo = 'SAÍDA' THEN valor ELSE 0 END)) AS saldo
FROM financas";
$result_resumo = mysqli_query($conn, $sql_resumo);
$resumo = mysqli_fetch_assoc($result_resumo);

$sql_categoria = "
    SELECT 
        c.nome AS categoria,
        SUM(CASE WHEN f.tipo = 'ENTRADA' THEN f.valor ELSE 0 END) AS entradas,
        SUM(CASE WHEN f.tipo = 'SAÍDA' THEN f.valor ELSE 0 END) AS saídas
    FROM financas f
    LEFT JOIN categoria c ON f.fk_categoria_id = c.id
    GROUP BY c.nome";
$result_categoria = mysqli_query($conn, $sql_categoria);

$categorias = [];
$entradas = [];
$saidas = [];

while ($row = mysqli_fetch_assoc($result_categoria)) {
    $categorias[] = $row['categoria'];
    $entradas[] = (float) $row['entradas'];
    $saidas[] = (float) $row['saídas'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Index css -->
    <link href="./css/index.css" rel="stylesheet">

    <title>Pecunia Sistema</title>

</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="d-flex ms-5">
            <div class="mx-3">
                <a class="navbar-brand" href="index.php">
                    <img src="./img/pecunia_logo.png" alt="Logo Pecunia" class="pecunia">
                </a>
            </div>
            <ul class="d-flex list-unstyled mb-0 justify-content-between align-items-center w-100 gap-3 ms-5">
                <li><a href="financas.php" class="items">Finanças</a></li>
                <li><a href="meses.php" class="items">Meses</a></li>
                <li><a href="categoria.php" class="items">Categoria</a></li>
            </ul>
        </div>
    </nav>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="center">
                    <h2><i class="bi bi-cash-coin"></i> Resumo Financeiro</h2>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <span class="text">
                        <p class="text-success"><strong><i class="bi bi-arrow-up-circle-fill"></i> Receitas Totais:</strong></p>
                        <h3>R$ <?php echo number_format($resumo['receitas'], 2, ',', '.'); ?></h3>
                    </span>
                </li>
                <li>
                    <span class="text">
                        <p class="text-danger"><strong><i class="bi bi-arrow-down-circle-fill"></i> Despesas Atuais:</strong></p>
                        <h3>R$ <?php echo number_format($resumo['despesas'], 2, ',', '.'); ?></h3>
                    </span>
                </li>
                <li>
                    <span class="text">
                        <p class="text-primary"><strong><i class="bi bi-piggy-bank-fill"></i> Saldo Atual:</strong></p>
                        <h3>R$ <?php echo number_format($resumo['saldo'], 2, ',', '.'); ?></h3>
                    </span>
                </li>
            </ul>

            <div class="container mt-5">
                <h3 class="text-center">Distribuição de Entradas e Saídas por Categoria</h3>
                <div id="grafico">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </main>
    </section>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const categorias = <?php echo json_encode($categorias); ?>;
        const entradas = <?php echo json_encode($entradas); ?>;
        const saidas = <?php echo json_encode($saidas); ?>;

        const ctx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: categorias,
                datasets: [{
                    label: 'Distribuição por Categoria',
                    data: entradas.concat(saidas),
                    backgroundColor: ['#28a745', '#dc3545'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const categoria = categorias[tooltipItem.dataIndex];
                                const valor = tooltipItem.raw;
                                const tipo = tooltipItem.datasetIndex === 0 ? 'Entrada' : 'Saída';
                                return `${tipo}: R$ ${valor.toFixed(2)} (${categoria})`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>