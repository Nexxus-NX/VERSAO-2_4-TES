<?php
require_once("conexao.php");
$tx = '<form class="password-box" id="cadastroForm" method="post">';
$tx .= '<img class="logo" src="img/logo3.png" alt="Logo"><br>';
$tx .= '<table>';
$tx .= '<caption>Lista de clientes</caption>';
$tx .= '<tr><th>Faz </th><th>Nome </th><th>Email </th></tr>';
$b2 = $pdo->query('SELECT * FROM usuarios');
while ($rc = $b2->fetch(PDO::FETCH_ASSOC)) {
  $faz = $rc['faz'];
  $nome = $rc['nome'];
  $email = $rc['email'];
  $tx .= '<tr>
<td>' . $faz . '</td>
<td>' . $nome . '</td>
<td>' . $email . '</td>
</tr>';
}
$tx .= '</table>';
$tx .= '</form>';
echo $tx;
$pdo = null;
