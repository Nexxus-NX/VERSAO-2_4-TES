<?php
$piv = isset($_POST["piv"]) ? $_POST["piv"] : null;
$dat1 = isset($_POST["dat1"]) ? $_POST["dat1"] : null;
$dat2 = isset($_POST["dat2"]) ? $_POST["dat2"] : null;
$ref = isset($_POST["ref"]) ? $_POST["ref"] : null;
$dat2 .= ' 23:59:59';
require_once("get_dados.php");
$pdo = null; //fecha o banco de dados
$mmAng = array_fill(0, 360, 1);
$tes = 0;
function defineValores(&$mmAng, $sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua, $lb)
{
  validarDados($sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua);
  $lamina = $tComA = [];
  $totMm = $totA = 0;

  for ($i = 0; $i < count($ult_agua); $i++) {
    list($mm, $t) = calcularLaminaEtempo($datI_agua[$i], $datF_agua[$i], $ult_agua[$i], $lb);
    angulos($mmAng, $angI_agua[$i], $angf_agua[$i], $sta_agua[$i], $mm, $t);
    $lamina[] = $mm;
    $tComA[] = $t;
    $totMm += $mm;
    $totA += $t;
  }

  return array($lamina, $tComA, $totMm, $totA);
}
function validarDados(...$arrs)
{ //Valida dados garantindo tamanhos iguais.
  $length = count($arrs[0]);
  foreach ($arrs as $arr) {
    if (count($arr) !== $length) {
      throw new Exception("Todos os arrays devem ter o mesmo tamanho.");
    }
  }
}
function calcularLaminaEtempo($dataInicio, $dataFim, $ultimaLeitura, $lb)
{ //Calcula lâmina e tempo em horas entre duas datas.
  $timestamp1 = (new DateTime($dataFim))->getTimestamp();
  $timestamp2 = (new DateTime($dataInicio))->getTimestamp();
  $t = ($timestamp1 - $timestamp2) / 3600;
  $mm = (($lb * 100.0) / $ultimaLeitura);

  return array($mm, $t);
}
function angulos(&$mmAng, $angi, $angf, $st, $mm, $tCom)
{ //Determina mm por angulo
  if ($tCom > 0.2) {
    $dir = ($st == 5) ? 1 : -1;
    for ($i = $angi; $i != $angf; $i = ($i + $dir + 360) % 360) {
      $mmAng[$i] += $mm;
    }
    $mmAng[$angf] += $mm;
  }
}
function separaDat($datetime)
{
  $dat = [];
  list($dat[0], $dat[1]) = explode(' ', $datetime);
  $dat[0] = date('d-m-Y', strtotime($dat[0]));
  return $dat;
}
function contF($veto)
{ //Conta as falhas e indica a + ocorrida
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
{ //Total geral de agua m³ e para cada intervalo
  $tot = 0;
  $valores = [];
  foreach ($tcoma as $val) {
    $ax = $vaz * $val;
    $valores[] = $ax;
    $tot += $ax;
  }
  return array($valores, number_format($tot, 2));
}
function uniData($datas, $val, $cons)
{ //Unifica as ações de um uinico dia
  for ($i = 0; $i < count($datas); $i++) {
    $dat[] = separaDat($datas[$i])[0];
  }
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
$tSemA = [];
$totS = 0;
$rxArray = defineValores($mmAng, $sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua, $lb);
$lamina = $rxArray[0];
$tComA = $rxArray[1];
$totMm = $rxArray[2];
$totA = $rxArray[3];
$consumo = consumoAgua($tComA, $vaz);
$comA = uniData($datI_agua, $tComA, $consumo[0]);
for ($i = 0; $i < count($ult_seco); $i++) {
  list($m, $t) = calcularLaminaEtempo($datI_seco[$i], $datF_seco[$i], $ult_seco[$i], $lb);
  $tSemA[] = $t;
  $totS += $t;
}
$semA = uniData($datI_seco, $tSemA, $tSemA);
$axt1 = array('.' => ',', ',' => '.');
$axt = strtr($consumo[1], $axt1);
require_once("switchStatos.php");
if ($ref == 1) { //Para pagina resumo
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
}
echo json_encode(array( //Para pagina gráficos
  'mmAng' => $mmAng, 'datA' => $comA[0], 'tComA' => $comA[1], 'consu' => $comA[2], 'totA' => number_format($totA, 2), 'totMm' => number_format($totMm, 2), 'datS' => $semA[0], 'tSemA' => $semA[1], 'totS' => number_format($totS, 2), 'totAg' => $axt, 'tes' => $dat2
));
