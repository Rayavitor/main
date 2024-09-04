<?php
session_start();
header('Content-Type: application/json');

// Caminho para a pasta que contém os arquivos INI
$accounts_dir = 'C:\Users\RV\Desktop\GM MIAMI\scriptfiles\Contas';

// Recebe os dados do formulário
$user = $_POST['username'];
$pass = $_POST['password'];

// Caminho para o arquivo INI do usuário
$ini_file = $accounts_dir . '/' . $user . '.ini';

// Verifica se o arquivo INI do usuário existe
if (file_exists($ini_file)) {
    // Lê o conteúdo do arquivo INI
    $account_data = parse_ini_file($ini_file, true);

    // Verifica a senha
    if ($account_data['account']['password'] === $pass) {
        // Verifica se a conta está desativada (supondo que você tenha essa informação no arquivo INI)
        if (isset($account_data['account']['status']) && $account_data['account']['status'] === 'deactivated') {
            echo json_encode(['answer' => 'deactivated']);
        } else {
            // Senha correta e conta ativa
            $_SESSION['user'] = $user;
            echo json_encode(['answer' => 'success']);
        }
    } else {
        // Senha incorreta
        echo json_encode(['answer' => 'not_pass']);
    }
} else {
    // Usuário não encontrado
    echo json_encode(['answer' => 'not_account']);
}
?>