<?php
$num1 = $_POST["num1"];
$num2 = $_POST["num2"];
$num3 = $_POST["num3"];
$num4 = $_POST["num4"];
$num5 = $_POST["num5"];
$ax  = $_POST["num6"];
require_once("conexao.php");
@session_start();
$tx = "";
$faz = $_SESSION['faz'];
switch ($ax) {
  case '2': {
      $b2 = $pdo->query("SELECT qdp FROM `setp` WHERE faz=$faz");
      $rc = $b2->fetch(PDO::FETCH_ASSOC);
      $tx = intval($rc['qdp']);
      $_SESSION['qdp'] = $tx;
      break;
    }
  case '4': {
      $rx = $pdo->query("SELECT COUNT(*) AS count FROM setp WHERE faz=$faz AND qdp>0");
      $row = $rx->fetch(PDO::FETCH_ASSOC);
      $count = $row['count'];
      if ($count > 0) {
        $rx = $pdo->query("UPDATE `setp` SET `qdp`= $num1 WHERE faz=$faz");
        if ($rx !== false && $rx !== 0) {
          $tx = "Ok1 $num1";
        }
      } else {
        $rx = $pdo->query("INSERT INTO `setp`(`faz`,`qdp`) VALUES ($faz, $num1)");
        if ($rx !== false && $rx !== 0) {
          $tx = "Ok2 $num1";
        }
      }
      if ($tx != "") {
        $pdo->query("DELETE FROM `position` WHERE faz=$faz AND piv>$num1");
      }
      break;
    }
  case '5': {
      $result = $pdo->query("SELECT * FROM `position` WHERE faz=$faz");
      if ($result && $result->rowCount() > 0) {
        while ($rc = $result->fetch(PDO::FETCH_ASSOC)) {
          $tx .= $rc['piv'] . ',' . $rc['x'] . ',' . $rc['y'] . ',' . $rc['tx'] . ',' . $rc['ty'] . '/';
          flush();
        }
      }
      break;
    }
  case '6': {
      $tx = "piv=$num1,x=$num2,y=$num3,tx=$num4,ty=$num5";
      $rx = $pdo->query("SELECT COUNT(*) AS count FROM position WHERE faz=$faz AND piv=$num1");
      $row = $rx->fetch(PDO::FETCH_ASSOC);
      $count = $row['count'];
      if ($count > 0) {
        $rx = $pdo->query("UPDATE position SET x=$num2, y=$num3,tx=$num4, ty=$num5 WHERE faz=$faz AND piv=$num1");
        if ($rx !== false && $rx !== 0) {
          $tx = "Piv $num1 atualizado!";
        } else {
          $tx = 'Falha ao atualizar!';
        }
      } else {
        $rx = $pdo->query("INSERT INTO position (faz, piv, x, y, tx, ty) VALUES ($faz,$num1,$num2,$num3,$num4,$num5)");
        if ($rx !== false && $rx !== 0) {
          $tx = "Piv $num1 criado!";
        } else {
          $tx = 'Falha ao criar!';
        }
      }
      break;
    }
}
$pdo = null;
if ($tx == "") {
  echo "n1=$num1 n2=$num2 n3=$num3 n4=$num4 n5=$num5 ax=$ax";
} else {
  echo $tx;
}
