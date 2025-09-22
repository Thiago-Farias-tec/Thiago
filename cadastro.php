<?php
$arquivo = "cadastro.txt";

if(isset($_POST['acao'])){
    $nome  = $_POST["nome"];
    $senha = $_POST["senha"];

    if(!file_exists($arquivo)){
        file_put_contents($arquivo, "nome;senha;\n");
    }

    if($_POST['acao'] == "salvar"){
        file_put_contents($arquivo, "$nome;$senha;\n", FILE_APPEND);
    }

    if($_POST['acao'] == "alterar" && isset($_POST['id'])){
        $linhas = file($arquivo);
        $linhas[$_POST['id']] = "$nome;$senha;\n";
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
        if(count($dados) >= 2){
            $editar = [
                'id' => $_GET['edit'],
                'nome' => $dados[0],
                'senha' => $dados[1],
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

    Nome:  <input name="nome" value="<?= $editar['nome'] ?? '' ?>"><br>
    Senha: <input name="senha" value="<?= $editar['senha'] ?? '' ?>"><br>
    <button><?= $editar ? 'Alterar' : 'Salvar' ?></button>
</form>

<hr>

<?php
if(file_exists($arquivo)){
    $linhas = file($arquivo);
    foreach($linhas as $i => $linha){
        if($i == 0) continue; 
        $d = explode(";", trim($linha));
        if(count($d) < 2) continue;
        echo "$d[0] | $d[1] ".
             "<a href='?edit=$i'>Alterar</a> | ".
             "<a href='?del=$i' onclick=\"return confirm('Excluir?')\">Excluir</a><br>";
    }
} else {
    echo "Nenhum cadastro.";
}
?>
