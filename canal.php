<?php
include 'config.php';
include 'auth.php';

$canal = $_GET['canal'] ?? '';
$user = $_GET['user'] ?? '';
$pass = $_GET['pass'] ?? '';

if (!$canal) {
    http_response_code(400);
    exit("Canal não especificado.");
}

if (!$user || !$pass) {
    http_response_code(400);
    exit("Usuário ou senha não especificados.");
}

if (!verificaUsuarioSenha($user, $pass)) {
    http_response_code(403);
    exit("Usuário ou senha inválidos.");
}

// REGISTRA O IP USADO POR ESTE USUÁRIO
$ip = $_SERVER['REMOTE_ADDR'];
$arquivo = __DIR__ . "/logins/{$user}.ini";

// Garante que a pasta exista
if (!is_dir(__DIR__ . "/logins")) {
    mkdir(__DIR__ . "/logins", 0777, true);
}

$dados = [];
if (file_exists($arquivo)) {
    $dados = parse_ini_file($arquivo, true);
}

$ipJaRegistrado = false;
if (isset($dados['IPs'])) {
    foreach ($dados['IPs'] as $ipExistente) {
        if ($ipExistente == $ip) {
            $ipJaRegistrado = true;
            break;
        }
    }
} else {
    $dados['IPs'] = [];
}

if (!$ipJaRegistrado) {
    $novaChave = "ip" . (count($dados['IPs']) + 1);
    $dados['IPs'][$novaChave] = $ip;

    // Salva de volta em formato .ini
    $conteudo = "";
    foreach ($dados as $secao => $valores) {
        $conteudo .= "[$secao]\n";
        foreach ($valores as $chave => $valor) {
            $conteudo .= "$chave = $valor\n";
        }
        $conteudo .= "\n";
    }

    file_put_contents($arquivo, $conteudo);
}

// Expiração do token (exemplo: 15 minutos)
$exp = time() + 5 * 60;

// Gera token incluindo usuário no hash
$token = hash_hmac('sha256', $canal . $user . $exp, SECRET_KEY);

// Redireciona para o stream incluindo user e pass
$redirectUrl = "https://hipe-player.onrender.com/stream.php?canal={$canal}&user=" . urlencode($user) . "&pass=" . urlencode($pass) . "&exp={$exp}&token={$token}";

header("Location: $redirectUrl");
exit;
