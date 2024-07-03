<?php require_once("script_php/verificar.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="en">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="styles/style_menu.css">
  <link rel="stylesheet" href="styles/hitorico.css">
  <link rel="stylesheet" href="styles/formularios.css">
  <title>PivôPower</title>
</head>

<body>
  <nav>
    <div class="mobile-menu">
      <div class="line1"></div>
      <div class="line2"></div>
      <div class="line3"></div>
    </div>
    <ul class="nav-list">
      <li><a href="monitorar.php" rel="prev" target="_self" id="termo1">Monitorar</a></li>
      <li><a href="script_php/logout.php" rel="next" target="_self">Sair</a></li>
    </ul>
  </nav>
  <div id="container">
    <form action="#">
      <fieldset class="entradas">
        <legend>Defina o período p/ busca</legend>
        <div id="div1">
          <label>Pivô:</label>
          <input type="number" name="val" id="piv" value="2">
          <label>de:</label>
          <input type="date" name="dat1" class="input1" id="dat1" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
          <label>a</label>
          <input type="date" name="dat2" class="input2" id="dat2" value="<?php echo date('Y-m-d'); ?>"><br>
        </div>
        <div id="div2">
          <label class="check"><input type="checkbox" name="chec" value="1" checked>Seco</label>
          <label class="check"><input type="checkbox" name="chec" value="2" checked>Água</label>
          <label class="check"><input type="checkbox" name="chec" value="3" checked>Falha</label>
          <label class="check"><input type="checkbox" name="chec" value="4">Alerta</label>
          <li class="dropdown">
            <a href="#">&#x2630;</a>
            <div class="dropdown-menu">
              <a href="#" class="drop" id="hist">&#x1F4C5;Históricos</a>
              <a href="#" class="drop" id="irri">&#x1F4A7;Irrigação</a>
              <a href="#" class="drop" id="graf">&#x1F4C8;Gráficos</a>
              <a href="#" class="drop" id="resu">&#x1F4DD;Resumo</a>
            </div>
          </li>
        </div>
      </fieldset>
    </form>
    <div class="conteudo">
      <picture>
        <source class="imgFundo" media="(max-width: 400px)" srcset="img/cel2.jpg" type="image/jpg">
        <img class="imgFundo" src="img/fundo.jpg" alt="Imagem flexível">
      </picture>
      <div id="divTable">
        <table id="table"></table>
      </div>
      <div class="container2" id="divChart">
        <div class="box" id="chart1">
          <h5 class="tit" id="titGraf1"></h5><canvas class="canv" id="myChart1"></canvas>
        </div>
        <div class="box" id="chart2">
          <h5 class="tit" id="titGraf2"></h5><canvas class="canv" id="myChart2"></canvas>
        </div>
        <div class="box" id="chart3">
          <h5 class="tit" id="titGraf3"></h5><canvas class="canv" id="myChart3"></canvas>
        </div>
        <div class="box" id="chart4">
          <h5 class="tit" id="titGraf4"></h5><canvas class="canv" id="myChart4"></canvas>
        </div>
        <div class="popup" id="anglePopup"></div>
      </div>
    </div>
    <div class="formulario" id="passwordOverlay"></div>
  </div>
  <script src="scripts/script_menu.js"></script>
  <script src="scripts/nav_historico.js"></script>
  <script src="scripts/dowload.js"></script>
</body>

</html>
