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
    $novoMesNome = intval(trim($_POST['txt-nome-mes-edit']));
    $novoAno = mysqli_real_escape_string($conn, trim($_POST['txt-ano-edit']));

    mysqli_begin_transaction($conn);

    try {
        $sqlUpdateMes = "UPDATE meses SET nome = $novoMesNome, ano = '$novoAno' WHERE id = $idMes";
        if (!mysqli_query($conn, $sqlUpdateMes)) {
            throw new Exception("Erro ao atualizar o mês: " . mysqli_error($conn));
        }

        $sqlUpdateFinancas = "
            UPDATE financas f
            JOIN meses m ON f.fk_mes_id = m.id
            SET f.data = DATE_FORMAT(f.data, CONCAT('$novoAno-', LPAD($novoMesNome, 2, '0'), '-%d'))
            WHERE f.fk_mes_id = $idMes
        ";
        if (!mysqli_query($conn, $sqlUpdateFinancas)) {
            throw new Exception("Erro ao atualizar as datas das finanças: " . mysqli_error($conn));
        }

        mysqli_commit($conn);
        header('Location: meses.php');
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
    }
}


if (isset($_POST['delete_mes'])) {
    $idMes = mysqli_real_escape_string($conn, $_POST['delete_mes']);
    $sql = "DELETE FROM meses WHERE id = '$idMes'";

    mysqli_query($conn, $sql);
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
    $idCategoria = mysqli_real_escape_string($conn, $_POST['delete_categoria']);
    $sql = "DELETE FROM categoria WHERE id = '$idCategoria'";

    mysqli_query($conn, $sql);
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
    mysqli_query($conn, $sql);

    $sqlBuscarSaldo = "SELECT saldo FROM meses WHERE id = $idMes";
    $resultadoSaldo = mysqli_query($conn, $sqlBuscarSaldo);
    $saldoAtual = 0;
    if ($resultadoSaldo && mysqli_num_rows($resultadoSaldo) > 0) {
        $saldoMes = mysqli_fetch_assoc($resultadoSaldo);
        $saldoAtual = $saldoMes['saldo'];
    }
    if ($tipo == 'ENTRADA') {
        $novoSaldo = $saldoAtual + $valor;
    } elseif ($tipo == 'SAÍDA') {
        $novoSaldo = $saldoAtual - $valor;
    }
    $sqlUpdateSaldo = "UPDATE meses SET saldo = $novoSaldo WHERE id = $idMes";
    mysqli_query($conn, $sqlUpdateSaldo);
    header('Location: financas.php');
    exit();
}


if (isset($_POST['edit_financa'])) {
    $idFinanca = mysqli_real_escape_string($conn, $_POST['edit-id']);
    $valor = intval(str_replace(['R$', ' ', '.'], '', $_POST['txt-valor']));
    $descricao = trim($_POST['txt-descricao']);
    $data = trim($_POST['txt-data']);
    $tipo = trim($_POST['txt-tipo']);
    $categoriaId = mysqli_real_escape_string($conn, $_POST['txt-categoria']);
    $idMes = trim($_POST['edit-id-mes']);

    $sql = "UPDATE financas 
            SET valor = '$valor', descricao = '$descricao', data = '$data', tipo = '$tipo', fk_categoria_id = '$categoriaId' 
            WHERE id = '$idFinanca'";
    mysqli_query($conn, $sql);

    $sqlBuscarSaldo = "SELECT saldo FROM meses WHERE id = $idMes";
    $resultadoSaldo = mysqli_query($conn, $sqlBuscarSaldo);
    $saldoAtual = 0;
    if ($resultadoSaldo && mysqli_num_rows($resultadoSaldo) > 0) {
        $saldoMes = mysqli_fetch_assoc($resultadoSaldo);
        $saldoAtual = $saldoMes['saldo'];
    }
    if ($tipo == 'ENTRADA') {
        $novoSaldo = $saldoAtual + $valor;
    } elseif ($tipo == 'SAÍDA') {
        $novoSaldo = $saldoAtual - $valor;
    }
    $sqlUpdateSaldo = "UPDATE meses SET saldo = $novoSaldo WHERE id = $idMes";
    mysqli_query($conn, $sqlUpdateSaldo);
    header('Location: financas.php');
    exit();
}

if (isset($_POST['delete_financa'])) {
    $idFinanca = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM financas WHERE id = '$idFinanca'";
    echo $sql;
    mysqli_query($conn, $sql);
    header('Location: financas.php');
    exit();
}
    
