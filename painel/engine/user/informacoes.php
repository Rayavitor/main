<?php
session_start();

// Caminho para a pasta que contém os arquivos INI
$accounts_dir = 'C:\Users\RV\Desktop\GM MIAMI\scriptfiles\Contas';

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: painel.html'); // Redirecionar se não estiver logado
    exit();
}

$user = $_SESSION['user'];
$ini_file = $accounts_dir . '/' . $user . '.ini';

// Verificar se o arquivo INI do usuário existe
if (file_exists($ini_file)) {
    // Ler o conteúdo do arquivo INI
    $account_data = parse_ini_file($ini_file, true);
    
    // Verificar se as chaves existem no array antes de acessá-las
    $coins = isset($account_data['account']['coins']) ? $account_data['account']['coins'] : 'Valor não encontrado';
    $dinhero = isset($account_data['account']['dinhero']) ? $account_data['account']['dinhero'] : 'Valor não encontrado';
} else {
    die("Arquivo INI não encontrado para o usuário: " . $user);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Informações do Usuário</title>
</head>
<body>
    <ul>
        <li>COINS: <?php echo $coins; ?></li>
        <li>DINHEIRO: <?php echo $dinhero; ?></li>
    </ul>
</body>
</html>