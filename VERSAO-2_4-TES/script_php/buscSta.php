<?php
session_start();
$piv = $_POST['piv'];
$faz = isset($_SESSION['faz']) ? $_SESSION['faz'] : null;
require_once("get_conf_piv.php");
if ($faz !== null) {
  $b2 = $pdo->prepare("SELECT * FROM statpiv_2 WHERE faz=:faz AND piv=:piv ORDER BY id DESC LIMIT 1");
  $b2->bindParam(':faz', $faz, PDO::PARAM_INT);
  $b2->bindParam(':piv', $piv, PDO::PARAM_INT);
  $b2->execute();
  $ulti = $angi = $staf = $angf = $ax = 0;
  $ultf = 1;
  $tx = '';
  while ($rc = $b2->fetch(PDO::FETCH_ASSOC)) {
    $staf = $rc['sta'];
    $ultf = $rc['ult'];
    $angi = $rc['ang_i'];
    $angf = $rc['ang_f'];
  }
}
$pdo = null;
if ($ultf) {
  $tp = ((($vel / 360) * 100.0) / $ultf);
} else {
  $tp = 0;
}
function tempVolta($ai, $af, $sent, $t)
{
  if ($ai == $af) {
    return number_format($t * 360, 2);
  }
  $ret = 0;
  $delta = ($af - $ai + 180) % 360 - 180;
  if (($sent == 3 || $sent == 5) && $delta < 0) {
    $ret = 360 + $delta;
  } else if (($sent == 2 || $sent == 4) && $delta >= 0) {
    $ret = 360 - $delta;
  } else {
    $ret = abs($delta);
  }
  return number_format($t * $ret, 2);
}
$tp = tempVolta($angi, $angf, $staf, $tp);
require_once("switchStatos.php");
$tx = status($staf);
if ($staf > 5 || $staf < 2) {
  echo "$tx<br>Ang. $angf °";
} else {
  echo "$tx <br>Vel. $ultf% <br>Ang. $angf °<br>tempo: $tp h";
}
$pdo = null;
