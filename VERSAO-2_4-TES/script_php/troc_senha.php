<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once("conexao.php");
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$nova = isset($_POST['nova']) ? $_POST['nova'] : '';
if (empty($usuario) || empty($senha) || empty($nova)) {
  echo "Usuário,senha e nova senha são campos obrigatórios!";
  $pdo = null;
  exit();
}
$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email=:usuario OR cpf=:usuario) AND senha=:senha");
$query->bindValue(":usuario", $usuario);
$query->bindValue(":senha", $senha);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if ($total_reg > 0) {
  $faz = $res[0]['faz'];
  $cpf = $res[0]['cpf'];
  $email = $res[0]['email'];
  $query = $pdo->prepare("UPDATE usuarios SET senha=:nova WHERE faz=:faz AND cpf=:cpf AND email=:email");
  $query->bindValue(":nova", $nova);
  $query->bindValue(":faz", $faz);
  $query->bindValue(":cpf", $cpf);
  $query->bindValue(":email", $email);
  $query->execute();
  echo 'Sua senha foi trocada!';
} else {
  echo 'Senha ou usuário inexistente!';
}
$pdo = null;
