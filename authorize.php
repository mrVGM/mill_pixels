<?php

function authorize() {
    $username = $_COOKIE['username'];
    $token = $_COOKIE['token'];
    
    if (!$username || !$token) {
        return false;
    }
    
    require_once('../db/repository.php');
    $login = get_login($username);
    
    if (!$login || $login['token'] !== $token || time() > $login['expiration']) {
        return false;
    }
    refresh_login($username);
    return true;
}

function authenticate() {
    return $_COOKIE['username'];
}

?>