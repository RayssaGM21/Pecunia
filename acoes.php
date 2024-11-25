<?php
session_start();
require_once('conexao.php');

if (isset($_POST['create-mes'])) {
    $nomeMes = trim($_POST['txt-nome-mes']);
    $ano = trim($_POST['txt-ano']);

    $sql = "INSERT INTO meses (nome, ano) VALUES ('$nomeMes', '$ano')";
    mysqli_query($conn, $sql);
    header('Location: meses.php');
    exit();
}

if (isset($_POST['edit_mes'])) {
    $idMes = mysqli_real_escape_string($conn, $_POST['edit-id']);
    $nomeMes = trim($_POST['txt-nome-mes-edit']);
    $ano = trim($_POST['txt-ano-edit']);

    $sql = "UPDATE meses SET nome = '$nomeMes', ano = '$ano' WHERE id = '$idMes'";
    mysqli_query($conn, $sql);
    header('Location: meses.php');
    exit();
}

if (isset($_POST['delete_mes'])) {
    $mesId = mysqli_real_escape_string($conn, $_POST['delete_mes']);
    $sql = "DELETE FROM meses WHERE id = '$mesId'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['message'] = "Mês com ID {$mesId} excluído com sucesso!";
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['message'] = "Ops! Não foi possível excluir o mês";
        $_SESSION['type'] = 'error';
    }
    header('Location: meses.php');
    exit();
}

if (isset($_POST['create-categoria'])) {
    $nomeCategoria = trim($_POST['txt-nome-categoria']);
    $descricaoCategoria = trim(($_POST['txt-descricao-categoria']));
    $sql = "INSERT INTO categoria (nome, descricao) VALUES ('$nomeCategoria', '$descricaoCategoria')";
    mysqli_query($conn, $sql);
    header('Location: categoria.php');
    exit();
}

if (isset($_POST['edit_categoria'])) {
    $idCategoria = mysqli_real_escape_string($conn, $_POST['edit-id']);
    $nomeCategoria = trim($_POST['txt-nome-categoria-edit']);
    $descricaoCategoria = trim($_POST['txt-descricao-categoria-edit']);

    $sql = "UPDATE categoria SET nome = '$nomeCategoria', descricao = '$descricaoCategoria' WHERE id = '$idCategoria'";
    mysqli_query($conn, $sql);
    header('Location: categoria.php');
    exit();
}

if (isset($_POST['delete_categoria'])) {
    $categoriaId = mysqli_real_escape_string($conn, $_POST['delete_categoria']);
    $sql = "DELETE FROM categoria WHERE id = '$categoriaId'";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['message'] = "Categoria com ID {$categoriaId} excluído com sucesso!";
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['message'] = "Ops! Não foi possível excluir a categoria";
        $_SESSION['type'] = 'error';
    }
    header('Location: categoria.php');
    exit();
}


if (isset($_POST['create-financa'])) {
    $tipo = trim($_POST['txt-tipo']);
    $data = $_POST['txt-data'];
    $descricao = trim($_POST['txt-descricao']);
    $valor = intval(str_replace(['R$', ' ', '.'], '', $_POST['txt-valor']));
    $categoriaId = (int) $_POST['txt-categoria'];
    $idMes = trim($_POST['fk_mes_id']);

    $sql = "INSERT INTO financas (tipo, data, descricao, valor, fk_categoria_id, fk_mes_id) 
            VALUES ('$tipo', '$data', '$descricao', '$valor', $categoriaId, $idMes)";
    // echo $sql;
    mysqli_query($conn, $sql);
    header('Location: financas.php');
    exit();

    // if (mysqli_query($conn, $sql)) {

    // } else {
    //     echo "Erro ao inserir a finança: " . mysqli_error($conn);
    // }
}
