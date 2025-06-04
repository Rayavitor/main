<?php
$canal = $_GET['canal'] ?? '';

if (!$canal) {
    header("HTTP/1.1 400 Bad Request");
    exit("Canal não especificado.");
}

$m3u8_url = 'https://777.nossoplayeronlinehd.lat/fontes/nossoplayer/' . urlencode($canal) . '.m3u8';

function curlGet($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Referer: https://nossoplayeronlinehd.lat',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.5',
        'Connection: keep-alive'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0');

    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200 && $data !== false) {
        return $data;
    } else {
        echo "curl error: $curl_error | HTTP code: $http_code | URL: $url";
        return false;
    }
}

$content = curlGet($m3u8_url);

if ($content === false) {
    header("HTTP/1.1 404 Not Found");
    exit("Erro ao acessar o .m3u8");
}

header("Content-Type: application/vnd.apple.mpegurl");
echo $content;