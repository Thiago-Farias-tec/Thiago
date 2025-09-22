<?php
$arquivo = "perguntas.txt";

function criarArquivo($arquivo) {
    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, "pergunta1;pergunta2;pergunta3;pergunta4;pergunta5;\n");
    }
}

if(isset($_POST['acao'])){
    $pergunta1       = $_POST["pergunta1"];
    $pergunta2    = $_POST["pergunta2"];
    $pergunta3      = $_POST["pergunta3"];
    $pergunta4 = $_POST["pergunta4"];
    $pergunta5     = $_POST["pergunta5"];

    criarArquivo($arquivo);

    if($_POST['acao'] == "salvar"){
        file_put_contents($arquivo, "$pergunta1;$pergunta2;$pergunta3;$pergunta4;$pergunta5;\n", FILE_APPEND);
    }

    if($_POST['acao'] == "alterar" && isset($_POST['id'])){
        $linhas = file($arquivo);
        $linhas[$_POST['id']] = "$pergunta1;$pergunta2;$pergunta3;$pergunta4;$pergunta5;\n";
        file_put_contents($arquivo, implode("", $linhas));
    }
}

if(isset($_GET['del'])){
    $linhas = file($arquivo);
    unset($linhas[$_GET['del']]);
    file_put_contents($arquivo, implode("", $linhas));
}

$editar = null;
if(isset($_GET['edit'])){
    $linhas = file($arquivo);
    if(isset($linhas[$_GET['edit']])){
        $dados = explode(";", trim($linhas[$_GET['edit']]));
        if(count($dados) >= 5){
            $editar = [
                'id' => $_GET['edit'],
                'pergunta1' => $dados[0],
                'pergunta2' => $dados[1],
                'pergunta3' => $dados[2],
                'pergunta4' => $dados[3],
                'pergunta5' => $dados[4],
            ];
        }
    }
}
?>

<form method="post">
    <input type="hidden" name="acao" value="<?= $editar ? 'alterar' : 'salvar' ?>">
    <?php if($editar): ?>
        <input type="hidden" name="id" value="<?= $editar['id'] ?>">
    <?php endif; ?>

    pergunta 1: <input name="pergunta1" value="<?= $editar['pergunta1'] ?? '' ?>"><br>
    pergunta 2: <input name="pergunta2" value="<?= $editar['pergunta2'] ?? '' ?>"><br>
    pergunta 3: <input name="pergunta3" value="<?= $editar['pergunta3'] ?? '' ?>"><br>
    pergunta 4: <input name="pergunta4" value="<?= $editar['pergunta4'] ?? '' ?>"><br>
    <select name="pergunta5" required="required">
        <option value="">Multiplica escolha</option>
        <option value="A" <?= isset($editar) && $editar['pergunta5'] == 'A' ? 'selected' : '' ?>>A</option>
        <option value="B" <?= isset($editar) && $editar['pergunta5'] == 'B' ? 'selected' : '' ?>>B</option>
        <option value="C" <?= isset($editar) && $editar['pergunta5'] == 'C' ? 'selected' : '' ?>>C</option>
        <option value="D" <?= isset($editar) && $editar['pergunta5'] == 'D' ? 'selected' : '' ?>>D</option>
    </select>
    <button><?= $editar ? 'Alterar' : 'Salvar' ?></button>
</form>

<hr>

<?php
if(file_exists($arquivo)){
    $linhas = file($arquivo);
    foreach($linhas as $i => $linha){
        if($i == 0) continue; 
        $d = explode(";", trim($linha));
        if(count($d) < 5) continue;
        echo "$d[0] | $d[1] | $d[2] | $d[3] | $d[4] ".
             "<a href='?edit=$i'>Alterar</a> | ".
             "<a href='?del=$i' onclick=\"return confirm('Excluir?')\">Excluir</a><br>";
    }
} else {
    echo "Nenhuma pergunta.";
}
?>