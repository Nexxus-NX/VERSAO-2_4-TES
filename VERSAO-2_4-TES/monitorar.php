<?php require_once("script_php/verificar.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/style_menu.css">
  <link rel="stylesheet" href="styles/monitorar.css">
  <link rel="stylesheet" href="styles/style_pv.css">
  <link rel="stylesheet" href="styles/style_conf.css">
  <link rel="stylesheet" href="styles/popup.css">
  <link rel="stylesheet" href="styles/formularios.css">
  <style>
    #loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('img/fundo.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      z-index: 9999;
    }

    .loader {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <title>PivôPower</title>
</head>

<body class="classtes" id="meu-elemento">
  <div id="fundo">
    <nav>
      <div class="mobile-menu">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
      </div>
      <ul class="nav-list">
        <li><a href="hitorico.php" rel="next" target="_self">Históricos</a></li>
        <li><a href="script_php/logout.php" rel="next" target="_self">Sair</a></li>
        <?php
        if ($_SESSION['nivel'] == 4 || $_SESSION['nivel'] == 7) {
          echo '
<a href="#" >&#9881;------------&#9881;</a>
<a href="#" class="ali" id="cad_pivs">Pivôs</a>
';
        }
        if ($_SESSION['nivel'] == 7) {
          echo '
<a href="#" class="ali" id="list_cliente">Usuários</a>        
<a href="#" class="ali" id="configurar">Comfigura</a>
<a href="#" class="ali" id="cad_cliente">Cadastro</a>          
';
        }
        ?>
      </ul>
    </nav>
    <div id="loading-overlay">
      <div class="loader"></div>
      <p>Aguardando dados...</p>
    </div>
    <?php
    $faz = @$_SESSION['faz'];
    if ($_SESSION['nivel'] == 7) {
      echo '<a class="faz">ID da Fazenda:<br><br><input type="number" name="faz" id="faz1" value="' . $faz . '" style="width: 30px; padding: 0px 0px 0px 5px;"></a>';
    }
    echo '<img id="imagem" src="img/pivos_sat' . $faz . '.jpg" alt="Imagem não encontrada">';
    ?>
    <div class="divP1" id="p1">
      <canvas class="canv" id="gauge1"></canvas>
      <label for="num-p1" class="lab-pv">P</label>
      <input type="number" class="lab-pv num" id="num-p1" value="1" readonly>
      <div class="popup" id="popup">
        <p class="conteudo"></p>
      </div>
    </div>
    <div class="divP1" id="config">
      <label for="qdp">Quantidade de pivos</label>
      <input type="number" class="conf" name="qdp" id="qdp" value="2"><br>
      <button class="btnConf" id="set">Set</button>
      <button class="btnConf" id="sair">Sair</button>
      <button class="btnConf" id="red">Mov</button>
    </div>
    <div class="formulario" id="passwordOverlay"></div>
  </div>
  <script>
    var qdp = 0;
  </script>
  <script src="scripts/script_menu.js"></script>
  <script src="scripts/get_config.js"></script>
  <script src="scripts/script_gauge.js"></script>
  <script src="scripts/set_config.js"></script>
  <script src="scripts/get_position.js"></script>
  <script src="scripts/cadastro.js"></script>
  <script src="scripts/popup.js"></script>
</body>

</html>