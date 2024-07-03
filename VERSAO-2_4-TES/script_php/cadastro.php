<?php
require_once("conexao.php");
$faz2 = $_POST['faz2'] ?? null;
$nome = $_POST['nome'] ?? null;
$email = $_POST['email'] ?? null;
$cpf = $_POST['cpf'] ?? null;
$senha = $_POST['senha'] ?? null;
$nivel = $_POST['nivel'] ?? null;
$camposObrigatorios = [];
if (empty($nome)) {
  $camposObrigatorios[] = "nome";
}
if (empty($email)) {
  $camposObrigatorios[] = "email";
}
if (empty($cpf)) {
  $camposObrigatorios[] = "CPF/CNPJ";
}
if (empty($senha)) {
  $camposObrigatorios[] = "senha";
}
if (!empty($camposObrigatorios)) {
  $tx = implode(', ', $camposObrigatorios) . " são campos obrigatórios!";
  echo $tx;
  $pdo = null;
  exit();
}
$query = $pdo->prepare("SELECT * FROM usuarios WHERE faz=:faz OR email=:email OR cpf=:cpf");
$query->execute(array(':faz' => $faz2, ':email' => $email, ':cpf' => $cpf));
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if ($res) {
  foreach ($res as $row) {
    if ($cpf == $row['cpf']) {
      echo 'CPF já cadastrado!';
      $pdo = null;
      exit();
    }
    if ($email == $row['email']) {
      echo 'Email já cadastrado!';
      $pdo = null;
      exit();
    }
  }
}
$queryInsert = $pdo->prepare("INSERT INTO `usuarios`(`faz`,`nome`,`email`,`cpf`,`senha`,`nivel`) VALUES (:faz, :nome, :email, :cpf, :senha, :nivel)");
$queryInsert->bindParam(":faz", $faz2);
$queryInsert->bindParam(":nome", $nome);
$queryInsert->bindParam(":email", $email);
$queryInsert->bindParam(":cpf", $cpf);
$queryInsert->bindParam(":senha", $senha);
$queryInsert->bindParam(":nivel", $nivel);
$queryInsert->execute();
echo "Salvo com sucesso!";
$pdo = null;
