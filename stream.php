<?php
include 'config.php';
include 'auth.php';

$canal = $_GET['canal'] ?? '';
$user = $_GET['user'] ?? '';
$pass = $_GET['pass'] ?? '';
$exp = $_GET['exp'] ?? 0;
$token = $_GET['token'] ?? '';
$seg = $_GET['seg'] ?? null;

if (!$user || !$pass) {
    header("HTTP/1.1 400 Bad Request");
    exit("Usuário ou senha não especificados.");
}

if (!verificaUsuarioSenha($user, $pass)) {
    header("HTTP/1.1 403 Forbidden");
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

// Verifica token incluindo usuário e senha no hash
$expected = hash_hmac('sha256', $canal . $user . $exp, SECRET_KEY);

if ($token !== $expected) {
    header("HTTP/1.1 403 Forbidden");
    exit("Token inválido.");
}

if (time() > $exp) {
    $urlNova = "https://hipe-player.onrender.com/canal.php?canal=" . urlencode($canal) . "&user=" . urlencode($user) . "&pass=" . urlencode($pass);
    header("Location: $urlNova");
    exit;
}

// Função para cURL com Referer
function curlGet($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Referer: https://nossoplayeronlinehd.com/'
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($http_code === 200 && $data !== false) {
        return $data;
    }
    return false;
}

if ($seg) {
    $mapFile = "segments/segments_map_{$canal}_{$user}_" . md5($pass) . "_{$exp}.php"; // inclui hash da senha no nome
    if (!file_exists($mapFile)) {
        header("HTTP/1.1 404 Not Found");
        exit("Mapa de segmentos não encontrado.");
    }

    $segments = include $mapFile;

    if (!isset($segments[$seg])) {
        header("HTTP/1.1 404 Not Found");
        exit("Segmento inválido.");
    }

    $ts_url = $segments[$seg];
    $ts_data = curlGet($ts_url);
    if ($ts_data !== false) {
        header("Content-Type: video/MP2T");
        echo $ts_data;
        exit;
    } else {
        header("HTTP/1.1 404 Not Found");
        exit("Erro ao acessar o segmento .ts");
    }
}

$base_url = 'https://fazoeli.fun/fontes/nossoplayer/';
$m3u8_url = $base_url . "$canal.m3u8";

$content = curlGet($m3u8_url);
if ($content === false) {
    header("HTTP/1.1 404 Not Found");
    exit("Erro ao acessar o .m3u8");
}

$segmentMap = [];
$content = preg_replace_callback('/https?:\/\/[^\s]+\.ts/', function($matches) use (&$segmentMap, $canal, $user, $pass, $exp, $token) {
    $id = uniqid();
    $segmentMap[$id] = $matches[0];
    // Passa pass e token junto para cada segmento
    return "stream.php?canal={$canal}&user=" . urlencode($user) . "&pass=" . urlencode($pass) . "&exp={$exp}&token={$token}&seg={$id}";
}, $content);

$mapFile = "segments/segments_map_{$canal}_{$user}_" . md5($pass) . "_{$exp}.php";
file_put_contents($mapFile, "<?php\nreturn " . var_export($segmentMap, true) . ";\n");

// Limpa mapas antigos
foreach (glob("segments/segments_map_*.php") as $file) {
    if (preg_match('/segments_map_.*_(\d+)\.php$/', basename($file), $match)) { // Antigo $file, $match
        if ((int)$match[1] < time()) {
            @unlink($file);
        }
    }
}

header("Content-Type: application/vnd.apple.mpegurl");
echo $content;
