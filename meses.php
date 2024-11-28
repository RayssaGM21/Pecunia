<?php
session_start();
require_once('conexao.php');

$sql = "SELECT meses.*, mes.nome AS nome_mes
        FROM meses 
        JOIN mes ON meses.nome = mes.id";
$meses = mysqli_query($conn, $sql);

$sqlMes = "SELECT id, nome FROM mes ORDER BY id asc";
$mes = mysqli_query($conn, $sqlMes);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT meses.*, mes.nome AS nome_mes FROM meses JOIN mes ON meses.nome = mes.id WHERE meses.id = $id";

    $mesesList = mysqli_query($conn, $sql);
    $mesesList = mysqli_fetch_assoc($mesesList);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Global css -->
    <link rel="stylesheet" href="./css/global.css">
    <!-- Meses css -->
    <link rel="stylesheet" href="./css/meses.css">

    <title>Meses</title>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Listagem de Meses
                            <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-light float-end">
                                <i class="bi bi-calendar-plus"></i> Adicionar mês
                            </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Mês</th>
                                    <th>Ano</th>
                                    <th>Saldo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($meses as $mese): ?>
                                    <?php
                                    $status = 'positivo';
                                    if ($mese['saldo'] < 0) {
                                        $status = 'negativo';
                                    } elseif ($mese['saldo'] == 0) {
                                        $status = 'neutro';
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $mese['id'] ?></td>
                                        <td><?php echo $mese['nome_mes'] ?></td>
                                        <td><?php echo $mese['ano'] ?></td>
                                        <td class="<?= $status ?>">R$<?php echo number_format($mese['saldo'], 2, ',', '.') ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Ações
                                                </button>
                                                <div class="dropdown-menu">
                                                    <div class="ajuste-dropdown">
                                                        <a href="javascript:void(0);"
                                                            class="btn botao btn-sm edit-link"
                                                            data-id="<?= $mese['id'] ?>"
                                                            data-nome="<?= $mese['nome'] ?>"
                                                            data-ano="<?= $mese['ano'] ?>"
                                                            data-saldo="<?= $mese['saldo'] ?>">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                        <form action="acoes.php" method="POST" class="d-inline">
                                                            <button onclick="return confirm('Tem certeza que deseja excluir?')" name="delete_mes" value="<?= $mese['id'] ?>" type="submit" class="btn btn-danger btn-sm">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Mês</h5>
                </div>
                <div class="modal-body">
                    <form action="acoes.php" method="POST">
                        <input type="hidden" name="edit-id" id="edit-id">
                        <div class="mb-3">
                            <label for="txt-nome-mes-edit">Mês</label>
                            <select name="txt-nome-mes-edit" id="txt-nome-mes-edit" class="form-select">
                                <?php foreach ($mes as $m): ?>
                                    <option value="<?php echo $m['id']; ?>">
                                        <?php echo isset($mesesList) && $mesesList['nome'] == $m['id'] ? 'selected' : ''; ?>
                                        <?php echo $m['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="txt-ano-edit" class="form-label">Ano</label>
                            <input type="text" id="txt-ano-edit" class="form-control" name="txt-ano-edit">
                        </div>
                        <div class="mb-3">
                            <label for="txt-saldo-edit" class="form-label">Saldo</label>
                            <input type="text" id="txt-saldo-edit" class="form-control" name="txt-saldo-edit" disabled>
                        </div>
                        <div class="modal-footer">
                            <a href="meses.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" name="edit_mes" class="btn botao">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Adicionar Mês -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Adicionar Mês</h5>
                </div>
                <div class="modal-body">
                    <form action="acoes.php" method="POST">
                        <div class="mb-3">
                            <label for="txt-nome-mes">Mês</label>
                            <select name="txt-nome-mes" id="txt-nome-mes" class="form-select">
                                <?php foreach ($mes as $m): ?>
                                    <option value="<?php echo $m['id']; ?>">
                                        <?php echo $m['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="txt-ano" class="form-label">Ano</label>
                            <input type="text" id="txt-ano" class="form-control" name="txt-ano" placeholder="Selecione um ano...">
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="create-mes" class="btn botao">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#txt-ano-edit').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('#txt-ano').datepicker({
                format: 'yyyy',
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('.edit-link').click(function() {
                var id = $(this).data('id');
                var nome_mes = $(this).data('nome');
                var ano = $(this).data('ano');
                var saldo = $(this).data('saldo');

                $('#editModal #txt-nome-mes-edit').val(nome_mes);
                $('#editModal #txt-ano-edit').val(ano);
                $('#editModal #txt-saldo-edit').val(saldo);
                $('#editModal #edit-id').val(id);

                $('#editModal').modal('show');
            });
        });
    </script>
</body>

</html>
