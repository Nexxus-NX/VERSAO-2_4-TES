<?php

use PhpMyAdmin\Session;

require_once("conexao.php");
@session_start();
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email=:usuario or cpf=:usuario or username=:usuario) and senha=:senha");
$query->bindValue(":usuario", "$usuario");
$query->bindValue(":senha", "$senha");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pdo = null;
$total_reg = @count($res);
if ($total_reg > 0) {
  $_SESSION['faz'] = $res[0]['faz'];
  $_SESSION['nome'] = $res[0]['nome'];
  $_SESSION['email'] = $res[0]['email'];
  $_SESSION['cpf'] = $res[0]['cpf'];
  $_SESSION['senha'] = $res[0]['senha'];
  $_SESSION['nivel'] = $res[0]['nivel'];
  $tes = $_SESSION['faz'];
  if (in_array(@$_SESSION['nivel'], range(1, 7))) {
    echo "<script language='javascript'> window.location='../monitorar.php'; </script>";
  }
} else {
  echo "<script language='javascript'> alert('Usuário ou senha incorretos.'); window.location='../index.php'; </script>";
  session_destroy();
  exit();
}
