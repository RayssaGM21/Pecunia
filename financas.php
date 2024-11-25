<?php
session_start();
require_once('conexao.php');

$sql = "
    SELECT mes.nome AS nome_mes, meses.ano, meses.id AS id_mes
    FROM meses
    JOIN mes ON meses.nome = mes.id
    ORDER BY meses.ano, mes.nome
";
$meses = mysqli_query($conn, $sql);

$financas = [];
if (isset($_POST['mes'])) {
    $idMes = $_POST['mes'];

    $sqlFinancas = "
        SELECT financas.*, categoria.nome AS nome_categoria
        FROM financas
        JOIN categoria ON financas.fk_categoria_id = categoria.id
        WHERE financas.fk_mes_id = $idMes
    ";
    $result = mysqli_query($conn, $sqlFinancas);
    $financas = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Global css -->
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/financas.css">
    <title>Pecunia</title>
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

    <div class="container mt-4">
        <form method="POST" action="financas.php" class="mb-4">
            <div class="mb-3">
                <label for="mes" class="form-label">Escolha um Mês e Ano</label>
                <select class="form-select" id="mes" name="mes" required>
                    <option value="">Selecione o Mês e Ano</option>
                    <?php
                    foreach ($meses as $row) {
                        $mesAno = $row['nome_mes'] . ' ' . $row['ano'];
                        echo "<option value='{$row['id_mes']}'>$mesAno</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Ver Finanças</button>
        </form>

        <?php if (!empty($financas)): ?>
            <h3 class="mt-5">
                Finanças do Mês Selecionado:
                <a href="financas-create.php?id=<?= $_POST['mes'] ?>" class="btn btn-light float-end">
                    <i class="bi bi-piggy-bank"></i> Adicionar Finança
                </a>
            </h3>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($financas as $financa): ?>
                        <tr>
                            <td><?= $financa['id'] ?></td>
                            <td><?= date('d/m/Y', strtotime($financa['data'])) ?></td>
                            <td><?= $financa['descricao'] ?></td>
                            <td class="<?= $financa['tipo'] ?>">R$ <?= number_format($financa['valor'], 2, ',', '.') ?></td>
                            <td class="<?= $financa['tipo'] ?>"><?= $financa['tipo'] ?></td>
                            <td><?= $financa['nome_categoria'] ?></td>
                            <td>
                                <a href="financas-edit.php?id=<?= $financa['id'] ?>" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="acoes.php" method="POST" class="d-inline">
                                    <button onclick="return confirm('Tem certeza que deseja excluir?')" name="delete_usuario" value="<?= $usuario['id'] ?>" type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['mes'])): ?>
            <div class="mensagem-fail-financas">
                <i class="bi bi-exclamation-circle" style="font-size: 60px;"></i>
                <h5> Não há finanças registradas para o mês selecionado</h5>
                <a href="financas-create.php?id=<?= $_POST['mes'] ?>" class="btn btn-light mt-3">
                    <i class="bi bi-calendar-plus"></i> Adicionar Finança
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>