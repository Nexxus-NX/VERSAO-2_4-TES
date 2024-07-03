<?php
session_start();
if (isset($_POST["faz1"])) {
  $_SESSION['faz'] = $_POST["faz1"];
  echo "Valor recebido: " . $_SESSION['faz'];
} else {
  echo "Nenhum valor recebido via POST.";
}
