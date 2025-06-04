<?php

// Lê o INI com dados dos canais
$canais = parse_ini_file('canais.ini', true);

header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: attachment; filename=\"lista.m3u\"");

echo "#EXTM3U\n";

foreach ($canais as $slug => $info) {
    $url = "https://hipe-player.onrender.com/stream.php?canal={$slug}";

    $tvg_id = $info['tvg-id'] ?? '';
    $tvg_name = $info['tvg-name'] ?? $info['name'];
    $tvg_logo = $info['tvg-logo'] ?? '';
    $group = $info['group-title'] ?? '';

    echo "#EXTINF:-1 tvg-id=\"" . htmlspecialchars($tvg_id) . "\" tvg-name=\"" . htmlspecialchars($tvg_name) . "\" tvg-logo=\"" . htmlspecialchars($tvg_logo) . "\" group-title=\"" . htmlspecialchars($group) . "\"," . htmlspecialchars($info['name']) . "\n";
    echo "{$url}\n";
}
