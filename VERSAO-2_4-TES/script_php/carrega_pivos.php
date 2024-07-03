<?php
@session_start();
$niv = $_SESSION['nivel'];
$faz = $_SESSION['faz'];
$piv = 1;
$txt = 'Alterar';
$tx = '<form class="password-box" id="cadPivs" method="post">';
$tx .= '<img class="logo" src="img/logo3.png" alt="Logo"><br>';
if ($niv == 7) {
  $tx .= '<label for="faz">N° da fazenda:</label><br>
<input type="number" id="faz" name="faz" value="' . $faz . '" requered><br>';
  $txt = 'Cadastra';
}
$tx .= '<label for="piv">N° do Pivô:</label><br>
<input type="number" id="piv" name="piv" value="' . $piv . '" requered><br>
<label for="aria">Ária do pivô:</label><br>
<input type="number" id="aria" name="aria" placeholder="Em hectares." requered><br>
<label for="vaz">Vazão  do pivô:</label><br>
<input type="number" id="vaz" name="vaz" placeholder="Em m³/h." requered><br>
<label for="vel">Tempo p/ volta a 100%:</label><br>
<input type="number" id="vel" name="vel" placeholder="Em m/h." requered><br>
<label for="lb">Lamina a 100%:</label><br>
<input type="number" id="lb" name="lb" placeholder="Em mm." requered><br><br>
<input class="btn" type="submit" id="submitCadastro" value="' . $txt . '">
<small><div align="center" id="mensagem-perfil"></div></small>
</form>';
echo $tx;
