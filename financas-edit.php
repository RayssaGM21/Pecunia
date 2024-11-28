<?php
session_start();
require_once('conexao.php');

$idFinanca = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($idFinanca) {
    $sql = "SELECT financas.valor, 
                   financas.descricao, 
                   financas.data, 
                   financas.tipo, 
                   financas.fk_mes_id, 
                   financas.fk_categoria_id, 
                   categoria.nome AS nome_categoria, 
                   meses.nome AS id_mes, 
                   meses.ano
            FROM financas
            JOIN categoria ON financas.fk_categoria_id = categoria.id
            JOIN meses ON financas.fk_mes_id = meses.id
            WHERE financas.id = $idFinanca";
    
    $resultadoFinanca = mysqli_query($conn, $sql);

    if ($resultadoFinanca && mysqli_num_rows($resultadoFinanca) > 0) {
        $financa = mysqli_fetch_assoc($resultadoFinanca);
        $valor = $financa['valor'];
        $descricao = $financa['descricao'];
        $data = $financa['data'];
        $tipo = $financa['tipo'];
        $fkMesId = $financa['fk_mes_id'];
        $fkCategoriaId = $financa['fk_categoria_id'];
        $nomeCategoria = $financa['nome_categoria'];
        $mesId = $financa['id_mes'];
        $ano = $financa['ano'];

        $minDate = sprintf("%04d-%02d-01", $ano, $mesId);
        $maxDate = date("Y-m-t", strtotime($minDate));
    }
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

    <title>Editar Finança</title>
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
                            <input type="hidden" name="edit-id" value="<?php echo $idFinanca; ?>">
                            <input type="hidden" name="edit-id-mes" value="<?php echo $fkMesId; ?>">
                            <div class="mb-3">
                                <label for="txt-valor">Valor</label>
                                <input type="text" name="txt-valor" id="txt-valor" class="form-control" value="<?php echo $valor; ?>" required oninput="formatarValor()" placeholder="R$ 0,00">
                            </div>

                            <div class="mb-3">
                                <label for="txt-descricao" class="form-label">Descrição</label>
                                <textarea name="txt-descricao" id="txt-descricao" class="form-control" placeholder="Digite a descrição da finança..." required><?php echo $descricao; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="txt-data">Data</label>
                                <input type="date" name="txt-data" id="txt-data" class="form-control"
                                    value="<?php echo $data; ?>" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="txt-tipo">Tipo</label>
                                <select name="txt-tipo" id="txt-tipo" class="form-select" required>
                                    <option value="ENTRADA" <?php echo $tipo == 'ENTRADA' ? 'selected' : ''; ?>>ENTRADA</option>
                                    <option value="SAÍDA" <?php echo $tipo == 'SAÍDA' ? 'selected' : ''; ?>>SAÍDA</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="txt-categoria">Categoria</label>
                                <select name="txt-categoria" id="txt-categoria" class="form-select" required>
                                    <option value="">Selecione a Categoria</option>
                                    <?php
                                    while ($categoria = mysqli_fetch_assoc($categorias)) {
                                        $selected = $categoria['id'] == $fkCategoriaId ? 'selected' : '';
                                        echo "<option value='{$categoria['id']}' $selected>{$categoria['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3" style="display: flex; gap: 8px; justify-content: end;">
                                <a href="financas.php" class="btn btn-outline-secondary float-end">Cancelar</a>
                                <button type="submit" name="edit_financa" class="btn botao float-end">Salvar</button>
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
