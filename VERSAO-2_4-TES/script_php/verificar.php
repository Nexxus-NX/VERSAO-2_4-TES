<?php
@session_start();
if (!in_array(@$_SESSION['nivel'], range(1, 7))) {
  session_destroy();
  header("Location: index.php");
  exit();
}
