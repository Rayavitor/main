<?php
include 'config.php';
include 'auth.php';  // inclui a função verificaUsuarioSenha

$user = $_GET['user'] ?? '';
$pass = $_GET['pass'] ?? '';

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

// Lê o INI com dados dos canais
$canais = parse_ini_file('canais.ini', true);

header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: attachment; filename=\"lista.m3u\"");

echo "#EXTM3U\n";

foreach ($canais as $slug => $info) {
    $url = "canal.php?canal={$slug}&user=" . urlencode($user) . "&pass=" . urlencode($pass);

    $tvg_id = $info['tvg-id'] ?? '';
    $tvg_name = $info['tvg-name'] ?? $info['name'];
    $tvg_logo = $info['tvg-logo'] ?? '';
    $group = $info['group-title'] ?? '';

    echo "#EXTINF:-1 tvg-id=\"" . htmlspecialchars($tvg_id) . "\" tvg-name=\"" . htmlspecialchars($tvg_name) . "\" tvg-logo=\"" . htmlspecialchars($tvg_logo) . "\" group-title=\"" . htmlspecialchars($group) . "\"," . htmlspecialchars($info['name']) . "\n";
    echo "{$url}\n";
}
