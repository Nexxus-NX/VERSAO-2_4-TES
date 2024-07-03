<?php
function status($sta)
{
  $ret = '';
  switch ($sta) {
    case '1':
      $ret = 'Desligado';
      break;
    case '2':
      $ret = "Lig. Rev.<< seco!";
      break;
    case '3':
      $ret = "Lig. Rev.<< agua!";
      break;
    case '4':
      $ret = "Lig. Fre.>> seco!";
      break;
    case '5':
      $ret = "Lig. Fre.>> agua!";
      break;
    case '6':
      $ret = "Ener. OK, aguard!";
      break;
    case '7':
      $ret = "Queda de energia!";
      break;
    case '8':
      $ret = "Falha na torre";
      break;
    case '9':
      $ret = "Falha na bomba!";
      break;
    case '10':
      $ret = "Falha na bomba!";
      break;
    case '11':
      $ret = "Pivo violado!";
      break;
    case '12':
      $ret = "Horario de ponta!";
      break;
    case '13':
      $ret = "Aguardando agua!";
      break;
    case '14':
      $ret = "Parada em angulo!";
      break;
    case '15':
      $ret = "Aguard. pressao!";
      break;
    case '16':
      $ret = "Desli. a bomba!";
      break;
    case '17':
      $ret = "Sem sinal!";
      break;
    default:
      $ret = "Aguardando!";
      break;
  }
  return $ret;
}
