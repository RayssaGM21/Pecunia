<?php
session_start();
require_once('conexao.php');

$sql = 'SELECT * FROM categoria';
$categoria = mysqli_query($conn, $sql);

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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Listagem de Categorias
                            <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-light float-end">
                                <i class="bi bi-bookmark-plus"></i> Adicionar Categoria
                            </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php include('message.php') ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categoria as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['id'] ?></td>
                                        <td><?php echo $cat['nome'] ?></td>
                                        <td><?php echo $cat['descricao'] ?></td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                class="btn btn-secondary btn-sm edit-link"
                                                data-id="<?= $cat['id'] ?>"
                                                data-nome="<?= $cat['nome'] ?>"
                                                data-descricao="<?= $cat['descricao'] ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="acoes.php" method="POST" class="d-inline">
                                                <button onclick="return confirm('Tem certeza que deseja excluir?')" name="delete_categoria" value="<?= $cat['id'] ?>" type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                                            </form>
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
                    <h5 class="modal-title" id="editModalLabel">Editar Categoria</h5>
                </div>
                <div class="modal-body">
                    <form action="acoes.php" method="POST">
                        <input type="hidden" name="edit-id" id="edit-id">
                        <div class="mb-3">
                            <label for="txt-nome-categoria-edit">Nome</label>
                            <input type="text" id="txt-nome-categoria-edit" class="form-control" name="txt-nome-categoria-edit">
                        </div>
                        <div class="mb-3">
                            <label for="txt-descricao-categoria-edit" class="form-label">Descrição</label>
                            <textarea type="text" id="txt-descricao-categoria-edit" class="form-control" 
                            name="txt-descricao-categoria-edit" placeholder="Digite a descrição da categoria..."
                            ></textarea>
                        </div>
                        <div class="modal-footer">
                            <a href="meses.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" name="edit_categoria" class="btn botao">Salvar</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Adicionar Categoria</h5>
                </div>
                <div class="modal-body">
                    <form action="acoes.php" method="POST">
                        <div class="mb-3">
                            <label for="txt-nome-categoria">Nome</label>
                            <input type="text" id="txt-nome-categoria" class="form-control" name="txt-nome-categoria" placeholder="Digite o nome da categoria..." required>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="txt-descricao-categoria-edit" class="form-label">Descrição</label>
                            <textarea type="text" id="txt-descricao-categoria-edit" class="form-control" name="txt-descricao-categoria-edit" placeholder="Digite a descrição da categoria..."></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="create-categoria" class="btn botao">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.edit-link').click(function() {
                var id = $(this).data('id');
                var nome_categoria = $(this).data('nome');
                var descricao_categoria = $(this).data('descricao');

                $('#editModal #txt-nome-categoria-edit').val(nome_categoria);
                $('#editModal #txt-descricao-categoria-edit').val(descricao_categoria);
                $('#editModal #edit-id').val(id);

                $('#editModal').modal('show');
            });
        });
    </script>
</body>

</html>
