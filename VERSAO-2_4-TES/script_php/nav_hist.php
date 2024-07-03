<?php
$piv = $_POST["piv"];
$dat1 = $_POST["dat1"];
$dat2 = $_POST["dat2"];
$chec = $_POST["chec"];
@session_start();
$faz = $_SESSION['faz'];
$dat2 .= ' 23:59:59';
function check($chec)
{
  $condi = array();
  if (isset($chec[0]) && $chec[0] == 1) {
    $condi[] = 'sta IN (1,2,4)';
  }
  if (isset($chec[1]) && $chec[1] == 2) {
    $condi[] = 'sta IN (3,5)';
  }
  if (isset($chec[2]) && $chec[2] == 3) {
    $condi[] = 'sta>6 AND sta<12';
  }
  if (isset($chec[3]) && $chec[3] == 4) {
    $condi[] = 'sta=6 OR sta>11';
  }
  return !empty($condi) ? ' AND (' . implode(' OR ', $condi) . ')' : '';
}
$sql = "SELECT * FROM statpiv_2 WHERE faz = :faz AND piv = :piv AND dat_i >= :dat1 AND dat_f <= :dat2";
$sql .= check($chec);
require_once("get_conf_piv.php");
require_once("switchStatos.php");
if (@$_SESSION['qdp'] < $piv) {
  echo 1;
  $pdo = null;
  exit();
}
$axd = explode(" ", $dat2);
$tx = '<table class="tabela" id="tb1">';
$tx .= '<thead><tr><th colspan="7">Históricos do P' . $piv . ' de ' . $dat1 . ' a ' . $axd[0] . '</th></tr>';
$tx .= '<tr><th>Statos</th><th>Veloci.</th><th>Lâmina</th><th>Angulo</th>';
$tx .= '<th>Origem</th><th>Data</th><th>Hora</th></tr></thead><tbody id="r">';

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':faz', $faz, PDO::PARAM_INT);
$stmt->bindParam(':piv', $piv, PDO::PARAM_INT);
$stmt->bindParam(':dat1', $dat1);
$stmt->bindParam(':dat2', $dat2);
$stmt->execute();
while ($rc = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $sta = $rc['sta'];
  $ult = $rc['ult'];
  $ang = $rc['ang_i'];
  $ori = $rc['ori'];
  $dat = date('d-m-Y H:i:s', strtotime($rc['dat_i']));
  $dat = explode(" ", $dat);
  $mm = 0;
  if ($sta == 3 || $sta == 5) {
    $mm = number_format(($lb * 100.0) / $ult, 2);
  }
  $sta = status($sta);
  $tx .= "<tr>
<td>$sta</td>
<td>$ult %</td>
<td>$mm mm</td>
<td>$ang °</td>
<td>" . origem($ori) . "</td>
<td>$dat[0]</td>
<td>$dat[1]</td>
</tr>";
}
function origem($orig)
{
  switch ($orig) {
    case 0:
      return 'Controle';
    case 1:
      return 'Pivô';
    case 2:
      return 'Central';
    case 3:
      return 'Restart';
    default:
      return $orig;
  }
}
$tx .= '</tbody>';
$tx .= '</table>';
$tx .= '<input class="btn" type="submit" id="dowload" value="&#x25BC;Dowload">';
$pdo = null;
echo $tx;
