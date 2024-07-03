<?php
require_once("conexao.php");
session_start();
$nivel = $_SESSION['nivel'];
$faz = 0;
if ($nivel == 7) {
  $faz = $_POST['faz'];
} else {
  $faz = $_SESSION['faz'];
}
$piv = $_POST['piv'];
$aria = $_POST['aria'];
$vaz = $_POST['vaz'];
$vel = $_POST['vel'];
$lb = $_POST['lb'];
$ax = $_POST['ax'];
$tx = '';
if ($aria == NULL) {
  $tx = "O campo 'aria'";
}
if ($vaz == NULL) {
  $tx .= ($tx != '') ? ", 'vazão'" : "O campo 'vazão'";
}
if ($vel == NULL) {
  $tx .= ($tx != '') ? ", 'velocidade'" : "O campo 'velocidade'";
}
if ($lb == NULL) {
  $tx .= ($tx != '') ? " e 'lamina'" : "O campo 'lamina'";
}
if ($tx != '') {
  $tx .= " são campos obrigatórios!";
  echo $tx;
  $pdo = null;
  exit();
}
$quer = "SELECT * FROM pivs WHERE faz=:faz AND piv=:piv";
$query = $pdo->prepare($quer);
$query->bindValue(":faz", $faz);
$query->bindValue(":piv", $piv);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if ($piv == @$res[0]['piv'] && !$ax) {
  echo 1;
  $pdo = null;
  exit();
} elseif (!$ax && $nivel == 7) {
  $quer = "INSERT INTO `pivs`(`faz`,`piv`,`aria`,`vaz`,`vel`,`lb`) ";
  $quer .= "VALUES (:faz, :piv, :aria, :vaz, :vel, :lb)";
} elseif ($ax == 1) {
  $quer = "UPDATE `pivs` SET `aria`=:aria, `vaz`=:vaz, `vel`=:vel, `lb`=:lb ";
  $quer .= "WHERE faz=:faz AND piv=:piv";
}
$query = $pdo->prepare($quer);
$query->bindValue(":faz", $faz);
$query->bindValue(":piv", $piv);
$query->bindValue(":aria", $aria);
$query->bindValue(":vaz", $vaz);
$query->bindValue(":vel", $vel);
$query->bindValue(":lb", $lb);
$query->execute();
$pdo = null;
echo "Salvo com Sucesso!";
