<?php
// Verifica se a variável "qdp" foi enviada via POST, e termina a execução se não foi
if (!isset($_POST["qdp"])) {
    die("qdp nulo.");
}

// Atribui o valor de "qdp" a uma variável local
$qdp = $_POST["qdp"];

// Inclui o arquivo de conexão com o banco de dados
require_once("conexao.php");

// Inicia uma sessão ou resume a sessão atual
@session_start();

// Recupera a variável de sessão 'faz'
$faz = $_SESSION['faz'];

// Tentativa de execução de operações de banco de dados
try {
    // Prepara uma consulta SQL para recuperar informações do último estado dos pivôs
    $sql = "SELECT t1.* FROM statpiv_2 t1 INNER JOIN (SELECT piv, MAX(id) as max_id FROM statpiv_2 WHERE faz = :faz GROUP BY piv) t2 ON t1.piv = t2.piv AND t1.id = t2.max_id ORDER BY t1.piv;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':faz', $faz, PDO::PARAM_INT);
    $stmt->execute();

    // Inicializa arrays para armazenar os resultados
    $ult = $sta = $angf = $stp = $piv = array_fill(0, $qdp * 2, 0);
    $angi = [];
    $ax = 0;

    // Armazena os dados recuperados em arrays
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $piv[$ax]  = intval($row['piv']);
            $sta[$ax]  = intval($row['sta']);
            $ult[$ax]  = intval($row['ult']);
            $angi[$ax] = intval($row['ang_i']);
            $angf[$ax] = intval($row['ang_f']);
            $stp[$ax]  = intval($row['stp']);
            $ax++;
        }
    }

    // Prepara outra consulta para recuperar a velocidade dos pivôs
    $sql2 = "SELECT vel FROM pivs WHERE faz=:faz AND piv <= :qdp ORDER BY piv";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(':faz', $faz, PDO::PARAM_INT);
    $stmt->bindParam(':qdp', $qdp, PDO::PARAM_INT);
    $stmt->execute();
    $ax = 0;
    $tp = [];

    // Calcula e armazena o tempo proporcional baseado na velocidade e estado do pivô
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vel = $row['vel'];
            if ($ult[$ax] > 0) {
                $tp[] = (($vel / 360) * 100.0) / $ult[$ax];
                $ax++;
            } else {
                $tp[] = $ult[$ax];
            }
        }
    }

    // Encerra a conexão com o banco de dados
    $stmt->closeCursor();
    $pdo = null;

    // Retorna os resultados em formato JSON
    echo json_encode(array(
        'piv' => $piv,
        'sta' => $sta,
        'ang_i' => $angi,
        'ang_f' => $angf,
        'ang_s' => $stp,
        'tpa' => $tp
    ));
} catch (PDOException $e) {
    // Captura qualquer erro de PDO e termina a execução
    die("Erro de banco de dados: $e");
}
