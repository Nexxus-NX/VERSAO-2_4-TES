<?php
session_start();
$faz = isset($_SESSION['faz']) ? $_SESSION['faz'] : null;
$niv = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : null;
$vaz = isset($_SESSION['vaz']) ? $_SESSION['vaz'] : null;
require_once("get_conf_piv.php");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT*FROM statpiv_2 WHERE faz=:faz AND piv=:piv AND dat_i>=:dat1 AND dat_f<:dat2";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':faz', $faz, PDO::PARAM_INT);
$stmt->bindParam(':piv', $piv, PDO::PARAM_INT);
$stmt->bindParam(':dat1', $dat1, PDO::PARAM_STR);
$stmt->bindParam(':dat2', $dat2, PDO::PARAM_STR);
$stmt->execute();
$mmAng = array_fill(0, 360, 1);
$totFalha = array_fill(0, 25, 0);
$consumo = array(0, 0);
$sta_agua = $ult_agua = $angI_agua = $angf_agua = $ori_agua = $angStop_a = $datI_agua = $datF_agua = [];
$sta_seco = $ult_seco = $angI_seco = $angf_seco = $ori_seco = $angStop_s = $datI_seco = $datF_seco = [];
$sta_falt = $ult_falt = $ang_falt = $dat_falt = [];
//$tes=[];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //$tes[]=$row['sta'];
  if ($row['sta'] == 3 || $row['sta'] == 5) { //Com água
    $sta_agua[]  = intval($row['sta']);
    $ult_agua[]  = intval($row['ult']);
    $angI_agua[] = intval($row['ang_i']);
    $angf_agua[] = intval($row['ang_f']);
    $datI_agua[] = $row['dat_i'];
    $datF_agua[] = $row['dat_f'];
  } elseif ($row['sta'] == 2 || $row['sta'] == 4) { //Sem água
    $sta_seco[]  = intval($row['sta']);
    $ult_seco[]  = intval($row['ult']);
    $angI_seco[] = intval($row['ang_i']);
    $angf_seco[] = intval($row['ang_f']);
    $datI_seco[] = $row['dat_i'];
    $datF_seco[] = $row['dat_f'];
  } elseif ($row['sta'] > 6 && $row['sta'] < 12) { //com falhas
    $totFalha[intval($row['sta'])]++;
    $sta_falt[] = intval($row['sta']);
    $ult_falt[] = intval($row['ult']);
    $ang_falt[] = intval($row['ang_f']);
    $dat_falt[] = intval($row['dat_f']);
  }
}
