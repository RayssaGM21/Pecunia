<?php
session_start();
require_once('conexao.php');

$idMes = isset($_GET['id']) ? $_GET['id'] : null;
if ($idMes) {
    $sql = "SELECT mes.nome AS nome_mes, meses.ano, mes.id AS numero_mes
            FROM meses
            JOIN mes ON meses.nome = mes.id
            WHERE meses.id = $idMes";
    $resultadoMes = mysqli_query($conn, $sql);
    $mes = mysqli_fetch_assoc($resultadoMes);

    if ($mes) {
        $mesNome = $mes['nome_mes'];
        $ano = $mes['ano'];
        $numeroMes = $mes['numero_mes'];
        $minDate = sprintf("%04d-%02d-01", $ano, $numeroMes);
        $maxDate = date("Y-m-t", strtotime($minDate));
    }
} else {
    $minDate = $maxDate = null;
}
$sqlCategorias = "SELECT * FROM categoria";
$categorias = mysqli_query($conn, $sqlCategorias);
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
    <!-- Finanças css -->
    <link rel="stylesheet" href="./css/financas.css">

    <title>Criar Finança</title>
</head>

<body>
    <nav class="navbar navbar-light bg-light mb-5">
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

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="acoes.php" method="POST">
                            <input type="hidden" name="fk_mes_id" value="<?php echo $idMes; ?>">
                            <div class="mb-3">
                                <label for="txt-valor">Valor</label>
                                <input type="text" name="txt-valor" id="txt-valor" class="form-control" required oninput="formatarValor()" placeholder="R$ 0,00">
                            </div>

                            <div class="mb-3">
                                <label for="txt-descricao" class="form-label">Descrição</label>
                                <textarea name="txt-descricao" id="txt-descricao" class="form-control" placeholder="Digite a descrição da finança..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="txt-data">Data</label>
                                <input type="date" name="txt-data" id="txt-data" class="form-control"
                                    min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="txt-tipo">Tipo</label>
                                <select name="txt-tipo" id="txt-tipo" class="form-select" required>
                                    <option value="">Selecione o Tipo</option>
                                    <option value="ENTRADA">ENTRADA</option>
                                    <option value="SAÍDA">SAÍDA</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="txt-categoria">Categoria</label>
                                <select name="txt-categoria" id="txt-categoria" class="form-select" required>
                                    <option value="">Selecione a Categoria</option>
                                    <?php
                                    foreach ($categorias as $categoria) {
                                        echo "<option value='{$categoria['id']}'>{$categoria['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3" style="display: flex; gap: 8px; justify-content: end;">
                                <a href="financas.php" class="btn btn-outline-secondary float-end">Cancelar</a>
                                <button type="submit" name="create-financa" class="btn botao float-end">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="./formataValor.js"></script>

</body>

</html>