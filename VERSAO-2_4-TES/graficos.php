<?php
$piv = isset($_POST["piv"]) ? $_POST["piv"] : null;
$dat1 = isset($_POST["dat1"]) ? $_POST["dat1"] : null;
$dat2 = isset($_POST["dat2"]) ? $_POST["dat2"] : null;
$ref = isset($_POST["ref"]) ? $_POST["ref"] : null;
session_start();
$faz = isset($_SESSION['faz']) ? $_SESSION['faz'] : null;
$niv = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : null;
$vaz = isset($_SESSION['vaz']) ? $_SESSION['vaz'] : null;
require_once("get_conf_piv.php");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT*FROM statpiv WHERE faz=:faz AND piv=:piv AND dat>=:dat1 AND dat<:dat2";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':faz', $faz, PDO::PARAM_INT);
$stmt->bindParam(':piv', $piv, PDO::PARAM_INT);
$stmt->bindParam(':dat1', $dat1, PDO::PARAM_STR);
$stmt->bindParam(':dat2', $dat2, PDO::PARAM_STR);
$stmt->execute();
$mmAng = array_fill(0, 360, 1);
$totFalha = array_fill(0, 25, 0);
$consumo = array(0, 0);
$st = $vl = $mi = $ai = $af = $hi = $datA = $horA = $tComA = $datS = $tSemA = [];
$datIa = $datIs = '';
$horIn = $angIn = $totA = $totS = $totMm = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $angF = intval($row['ang']);
  $datF = new DateTime($row['dat']);
  $horIn = $row['dat'];
  if ($datIa != '') {
    $rxDat = separaDat($datIa);
    $datA[] = $rxDat[0];
    $horA[] = $datIa; //$rxDat[1];
    $hi[] = $horIn; //separaDat($horIn)[1];
    $timestamp1 = $datF->getTimestamp();
    $timestamp2 = (new DateTime($datIa))->getTimestamp();
    $t = ($timestamp1 - $timestamp2) / 3600;
    $mm = (($lb * 100.0) / $ult);
    $vl[] = $ult;
    $mi[] = $mm;
    $totMm += $mm;
    $tb = $vel / 360;
    $ta = ($tb * 100) / $ult;
    $ai[] = $angIn;
    $af[] = $angF;
    angulos($mmAng, $angIn, $angF, $mm, $row['sta'], $t, $ta);
    $tComA[] = $t;
    $totA += $t;
    $datIa = '';
  }
  if ($datIs != '') {
    $rxDat = separaDat($datIs)[0];
    $datS[] = $rxDat;
    $timestamp1 = $datF->getTimestamp();
    $timestamp2 = (new DateTime($datIs))->getTimestamp();
    $t = ($timestamp1 - $timestamp2) / 3600;
    $tSemA[] = $t;
    $totS += $t;
    $datIs = '';
  }
  if ($row['sta'] == 3 || $row['sta'] == 5) {
    $ult = intval($row['ult']);
    $datIa = $row['dat'];
    $st[] = $row['sta'];
  } elseif ($row['sta'] == 2 || $row['sta'] == 4) {
    $datIs = $row['dat'];
  } elseif ($row['sta'] > 6 && $row['sta'] < 12) {
    $totFalha[intval($row['sta'])]++;
  }
  $angIn = $angF;
  $angF = 0;
}
$pdo = null;
function separaDat($datetime)
{
  $dat = [];
  list($dat[0], $dat[1]) = explode(' ', $datetime);
  $dat[0] = date('d-m-Y', strtotime($dat[0]));
  return $dat;
}
function angulos(&$mmAng, $angi, $angf, $mm, $st, $tCom, $ta)
{
  if ($tCom > 0.2) {
    $dir = ($st == 5) ? 1 : -1;
    for ($i = $angi; $i != $angf; $i = ($i + $dir + 360) % 360) {
      $mmAng[$i] += $mm;
      $tCom -= $ta;
    }
    if ($tCom > $ta * 300) {
      $a = 0;
      while ($a < 360) {
        $mmAng[$a] += $mm;
        $tCom -= $ta;
        $a++;
      }
    }
    $mmAng[$angf] += $mm;
  }
}
function contF($veto)
{
  $total = array(0, 0);
  for ($i = 0; $i < 25; $i++) {
    if ($veto[$i]) {
      $total[0] += $veto[$i];
      if ($veto[$i] > $total[1]) {
        $total[1] = $i;
      }
    }
  }
  return $total;
}
function consumoAgua($tcoma, $vaz)
{
  $tot = 0;
  $valores = [];
  foreach ($tcoma as $val) {
    $ax = $vaz * $val;
    $valores[] = $ax;
    $tot += $ax;
  }
  return array($valores, number_format($tot, 2));
}
function uniData($dat, $val, $cons)
{
  $ax = count($dat);
  $axVal = array_fill(0, $ax, 0);
  $axcon = array_fill(0, $ax, 0);
  $cont = 0;
  $ax2 = 0;
  if ($ax !== count($val) || $ax !== count($cons) || $ax == 0) {
    return array($dat, $val, $cons);
  }
  for ($i = 0; $i < $ax; $i++) {
    if ($dat[$i] != $ax2) {
      $d[] = $dat[$i];
      $axVal[$cont] = floatval($val[$i]);
      $axcon[$cont] = $cons[$i];
      $ax2 = $dat[$i];
      $cont++;
    } else {
      $c = 0;
      if ($cont > 0) {
        $c = $cont - 1;
      }
      $axVal[$c] += floatval($val[$i]);
      $axcon[$c] += floatval($cons[$i]);
    }
  }
  array_splice($axVal, count($d));
  array_splice($axcon, count($d));
  return array($d, $axVal, $axcon);
}
$consumo = consumoAgua($tComA, $vaz);
$comA = uniData($datA, $tComA, $consumo[0]);
$semA = uniData($datS, $tSemA, $tSemA);
$dat1 = implode('-', array_reverse(explode('-', $dat1)));
$dat2 = implode('-', array_reverse(explode('-', $dat2)));
$pdo = null;
$axt1 = array('.' => ',', ',' => '.');
$axt = strtr($consumo[1], $axt1);
require_once("switchStatos.php");
if ($ref == 1) {
  $tx = '<table class="tabRez">
<tr><th colspan="2">Periodo de ' . $dat1 . ' a ' . $dat2 . '</th></tr>
<tr><td>Total de horas :</td><td>' . number_format($totA + $totS, 2) . ' horas</td></tr>
<tr><td>Total a seco :</td><td>' . number_format($totS, 2) . ' horas</td></tr>
<tr><td>Total com água :</td><td>' . number_format($totA, 2) . ' horas</td></tr>
<tr><td>Volume de água no periodo:</td><td>' . $axt . '</td></tr>
<tr><td>Total de falhas :</td><td>' . contF($totFalha)[0] . ' Falhas</td></tr>
<tr><td>Falha com maior incidência:</td><td>' . status(contF($totFalha)[1]) . '</td></tr>
<!-- Adicione mais linhas conforme necessário -->
</table>';
  echo $tx;
  exit();
} else
if ($ref == 2) {
  $axd = explode(" ", $dat2);
  $tx = '<table class="tabela" id="tb1">';
  $tx .= '<thead><tr><th colspan="9" id="tes">Irrigação no P' . $piv . ' de ' . $dat1 . ' a ' . $axd[0] . '</th></tr>';
  $tx .= '<tr><th>Statos</th><th>Veloci.(%)</th><th>Lâmina (mm)</th><th>Angulo inicial (º)</th>';
  $tx .= '<th>Angulo final (º)</th><th>Data</th><th>Hora inicio</th><th>Hora fim</th><th>Tempo (h)</th></tr></thead><tbody id="r">';
  for ($i = 0; $i < count($datA); $i++) {
    $v = $vl[$i];
    if ($v > 5) {
      $sta = status($st[$i]);
      $m = number_format($mi[$i], 2);
      $in = $ai[$i];
      $fi = $af[$i];
      $dat1 = $datA[$i];
      $hf = separaDat($hi[$i])[1];
      $hin = separaDat($horA[$i])[1];
      $hf2 = strtotime($hi[$i]);
      $hin2 = strtotime($horA[$i]);
      $t = number_format(($hf2 - $hin2) / 3600.0, 3);
      $tx .= "<tr>
<td>$sta</td>
<td>$v</td>
<td>$m</td>
<td>$in</td>
<td>$fi</td>
<td>$dat1</td>
<td>$hin</td>
<td>$hf</td>
<td>$t</td>
</tr>";
    }
  }
  $tx .= '</tbody>';
  $tx .= '</table>';
  $tx .= '<input class="btn" type="submit" id="dowload" value="&#x25BC;Dowload">';
  echo $tx;
  exit(); //*/
}
echo json_encode(array(
  'mmAng' => $mmAng, 'datS' => $semA[0], 'tSemA' => $semA[1], 'datA' => $comA[0], 'tComA' => $comA[1], 'consu' => $comA[2], 'totS' => number_format($totS, 2), 'totA' => number_format($totA, 2), 'totMm' => number_format($totMm, 2), 'totAg' => $axt
));
