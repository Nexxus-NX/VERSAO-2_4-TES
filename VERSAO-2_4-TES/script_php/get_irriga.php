<?php
$piv = isset($_POST["piv"]) ? $_POST["piv"] : null;
$dat1 = isset($_POST["dat1"]) ? $_POST["dat1"] : null;
$dat3 = isset($_POST["dat2"]) ? $_POST["dat2"] : null;
$ref = isset($_POST["ref"]) ? $_POST["ref"] : null;
$dat2=$dat3;
$dat2 .= ' 23:59:59';

require_once("get_dados.php");

$mmAng = array_fill(0, 360, 1);

function defineValores(&$mmAng, $sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua, $lb)
{
  validarDados($sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua);
  $lamina = $tComA = [];
  $totMm = $totA = 0;

  for ($i = 0; $i < count($ult_agua); $i++) {
    list($mm, $t) = calcularLaminaEtempo($datI_agua[$i], $datF_agua[$i], $ult_agua[$i], $lb);
    $lamina[] = $mm;
    $tComA[] = $t;
    $totMm += $mm;
    $totA += $t;
  }

  return array($lamina, $tComA, $totMm, $totA);
}

function validarDados(...$arrs)
{
  $length = count($arrs[0]);
  foreach ($arrs as $arr) {
    if (count($arr) !== $length) {
      throw new Exception("Todos os arrays devem ter o mesmo tamanho.");
    }
  }
}

function calcularLaminaEtempo($dataInicio, $dataFim, $ultimaLeitura, $lb)
{
  $timestamp1 = (new DateTime($dataFim))->getTimestamp();
  $timestamp2 = (new DateTime($dataInicio))->getTimestamp();
  $t = ($timestamp1 - $timestamp2) / 3600;
  $mm = (($lb * 100.0) / $ultimaLeitura);

  return array($mm, $t);
}

function dia_noite($inicio, $fim)
{
  $inicioDateTime = new DateTime($inicio);
  $fimDateTime = new DateTime($fim);
  $horasDia = $horasNoite = 0;

  while ($inicioDateTime < $fimDateTime) {
    $horaAtual = (int)$inicioDateTime->format('H');
    $proximoMinuto = (clone $inicioDateTime)->modify('+1 minute');
    $minutosParaAdicionar = min(($fimDateTime->getTimestamp() - $inicioDateTime->getTimestamp()) / 60, 1);

    if ($horaAtual >= 6 && $horaAtual < 19) {
      $horasDia += $minutosParaAdicionar / 60;
    } else {
      $horasNoite += $minutosParaAdicionar / 60;
    }
    $inicioDateTime->modify('+1 minute');
  }

  return [round($horasDia, 2), round($horasNoite, 2)];
}

$rxArray = defineValores($mmAng, $sta_agua, $ult_agua, $datI_agua, $datF_agua, $angI_agua, $angf_agua, $lb);
$lamina = $rxArray[0];
$tComA = $rxArray[1];
$consumo = consumoAgua($tComA, $vaz);
$comA = uniData($datI_agua, $tComA, $consumo[0]);

$axt1 = array('.' => ',', ',' => '.');
$axt = strtr($consumo[1], $axt1);

$dat1 = implode('-', array_reverse(explode('-', $dat1)));

require_once("switchStatos.php");

if ($ref == 2) {
  $tx = '<table class="tabela" id="tb1">';
  $tx .= '<thead><tr><th colspan="8" id="tes">Irrigação no P' . $piv . ' de ' . $dat1 . ' a ' . $dat3 . ' motor '.$cv.'Cv</th></tr>';
  $tx .= '<tr><th>Statos</th><th>Velo.(%)</th><th>Lâmina (mm)</th><th>Ang. inicio (º)</th>';
  $tx .= '<th>Ang. fim (º)</th><th>Iniciada</th><th>Finalizada</th><th>Tempo irrigado(h)</th>';
  $tx .= '</tr></thead><tbody id="r">';

  $axh = $noite = $dia = $axmm = $conti = 0;

  for ($i = 0; $i < count($datI_agua); $i++) {
    $v = $ult_agua[$i];
    if ($v > 5) {
      $sta = status($sta_agua[$i]);
      $m = number_format($lamina[$i], 2);
      $ini = $angI_agua[$i];
      $fi = $angf_agua[$i];
      $dat1 = $datI_agua[$i];
      $hf = date('d-m-Y H:i:s', strtotime($datF_agua[$i]));
      $hin = date('d-m-Y H:i:s', strtotime($datI_agua[$i]));
      $hf2 = strtotime($datF_agua[$i]);
      $hin2 = strtotime($datI_agua[$i]);
      $t = number_format(($hf2 - $hin2) / 3600.0, 3);
      $axmm += $m;
      $axh += $t;
      $conti++;

      $dia_noite = dia_noite($datI_agua[$i], $datF_agua[$i]);
      $dia += $dia_noite[0];
      $noite += $dia_noite[1];

      $tx .= "<tr><td>$sta</td><td>$v</td><td>$m</td><td>$ini</td><td>$fi</td>";
      $tx .= "<td>$hin</td><td>$hf</td><td>$t</td></tr>";
    }
  }

  $vol = number_format($axh * $vaz, 2, ',', '.');
  $kwh = (($it / $in) * ($cv * 0.7355) * (1 / ($fp * $rend)));
  $cons = $kwh * $axh;
  $consD = ($axh ? ($cons / $axh) * $dia : 0);
  $consN = $cons - $consD;
  $custD = $td * $consD;
  $custN = $tn * $consN;
  $totDN = $custD + $custN;
  $media = ($axh * $vaz * 1000) / ($aria * 10000);
  $medmm = ($conti ? $axmm / $conti : 0);
  $cusmm = ($totDN / $aria) / $media;

  $consD = number_format($consD, 2, ',', '.');
  $consN = number_format($consN, 2, ',', '.');
  $cons = number_format($cons, 2, ',', '.');
  $custD = number_format($custD, 2, ',', '.');
  $custN = number_format($custN, 2, ',', '.');
  $totDN = number_format($totDN, 2, ',', '.');
  $axh = number_format($axh, 2, ',', '.');
  $media = number_format($media, 2, ',', '.');
  $medmm = number_format($medmm, 2, ',', '.');
  $cusmm = number_format($cusmm, 2, ',', '.');

  $tx .= '<tr><td>-----</td><td>-----</td><td>-----</td><td>-----</td><td>-----</td>';
  $tx .= '<td>-----</td><td>-----</td><td>-----</td></tr>';
  $tx .= '<tr><th colspan="8">Valores aproximados do período com base na ficha técnica fornecida</th></tr>';
  $tx .= '<tr><th>Tempo irrigado (h)</th><th>Tempo dia (h)</th><th>Tempo noite (h)</th><th>Tarifa dia (R$)</th>';
  $tx .= '<th>Tarifa noite(R$)</th><th>Tarifa demanda (R$)</th><th>Lâmina média(mm)</th>';
  $tx .= '<th>Total bombeado (m³)</th></tr>';
  $tx .= "<tr><td>$axh</td><td>$dia</td><td>$noite</td><td>$td</td><td>$tn</td>";
  $tx .= "<td>$media</td><td>$vol</td><td></td></tr>";
  $tx .= '<tr><th>Consumo total (Kwh)</th><th>Consumo dia(Kwh)</th><th>Consumo noite (Kwh)</th>';
  $tx .= '<th>Custo dia (R$)</th><th>Custo noite(R$)</th><th>Total (R$)</th><th>Custo p/ mm (R$)</th>';
  $tx .= '<th>M³/h</th></tr>';
  $tx .= "<tr><td>$cons</td><td>$consD</td><td>$consN</td><td>$custD</td><td>$custN</td>";
  $tx .= "<td>$totDN</td><td>$cusmm</td><td>$vaz</td></tr>";
  $tx .= '</tbody>';
  $tx .= '</table>';
  $tx .= '<input class="btn" type="submit" id="download" value="&#x25BC;Download">';

  echo $tx;
  exit();
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

function uniData($datas, $val, $cons)
{
  $dat = [];
  foreach ($datas as $data) {
    $dat[] = explode(' ', $data)[0];
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
      $c = ($cont > 0) ? $cont - 1 : 0;
      $axVal[$c] += floatval($val[$i]);
      $axcon[$c] += floatval($cons[$i]);
    }
  }
  array_splice($axVal, count($d));
  array_splice($axcon, count($d));
  return array($d, $axVal, $axcon);
}
