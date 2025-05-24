<?php
function verificaUsuarioSenha(string $user, string $pass): bool {
    if (!$user || !$pass) {
        return false;
    }

    $usuarios = parse_ini_file('usuarios.ini', true);

    if (!isset($usuarios[$user])) {
        return false;
    }

    return $usuarios[$user]['password'] === $pass;
}