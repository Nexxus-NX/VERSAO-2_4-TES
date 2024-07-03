<?php
require_once("conexao.php");
$b2 = $pdo->query("SELECT MAX(faz) AS ultimo_id FROM usuarios;");
$rc = $b2->fetch(PDO::FETCH_ASSOC);
$ultFaz = 0;
if ($rc) {
  $ultFaz = intval($rc['ultimo_id']) + 1;
}
$pdo = null;
echo '
<form class="password-box" id="cadastroForm" method="post">
<img class="logo" src="img/logo3.png" alt="Logo"><br>
<label for="faz2">N° da fazenda:</label><br>
<input type="text" id="faz2" name="faz2" value="' . $ultFaz . '" placeholder="Id \'opcional em novos cadástros\'!"><br>
<label for="nome">Nome:</label><br>
<input type="text" id="nome" name="nome" placeholder="Nome da fazenda ou proprietário!"><br>
<label for="email">Email:</label><br>
<input type="email" id="email" name="email" placeholder="emails_para_contato.com!"><br>
<label for="cpf">Cpf/Cnpj:</label><br>
<input type="text" id="cpf" name="cpf" placeholder="Cpf do usuário ou cnpj da fazenda!"><br>
<label for="nivel">Nivel:</label><br>
<input type="number" id="nivel" name="nivel" placeholder="Senha para logim do usuário!"><br>
<label for="senha">Senha:</label><br>
<input type="password" id="senha" name="senha" placeholder="Senha para logim do usuário!"><br><br>
<input class="btn" type="submit" id="submitCadastro" value="Cadastrar">
<small><div align="center" id="mensagem-perfil"></div></small>
</form>';
