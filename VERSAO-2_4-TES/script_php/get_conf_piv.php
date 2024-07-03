<?php
require_once("conexao.php");
@session_start();
$confPiv = $pdo->query("SELECT * FROM `pivs` WHERE faz=$faz AND piv=$piv");
if ($rc = $confPiv->fetch(PDO::FETCH_ASSOC)) {
  $aria = $rc['aria'];
  $vaz = $rc['vaz'];
  $vel = $rc['vel'];
  $lb = $rc['lb'];
  $cv = $rc['cv'];
  $rend = $rc['rend'];
  $fp = $rc['fp'];
  $in = $rc['in'];
  $it = $rc['it'];
  $td = $rc['td'];
  $tn = $rc['tn'];
  $tdem = $rc['tdem'];
}
